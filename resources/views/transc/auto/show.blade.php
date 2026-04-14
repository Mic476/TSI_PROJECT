@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
{{-- section content --}}
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => ''])
    @if (($dmenu ?? '') == 'trdper' || ($url_menu ?? '') == 'daftar-periodic-hrd' || request()->is('daftar-periodic-hrd/show/*'))
        <style>
                .periodic-page-wrap {
                    margin-top: 10px;
                }

                .periodic-card {
                    border-radius: 16px;
                    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
                    overflow: hidden;
                }

                .periodic-header {
                    background: linear-gradient(135deg, #eff6ff 0%, #fdf4ff 45%, #fef9c3 100%);
                    border-bottom: 2px solid #0ea5a5;
                    padding: 18px 20px 16px;
                }

                .periodic-header h4 {
                    margin: 0;
                    line-height: 1.35;
                }

                .periodic-table thead {
                    background: rgba(14, 165, 165, 0.2);
                }

                .periodic-table thead th {
                    font-weight: 600;
                    text-transform: uppercase;
                    font-size: 0.75rem;
                    letter-spacing: 0.06em;
                }

                .periodic-table tbody tr {
                    border-bottom: 1px solid #e2e8f0;
                }

                .btn-delete-row {
                    background: #ef4444;
                    border: none;
                    color: #fff;
                    width: 36px;
                    height: 36px;
                    border-radius: 10px;
                    font-weight: 700;
                }

                .btn-add-row {
                    background: #2563eb;
                    border: none;
                    color: #fff;
                    border-radius: 10px;
                    padding: 8px 16px;
                    font-weight: 600;
                }

                .btn-add-row:hover {
                    background: #1d4ed8;
                }

                .periodic-input {
                    background: #f1f5f9;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    padding: 6px 10px;
                    font-size: 0.85rem;
                }

                .plan-dates-container {
                    max-height: 200px;
                    overflow-y: auto;
                    min-width: 150px;
                }

                .plan-dates-container .plan-date-input {
                    font-size: 0.85rem;
                }

                .periodic-note {
                    border-left: 4px solid #f59e0b;
                    background: #fffbeb;
                    color: #92400e;
                    border-radius: 10px;
                    padding: 10px 12px;
                    font-size: 0.85rem;
                }
            </style>
        @php
            $selectedYear = (int) $idencrypt;
            $headerRow = \Illuminate\Support\Facades\DB::table('pl_periodic_header')
                ->where('tahun', $selectedYear)
                ->where('is_active', '1')
                ->first();
            $keterangan = $headerRow->keterangan ?? 'Daftar Periodic HRD';
            $headerId = $headerRow->id ?? null;

            $detailRows = collect();
            if ($headerId) {
                $detailRows = \Illuminate\Support\Facades\DB::table('pl_periodic_detail')
                    ->leftJoin('ms_periodic', 'pl_periodic_detail.periodic_id', '=', 'ms_periodic.id')
                    ->leftJoin('ms_area', 'pl_periodic_detail.area_id', '=', 'ms_area.id')
                    ->leftJoin('users', 'pl_periodic_detail.worker_id', '=', 'users.id')
                    ->select(
                        'pl_periodic_detail.id',
                        'pl_periodic_detail.periodic_id',
                        'pl_periodic_detail.area_id',
                        'pl_periodic_detail.worker_id',
                        'pl_periodic_detail.periode',
                        'pl_periodic_detail.cycle',
                        'pl_periodic_detail.start_plan_date',
                        'ms_periodic.job_description',
                        'ms_area.nama_area as area_name',
                        \Illuminate\Support\Facades\DB::raw("CONCAT(users.firstname, ' ', COALESCE(users.lastname, '')) as worker_name")
                    )
                    ->where('pl_periodic_detail.header_id', $headerId)
                    ->where('pl_periodic_detail.is_active', '1')
                    ->orderBy('pl_periodic_detail.id')
                    ->get();
            }

            $periodics = \Illuminate\Support\Facades\DB::table('ms_periodic')
                ->select('ms_periodic.id', 'ms_periodic.job_description')
                ->where('ms_periodic.is_active', '1')
                ->orderBy('ms_periodic.job_description')
                ->get();

            $areas = \Illuminate\Support\Facades\DB::table('ms_area')
                ->orderBy('nama_area')
                ->get();

            // Build workers dropdown from all active roles with ptg prefix
            $petugasRoles = \Illuminate\Support\Facades\DB::table('sys_roles')
                ->where('isactive', '1')
                ->where('idroles', 'like', 'ptg%')
                ->orderBy('idroles')
                ->get(['idroles', 'name']);

            $activeUsers = \Illuminate\Support\Facades\DB::table('users')
                ->where('isactive', '1')
                ->select('id', 'idroles')
                ->orderBy('id')
                ->get();

            $workers = collect([]);
            foreach ($petugasRoles as $role) {
                $roleCode = strtolower(trim((string) $role->idroles));

                $matchedUser = $activeUsers->first(function ($user) use ($roleCode) {
                    $userRoles = array_map('trim', explode(',', strtolower((string) ($user->idroles ?? ''))));
                    return in_array($roleCode, $userRoles, true);
                });

                if ($matchedUser) {
                    $workers->push((object) [
                        'id' => $matchedUser->id,
                        'label' => !empty($role->name) ? $role->name : strtoupper(substr($roleCode, 3)),
                    ]);
                }
            }

            $dateRows = \Illuminate\Support\Facades\DB::table('pl_periodic_items')
                ->join('pl_periodic_detail', 'pl_periodic_items.detail_id', '=', 'pl_periodic_detail.id')
                ->select('pl_periodic_detail.id as detail_id', 'pl_periodic_items.planned_date', 'pl_periodic_items.realization_date')
                ->where('pl_periodic_detail.header_id', $headerId)
                ->where('pl_periodic_detail.is_active', '1')
                ->where('pl_periodic_items.is_active', '1')
                ->whereYear('pl_periodic_items.planned_date', $selectedYear)
                ->orderBy('pl_periodic_items.planned_date')
                ->get();
            $dateMap = [];
            foreach ($dateRows as $row) {
                $dateMap[$row->detail_id][] = [
                    'plan_date' => $row->planned_date,
                    'realization_date' => $row->realization_date,
                ];
            }
        @endphp
        <div class="card shadow-lg mx-4">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-lg">
                        <div class="nav-wrapper">
                            {{-- button back --}}
                            <button class="btn btn-secondary mb-0" type="button" id="periodic-back">
                                <i class="fas fa-circle-left me-1"></i><span class="font-weight-bold">Kembali</span>
                            </button>
                            {{-- button save --}}
                            <button class="btn btn-primary mb-0" type="button" id="periodic-save">
                                <i class="fas fa-floppy-disk me-1"></i><span class="font-weight-bold">Simpan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4 periodic-page-wrap">
            <div class="card periodic-card mb-4">
                <div class="periodic-header">
                    <h4 class="mb-1">Daftar Periodic HRD</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Tahun</label>
                            <input type="text" class="form-control" id="periodic-year" value="{{ $selectedYear }}" readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="periodic-keterangan" value="{{ $keterangan }}" readonly>
                        </div>
                    </div>
                    <div class="periodic-note mb-3">
                        Note: Tanggal rencana disarankan input di awal minggu <strong>Senin - Rabu</strong>.
                    </div>
                    <div class="table-responsive">
                        <table class="table periodic-table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 90px;">Action</th>
                                    <th>No</th>
                                    <th>Area</th>
                                    <th>Pekerjaan</th>
                                    <th>Periode</th>
                                    <th>Cycle</th>
                                    <th>Start Rencana</th>
                                    <th>Rencana</th>
                                    <th>Realisasi</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody id="periodic-detail-body">
                                @forelse ($detailRows as $detail)
                                    <tr data-id="{{ $detail->id }}">
                                        <td class="text-center">
                                            <button type="button" class="btn-delete-row" title="Hapus">
                                                X
                                            </button>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <select class="form-select form-select-sm area-select">
                                                <option value="">Pilih Area</option>
                                                @foreach ($areas as $area)
                                                    <option value="{{ $area->id }}"
                                                        {{ $area->id == $detail->area_id ? 'selected' : '' }}>
                                                        {{ $area->nama_area ?? '-' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm periodic-select">
                                                <option value="">Pilih Pekerjaan</option>
                                                @foreach ($periodics as $periodic)
                                                    <option value="{{ $periodic->id }}"
                                                        {{ $periodic->id == $detail->periodic_id ? 'selected' : '' }}>
                                                        {{ $periodic->job_description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm periode-select">
                                                <option value="">Pilih</option>
                                                <option value="mingguan" {{ $detail->periode === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                                <option value="bulanan" {{ $detail->periode === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                <option value="tahunan" {{ $detail->periode === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" min="1" class="form-control form-control-sm cycle-input"
                                                value="{{ $detail->cycle ?? '' }}">
                                        </td>
                                        <td>
                                            <input type="date" class="form-control form-control-sm start-plan-date-input"
                                                value="{{ $detail->start_plan_date ?? '' }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn-plan-dates"
                                                data-periodic="{{ $detail->periodic_id ?? '' }}"
                                                data-cycle="{{ $detail->cycle ?? 0 }}">
                                                Detail
                                            </button>
                                            @php
                                                $existingDates = \Illuminate\Support\Facades\DB::table('pl_periodic_items')
                                                    ->where('detail_id', $detail->id)
                                                    ->orderBy('planned_date')
                                                    ->pluck('planned_date')
                                                    ->toArray();
                                            @endphp
                                            <input type="hidden" class="plan-dates-data" value="{{ json_encode($existingDates) }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info btn-date-detail"
                                                data-type="realization" data-detail="{{ $detail->id ?? '' }}">
                                                Detail
                                            </button>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm worker-select">
                                                <option value="">Pilih Petugas</option>
                                                @foreach ($workers as $worker)
                                                    <option value="{{ $worker->id }}"
                                                        {{ $worker->id == $detail->worker_id ? 'selected' : '' }}>
                                                        {{ $worker->label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            Belum ada jadwal kerja periodic.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn-add-row" id="periodic-add-row">+ Tambah</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="dateDetailModal" tabindex="-1" aria-labelledby="dateDetailLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dateDetailLabel">Detail Tanggal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="dateDetailBody">
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Tidak ada data.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Plan Dates Input -->
        <div class="modal fade" id="planDatesModal" tabindex="-1" aria-labelledby="planDatesLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="planDatesLabel">Input Tanggal Rencana</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="planDatesInputContainer" class="mb-3">
                            <!-- Inputs will be generated here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="savePlanDates">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        @push('js')
            <script>
                const dateMap = @json($dateMap);
                const headerYearInput = document.getElementById('periodic-year');
                const headerDescriptionInput = document.getElementById('periodic-keterangan');
                let isDirty = false;

                const addRowButton = document.getElementById('periodic-add-row');
                const detailBody = document.getElementById('periodic-detail-body');
                const saveButton = document.getElementById('periodic-save');
                const backButton = document.getElementById('periodic-back');

                function reindexDetailRows() {
                    if (!detailBody) {
                        return;
                    }

                    const rows = Array.from(detailBody.querySelectorAll('tr'))
                        .filter((row) => !row.querySelector('td[colspan]'));

                    rows.forEach((row, index) => {
                        const noCell = row.querySelector('td:nth-child(2)');
                        if (noCell) {
                            noCell.textContent = index + 1;
                        }
                    });
                }

                function setDirty(value = true) {
                    isDirty = value;
                }

                function showAlert({
                    icon = 'warning',
                    title = 'Perhatian',
                    text = ''
                }) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon,
                            title,
                            text,
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    alert(text || title);
                }

                function buildSelect(options, className, placeholder, value = '') {
                    const select = document.createElement('select');
                    select.className = `form-select form-select-sm ${className}`;
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = placeholder;
                    select.appendChild(defaultOption);
                    options.forEach((opt) => {
                        const option = document.createElement('option');
                        option.value = opt.value;
                        option.textContent = opt.label;
                        if (opt.value === value) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                    select.addEventListener('change', () => setDirty());
                    return select;
                }

                function buildInput(placeholder, className, type = 'text') {
                    const input = document.createElement('input');
                    input.type = type;
                    input.className = className;
                    input.placeholder = placeholder;
                    input.addEventListener('input', () => setDirty());
                    return input;
                }

                const periodicOptions = @json($periodics->map(fn($item) => ['value' => (string) $item->id, 'label' => $item->job_description]));
                const areaOptions = @json($areas->map(fn($item) => ['value' => (string) $item->id, 'label' => $item->nama_area]));
                const workerOptions = @json($workers->map(fn($item) => ['value' => (string) $item->id, 'label' => $item->label]));

                function updateRowPeriodic(row) {
                    const periodicSelect = row.querySelector('.periodic-select');
                    const buttons = row.querySelectorAll('.btn-date-detail');
                    if (!periodicSelect) {
                        return;
                    }
                    const detailId = row.dataset.id || '';
                    buttons.forEach((btn) => {
                        btn.dataset.detail = detailId;
                    });
                }

                function addDetailRow() {
                    if (!detailBody) {
                        return;
                    }

                    const emptyRow = detailBody.querySelector('td[colspan]')?.closest('tr');
                    if (emptyRow) {
                        emptyRow.remove();
                    }

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-center">
                            <button type="button" class="btn-delete-row" title="Hapus">X</button>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    `;

                    const cells = row.querySelectorAll('td');
                    cells[1].textContent = '';

                    const areaSelect = buildSelect(areaOptions, 'area-select', 'Pilih Area');
                    const periodicSelect = buildSelect(periodicOptions, 'periodic-select', 'Pilih Pekerjaan');
                    const periodeSelect = buildSelect(
                        [
                            { value: 'mingguan', label: 'Mingguan' },
                            { value: 'bulanan', label: 'Bulanan' },
                            { value: 'tahunan', label: 'Tahunan' },
                        ],
                        'periode-select',
                        'Pilih'
                    );
                    const cycleInput = buildInput('Cycle', 'form-control form-control-sm cycle-input', 'number');
                    cycleInput.min = 1;
                    const startPlanDateInput = buildInput('', 'form-control form-control-sm start-plan-date-input', 'date');
                    const workerSelect = buildSelect(workerOptions, 'worker-select', 'Pilih Petugas');

                    const planButton = document.createElement('button');
                    planButton.type = 'button';
                    planButton.className = 'btn btn-sm btn-primary btn-plan-dates';
                    planButton.dataset.periodic = '';
                    planButton.dataset.cycle = '0';
                    planButton.textContent = 'Detail';

                    const planDatesHidden = document.createElement('input');
                    planDatesHidden.type = 'hidden';
                    planDatesHidden.className = 'plan-dates-data';
                    planDatesHidden.value = '[]';

                    const realizationButton = document.createElement('button');
                    realizationButton.type = 'button';
                    realizationButton.className = 'btn btn-sm btn-info btn-date-detail';
                    realizationButton.dataset.type = 'realization';
                    realizationButton.dataset.detail = '';
                    realizationButton.textContent = 'Detail';

                    cells[2].appendChild(areaSelect);
                    cells[3].appendChild(periodicSelect);
                    cells[4].appendChild(periodeSelect);
                    cells[5].appendChild(cycleInput);
                    cells[6].appendChild(startPlanDateInput);
                    cells[7].appendChild(planButton);
                    cells[7].appendChild(planDatesHidden);
                    cells[8].appendChild(realizationButton);
                    cells[9].appendChild(workerSelect);

                    const deleteButton = row.querySelector('.btn-delete-row');
                    deleteButton.addEventListener('click', () => handleDeleteRow(deleteButton));

                    periodicSelect.addEventListener('change', () => updateRowPeriodic(row));
                    
                    planButton.addEventListener('click', function() {
                        openPlanDatesModal(this);
                    });

                    attachAutoPlanDateListeners(row);

                    detailBody.appendChild(row);
                    reindexDetailRows();
                    setDirty();
                }

                async function handleDeleteRow(button) {
                    const row = button.closest('tr');
                    if (!row) {
                        return;
                    }

                    const confirmation = typeof Swal !== 'undefined'
                        ? await Swal.fire({
                            title: 'Konfirmasi Hapus',
                            text: 'Yakin ingin menghapus baris ini?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        })
                        : {
                            isConfirmed: confirm('Yakin ingin menghapus baris ini?')
                        };

                    if (!confirmation.isConfirmed) {
                        return;
                    }
                    const detailId = row.dataset.id;
                    if (!detailId) {
                        row.remove();
                        reindexDetailRows();
                        setDirty();
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        document.querySelector('input[name="_token"]')?.value;

                    fetch('{{ route("periodic-schedule.detail-delete") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            detail_id: detailId
                        })
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            row.remove();
                            reindexDetailRows();
                        } else {
                            showAlert({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Gagal menghapus data.'
                            });
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        showAlert({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat menghapus data.'
                        });
                    });
                }

                function openDateModal(detailId, type) {
                    const modalBody = document.getElementById('dateDetailBody');
                    if (!modalBody) {
                        return;
                    }
                    modalBody.innerHTML = '';
                    const rows = dateMap[detailId] || [];
                    const list = rows
                        .map((row) => type === 'plan' ? row.plan_date : row.realization_date)
                        .filter((date) => !!date);

                    if (list.length === 0) {
                        modalBody.innerHTML = '<tr><td colspan="2" class="text-center text-muted">Tidak ada data.</td></tr>';
                    } else {
                        list.forEach((date, index) => {
                            const tr = document.createElement('tr');
                            const dateValue = new Date(date);
                            const formatted = isNaN(dateValue) ? date : dateValue.toLocaleDateString('id-ID');
                            tr.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${formatted}</td>
                            `;
                            modalBody.appendChild(tr);
                        });
                    }

                    const modalTitle = document.getElementById('dateDetailLabel');
                    if (modalTitle) {
                        modalTitle.textContent = type === 'plan' ? 'Detail Rencana' : 'Detail Realisasi';
                    }
                    const modalInstance = new bootstrap.Modal(document.getElementById('dateDetailModal'));
                    modalInstance.show();
                }

                let currentPlanDatesRow = null;

                function parseIsoDate(isoDate) {
                    if (!isoDate || typeof isoDate !== 'string') {
                        return null;
                    }
                    const parts = isoDate.split('-').map((part) => parseInt(part, 10));
                    if (parts.length !== 3 || parts.some((part) => Number.isNaN(part))) {
                        return null;
                    }
                    const [year, month, day] = parts;
                    return new Date(year, month - 1, day);
                }

                function formatIsoDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }

                function advanceOnePeriodDate(currentDate, periode) {
                    const nextDate = new Date(currentDate);

                    if (periode === 'mingguan') {
                        nextDate.setDate(nextDate.getDate() + 7);
                        return nextDate;
                    }

                    if (periode === 'bulanan') {
                        nextDate.setMonth(nextDate.getMonth() + 1);
                        return nextDate;
                    }

                    nextDate.setFullYear(nextDate.getFullYear() + 1);
                    return nextDate;
                }

                function generatePlanDatesFromRow(row) {
                    const cycle = parseInt(row.querySelector('.cycle-input')?.value, 10) || 0;
                    const startPlanDate = row.querySelector('.start-plan-date-input')?.value || '';
                    const periode = row.querySelector('.periode-select')?.value || '';

                    if (cycle <= 0 || !startPlanDate || !periode) {
                        return [];
                    }

                    const seedDate = parseIsoDate(startPlanDate);
                    if (!seedDate || Number.isNaN(seedDate.getTime())) {
                        return [];
                    }

                    const currentBlockStart = new Date(seedDate);
                    const nextBlockStart = advanceOnePeriodDate(currentBlockStart, periode);
                    const blockEnd = new Date(nextBlockStart);
                    blockEnd.setDate(blockEnd.getDate() - 1);

                    const diffMs = blockEnd.getTime() - currentBlockStart.getTime();
                    const blockLengthInDays = Math.max(1, Math.floor(diffMs / (1000 * 60 * 60 * 24)) + 1);
                    const generated = [];

                    const dayOfWeek = currentBlockStart.getDay(); // 0: Minggu, 1: Senin, ...
                    const remainingDaysInThisWeek = ((7 - dayOfWeek) % 7) + 1;
                    const shouldKeepSameWeek =
                        periode === 'mingguan' &&
                        cycle <= remainingDaysInThisWeek;

                    // Match backend logic: cycle is frequency within one period window.
                    for (let index = 0; index < cycle; index++) {
                        let offset = 0;
                        if (shouldKeepSameWeek) {
                            // Keep dates in the same week and spread them across remaining days.
                            offset = Math.floor((index * remainingDaysInThisWeek) / cycle);
                            if (offset >= remainingDaysInThisWeek) {
                                offset = remainingDaysInThisWeek - 1;
                            }
                        } else {
                            offset = Math.floor((index * blockLengthInDays) / cycle);
                            if (offset >= blockLengthInDays) {
                                offset = blockLengthInDays - 1;
                            }
                        }

                        const candidate = new Date(currentBlockStart);
                        candidate.setDate(candidate.getDate() + offset);
                        generated.push(formatIsoDate(candidate));
                    }

                    return generated;
                }

                function setPlanDatesForRow(row, dates) {
                    const planDatesData = row.querySelector('.plan-dates-data');
                    if (planDatesData) {
                        const nextValue = JSON.stringify(dates);
                        const changed = planDatesData.value !== nextValue;
                        planDatesData.value = nextValue;
                        return changed;
                    }
                    return false;
                }

                function syncModalPlanDatesToRow() {
                    if (!currentPlanDatesRow) {
                        return;
                    }
                    const inputs = document.querySelectorAll('.plan-date-input-modal');
                    const dates = Array.from(inputs).map((input) => input.value);
                    const changed = setPlanDatesForRow(currentPlanDatesRow, dates);
                    if (changed) {
                        setDirty();
                    }
                }

                function attachAutoPlanDateListeners(row) {
                    const autoGenerateHandler = (markDirty = false) => {
                        const generated = generatePlanDatesFromRow(row);
                        if (generated.length > 0) {
                            const changed = setPlanDatesForRow(row, generated);
                            if (markDirty && changed) {
                                setDirty();
                            }
                        }
                    };

                    row.querySelector('.cycle-input')?.addEventListener('input', () => autoGenerateHandler(true));
                    row.querySelector('.start-plan-date-input')?.addEventListener('input', () => autoGenerateHandler(true));
                    row.querySelector('.cycle-input')?.addEventListener('change', () => autoGenerateHandler(true));
                    row.querySelector('.start-plan-date-input')?.addEventListener('change', () => autoGenerateHandler(true));
                    row.querySelector('.periode-select')?.addEventListener('change', () => autoGenerateHandler(true));

                    autoGenerateHandler(false);
                }

                function openPlanDatesModal(button) {
                    const row = button.closest('tr');
                    currentPlanDatesRow = row;
                    
                    const cycleInput = row.querySelector('.cycle-input');
                    const cycle = parseInt(cycleInput?.value) || 0;
                    
                    if (cycle === 0) {
                        showAlert({
                            icon: 'warning',
                            title: 'Cycle Tidak Valid',
                            text: 'Cycle harus lebih dari 0.'
                        });
                        return;
                    }

                    const planDatesData = row.querySelector('.plan-dates-data');
                    let existingDates = [];
                    try {
                        existingDates = JSON.parse(planDatesData?.value || '[]');
                    } catch (e) {
                        existingDates = [];
                    }

                    const generatedDates = generatePlanDatesFromRow(row);
                    if (existingDates.length === 0 && generatedDates.length > 0) {
                        existingDates = generatedDates;
                        setPlanDatesForRow(row, generatedDates);
                    }

                    const container = document.getElementById('planDatesInputContainer');
                    container.innerHTML = '';

                    for (let i = 0; i < cycle; i++) {
                        const formGroup = document.createElement('div');
                        formGroup.className = 'mb-2';
                        
                        const label = document.createElement('label');
                        label.className = 'form-label';
                        label.textContent = `Tanggal ${i + 1}`;
                        
                        const input = document.createElement('input');
                        input.type = 'date';
                        input.className = 'form-control plan-date-input-modal';
                        input.value = existingDates[i] || generatedDates[i] || '';
                        input.addEventListener('input', syncModalPlanDatesToRow);
                        input.addEventListener('change', syncModalPlanDatesToRow);
                        
                        formGroup.appendChild(label);
                        formGroup.appendChild(input);
                        container.appendChild(formGroup);
                    }

                    const modalInstance = new bootstrap.Modal(document.getElementById('planDatesModal'));
                    modalInstance.show();
                }

                document.getElementById('savePlanDates')?.addEventListener('click', function() {
                    if (!currentPlanDatesRow) return;

                    syncModalPlanDatesToRow();

                    const modalElement = document.getElementById('planDatesModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();
                });

                document.getElementById('planDatesModal')?.addEventListener('hidden.bs.modal', function() {
                    syncModalPlanDatesToRow();
                    currentPlanDatesRow = null;
                });

                function collectDetails() {
                    const rows = Array.from(detailBody.querySelectorAll('tr'))
                        .filter((row) => !row.querySelector('td[colspan]'));
                    return rows.map((row) => {
                        const detailId = row.dataset.id || null;
                        const periodicId = row.querySelector('.periodic-select')?.value || '';
                        const areaId = row.querySelector('.area-select')?.value || '';
                        const workerId = row.querySelector('.worker-select')?.value || null;
                        const periode = row.querySelector('.periode-select')?.value || '';
                        const cycle = row.querySelector('.cycle-input')?.value || '';
                        const startPlanDate = row.querySelector('.start-plan-date-input')?.value || '';
                        
                        const planDatesData = row.querySelector('.plan-dates-data');
                        let planDates = [];
                        try {
                            planDates = JSON.parse(planDatesData?.value || '[]').filter(date => date !== '');
                        } catch (e) {
                            planDates = [];
                        }
                        
                        return {
                            id: detailId,
                            periodic_id: periodicId,
                            area_id: areaId,
                            worker_id: workerId,
                            periode: periode,
                            cycle: cycle,
                            start_plan_date: startPlanDate || null,
                            plan_dates: planDates
                        };
                    });
                }

                function saveDetails() {
                    const details = collectDetails();
                    if (details.length === 0) {
                        showAlert({
                            icon: 'warning',
                            title: 'Data Belum Lengkap',
                            text: 'Detail harus diisi minimal 1 baris.'
                        });
                        return;
                    }

                    const headerYear = headerYearInput ? parseInt(headerYearInput.value, 10) : null;
                    if (!headerYear || Number.isNaN(headerYear)) {
                        showAlert({
                            icon: 'warning',
                            title: 'Tahun Tidak Valid',
                            text: 'Tahun tidak valid.'
                        });
                        return;
                    }

                    const invalid = details.find((item) => !item.periodic_id || !item.area_id || !item.worker_id || !item.periode || !item.cycle || !item.start_plan_date);
                    if (invalid) {
                        showAlert({
                            icon: 'warning',
                            title: 'Lengkapi Data',
                            text: 'Lengkapi data: Pekerjaan, Area, Petugas, Periode, Cycle, dan Start Rencana.'
                        });
                        return;
                    }

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                        document.querySelector('input[name="_token"]')?.value;

                    const headerDescription = headerDescriptionInput ? headerDescriptionInput.value : '';

                    fetch('{{ route("periodic-schedule.bulk-save") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            tahun: headerYear,
                            keterangan: headerDescription,
                            details: details
                        })
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            setDirty(false);
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message || 'Data berhasil tersimpan.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                alert(data.message || 'Data berhasil tersimpan.');
                                location.reload();
                            }
                        } else {
                            showAlert({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                text: data.message || 'Gagal menyimpan data.'
                            });
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        showAlert({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat menyimpan data.'
                        });
                    });
                }

                function confirmLeave(event) {
                    if (!isDirty) {
                        return true;
                    }
                    const message = 'Perubahan belum disimpan. Tetap keluar?';
                    if (event) {
                        event.preventDefault();
                        event.returnValue = message;
                    }
                    return message;
                }

                if (addRowButton) {
                    addRowButton.addEventListener('click', addDetailRow);
                }

                reindexDetailRows();

                if (saveButton) {
                    saveButton.addEventListener('click', saveDetails);
                }

                if (backButton) {
                    backButton.addEventListener('click', async (event) => {
                        if (!isDirty) {
                            history.back();
                            return;
                        }

                        event.preventDefault();

                        const confirmation = typeof Swal !== 'undefined'
                            ? await Swal.fire({
                                title: 'Perubahan Belum Disimpan',
                                text: 'Tetap keluar dari halaman ini?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Keluar',
                                cancelButtonText: 'Batal',
                                reverseButtons: true
                            })
                            : {
                                isConfirmed: confirm('Perubahan belum disimpan. Tetap keluar?')
                            };

                        if (!confirmation.isConfirmed) {
                            return;
                        }

                        window.removeEventListener('beforeunload', confirmLeave);
                        history.back();
                    });
                }

                window.addEventListener('beforeunload', confirmLeave);

                if (detailBody) {
                    detailBody.querySelectorAll('.btn-delete-row').forEach((button) => {
                        button.addEventListener('click', () => handleDeleteRow(button));
                    });
                    detailBody.querySelectorAll('.periodic-select').forEach((select) => {
                        select.addEventListener('change', (event) => {
                            updateRowPeriodic(event.target.closest('tr'));
                            setDirty();
                        });
                    });
                    detailBody.querySelectorAll('select, input').forEach((input) => {
                        input.addEventListener('change', () => setDirty());
                    });
                    detailBody.querySelectorAll('.btn-date-detail').forEach((button) => {
                        button.addEventListener('click', (event) => {
                            const row = event.target.closest('tr');
                            const detailId = event.target.dataset.detail || row?.dataset?.id;
                            if (!detailId) {
                                showAlert({
                                    icon: 'info',
                                    title: 'Informasi',
                                    text: 'Simpan data terlebih dahulu untuk melihat detail realisasi.'
                                });
                                return;
                            }
                            openDateModal(detailId, event.target.dataset.type);
                        });
                    });
                    detailBody.querySelectorAll('.btn-plan-dates').forEach((button) => {
                        button.addEventListener('click', function(event) {
                            openPlanDatesModal(this);
                        });
                    });
                    detailBody.querySelectorAll('tr').forEach((row) => {
                        if (!row.querySelector('td[colspan]')) {
                            attachAutoPlanDateListeners(row);
                        }
                    });
                }
            </script>
        @endpush
    @else
        <div class="card shadow-lg mx-4">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-lg">
                        <div class="nav-wrapper">
                            {{-- button back --}}
                            <button class="btn btn-secondary mb-0" onclick="history.back()"><i class="fas fa-circle-left me-1">
                                </i><span class="font-weight-bold">Kembali</button>
                            {{-- check authorize edi --}}
                            @if ($authorize->edit == '1' && !in_array(($dmenu ?? ''), ['trhrd', 'trreq'], true))
                                {{-- check status active --}}
                                @if ((int) ($list->is_active ?? $list->isactive ?? 1) == 1)
                                    {{-- button save --}}
                                    <button class="btn btn-primary mb-0" style="display: none;" id="{{ $dmenu }}-save"
                                        onclick="event.preventDefault(); document.getElementById('{{ $dmenu }}-form').submit();"><i
                                            class="fas fa-floppy-disk me-1"> </i><span class="font-weight-bold">Simpan</button>
                                    {{-- button edit --}}
                                    <button class="btn btn-warning mb-0" id="{{ $dmenu }}-edit"><i
                                            class="fas fa-edit me-1"> </i><span class="font-weight-bold">Edit</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <form role="form" method="POST" action="{{ URL::to($url_menu . '/' . $idencrypt) }}"
                            enctype="multipart/form-data" id="{{ $dmenu }}-form">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <p class="text-uppercase text-sm">View {{ $title_menu }}</p>
                                <hr class="horizontal dark mt-0">
                                <div class="row">
                                    {{-- retrieve table header --}}
                                    @foreach ($table_header as $header)
                                        @php
                                            $primary = false;
                                            $generateid = false;
                                            foreach ($table_primary as $p) {
                                                $primary == false
                                                    ? ($p->field == $header->field
                                                        ? ($primary = true)
                                                        : ($primary = false))
                                                    : '';
                                                $generateid == false
                                                    ? ($p->generateid == $header->field
                                                        ? ($generateid = true)
                                                        : ($generateid = false))
                                                    : '';
                                            }
                                        @endphp
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                @if ($header->type != 'hidden')
                                                    {{-- display label alias on type field not hidden --}}
                                                    <label for="example-text-input"
                                                        class="form-control-label">{{ $header->alias }}</label>
                                                @endif
                                                {{-- field type char and string --}}
                                                @if ($header->type == 'char' || $header->type == 'string')
                                                    <input
                                                        class="form-control {{ $header->primary == '1' ? ' bg-dark text-light' : '' }} {{ $header->class }}"
                                                        type="text" disabled {{ $primary ? ' key=true' : '' }}
                                                        value="{{ $list ? $list->{$header->field} : old($header->field) }}"
                                                        name="{{ $header->field }}" maxlength="{{ $header->length }}">
                                                    @if ($header->note != '')
                                                        <p class='text-secondary text-xs pt-1 px-1'>
                                                            {{ '*) ' . $header->note }}
                                                        </p>
                                                    @endif
                                                    {{-- field type text --}}
                                                @elseif ($header->type == 'text')
                                                    <textarea class="form-control {{ $header->primary == '1' ? ' bg-dark text-light' : '' }} {{ $header->class }}"
                                                        disabled name="{{ $header->field }}" maxlength="{{ $header->length }}">{{ $list ? $list->{$header->field} : old($header->field) }}</textarea>
                                                    @if ($header->note != '')
                                                        <p class='text-secondary text-xs pt-1 px-1'>
                                                            {{ '*) ' . $header->note }}
                                                        </p>
                                                    @endif
                                                    {{-- field type email --}}
                                                @elseif ($header->type == 'email')
                                                    <input
                                                        class="form-control {{ $header->primary == '1' ? ' bg-dark text-light' : '' }} {{ $header->class }}"
                                                        type="email" disabled {{ $primary ? ' key=true' : '' }}
                                                        value="{{ $list ? $list->{$header->field} : old($header->field) }}"
                                                        name="{{ $header->field }}" maxlength="{{ $header->length }}">
                                                    @if ($header->note != '')
                                                        <p class='text-secondary text-xs pt-1 px-1'>
                                                            {{ '*) ' . $header->note }}
                                                        </p>
                                                    @endif
                                                    {{-- field type number --}}
                                                @elseif ($header->type == 'number')
                                                    <input
                                                        class="form-control {{ $header->primary == '1' ? ' bg-dark text-light' : '' }} {{ $header->class }}"
                                                        type="number" disabled {{ $primary ? ' key=true' : '' }}
                                                        value="{{ $list ? $list->{$header->field} : old($header->field) }}"
                                                        name="{{ $header->field }}" max="{{ $header->length }}">
                                                    @if ($header->note != '')
                                                        <p class='text-secondary text-xs pt-1 px-1'>
                                                            {{ '*) ' . $header->note }}
                                                        </p>
                                                    @endif
                                                    {{-- field type currency --}}
                                                @elseif ($header->type == 'currency')
                                                    <input
                                                        class="form-control {{ $header->primary == '1' ? ' bg-dark text-light' : '' }} {{ $header->class }}"
                                                        type="number" disabled {{ $primary ? ' key=true' : '' }}
                                                        value="{{ $list ? $list->{$header->field} : old($header->field) }}"
                                                        name="{{ $header->field }}" max="{{ $header->length }}">
                                                    @if ($header->note != '')
                                                        <p class='text-secondary text-xs pt-1 px-1'>
                                                            {{ '*) ' . $header->note }}
                                                        </p>
                                                    @endif
                                                    {{-- field type search --}}
                                                @elseif ($header->type == 'search')
                                                    <div class="flex flex-col mb-2 input-group">
                                                        <input name="{{ $header->field }}"
                                                            class="form-control {{ $header->primary == '1' ? ' bg-dark text-light' : '' }}  {{ $header->class }}"
                                                            type="text" disabled {{ $primary ? ' key=true' : '' }}
                                                            value="{{ $list ? $list->{$header->field} : old($header->field) }}">
                                                        <span class="input-group-text bg-primary text-light icon-modal-search"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#searchModal{{ $header->field }}"
                                                            style="border-color:#d2d6da;border-left:3px solid #d2d6da;cursor: pointer;display:none;"><i
                                                                class="fas fa-search"></i></span>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="searchModal{{ $header->field }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="searchModalLabel">
                                                                        List Data
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                @if ($header->query != '')
                                                                    @php
                                                                        $table_result = DB::select($header->query);
                                                                    @endphp
                                                                @endif
                                                                <div class="modal-body">
                                                                    <table class="table display" id="list_{{ $dmenu }}">
                                                                        @if ($table_result)
                                                                            <thead class="thead-light"
                                                                                style="background-color: #00b7bd4f;">
                                                                                <tr>
                                                                                    <th width="20px">Action</th>
                                                                                    @foreach ($table_result as $result)
                                                                                        @php
                                                                                            $sAsArray = array_keys(
                                                                                                (array) $result,
                                                                                            );
                                                                                        @endphp
                                                                                    @endforeach
                                                                                    @foreach ($sAsArray as $modal_h)
                                                                                        <th>{{ $modal_h }}</th>
                                                                                    @endforeach
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($table_result as $modal_d)
                                                                                    <tr>
                                                                                        @foreach ($table_result as $result)
                                                                                            @php
                                                                                                $field = array_keys(
                                                                                                    (array) $result,
                                                                                                );
                                                                                            @endphp
                                                                                        @endforeach
                                                                                        <td width="20px"><span
                                                                                                class="btn badge bg-primary badge-lg"
                                                                                                onclick="select_modal('{{ $modal_d->{$field[0]} }}')"><i
                                                                                                    class="bi bi-check-circle me-1"></i>
                                                                                                Select</span>
                                                                                        </td>
                                                                                        @foreach ($field as $header_field)
                                                                                            @php
                                                                                                $string = $header_field;
                                                                                            @endphp
                                                                                            <td
                                                                                                class="text-sm font-weight-normal">
                                                                                                {{ $modal_d->$string }}
                                                                                            </td>
                                                                                        @endforeach
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        @endif
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if ($header->note != '')
                                                        <p class='text-secondary text-xs pt-1 px-1'>
                                                            {{ '*) ' . $header->note }}
                                                        </p>
                                                    @endif
                                                    <script>
                                                        function select_modal(id, name) {
                                                            $('input[name="{{ $header->field }}"]').val(id);
                                                            $('#searchModal{{ $header->field }}').modal('hide');
                                                        }
                                                    </script>
                                                    {{-- field type image --}}
                                                @elseif ($header->type == 'image')
                                                    @php
                                                        $imageValue = $list ? $list->{$header->field} : null;
                                                        $imagePaths = [];

                                                        if (is_string($imageValue) && trim($imageValue) !== '') {
                                                            $decodedImages = json_decode($imageValue, true);

                                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedImages)) {
                                                                $imagePaths = array_values(
                                                                    array_filter($decodedImages, fn($path) => is_string($path) && trim($path) !== ''),
                                                                );
                                                            } elseif (str_contains($imageValue, ',')) {
                                                                $imagePaths = array_values(
                                                                    array_filter(array_map('trim', explode(',', $imageValue)), fn($path) => $path !== ''),
                                                                );
                                                            } else {
                                                                $imagePaths = [trim($imageValue)];
                                                            }
                                                        }
                                                    @endphp
                                                    @if (!empty($imagePaths))
                                                        <div class="row g-2 mt-1">
                                                            @foreach ($imagePaths as $imageIndex => $imagePath)
                                                                @php
                                                                    $imageSrc = \Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])
                                                                        ? $imagePath
                                                                        : asset('storage/' . ltrim($imagePath, '/'));
                                                                    $imageModalId = 'imageModal' . str_replace('.', '_', $header->field) . $imageIndex;
                                                                @endphp
                                                                <div class="col-6 col-md-4">
                                                                    <img src="{{ $imageSrc }}"
                                                                        alt="{{ $header->alias }} {{ $imageIndex + 1 }}"
                                                                        class="img-fluid rounded shadow-sm"
                                                                        style="width: 100%; max-height: 160px; object-fit: cover; cursor: pointer;"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#{{ $imageModalId }}">
                                                                </div>

                                                                <div class="modal fade" id="{{ $imageModalId }}" tabindex="-1">
                                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ $header->alias }} {{ $imageIndex + 1 }}</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <img src="{{ $imageSrc }}"
                                                                                    alt="{{ $header->alias }} {{ $imageIndex + 1 }}"
                                                                                    class="img-fluid"
                                                                                    style="max-height: 600px;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <p class="text-primary text-xs pt-2">
                                                            <i class="fas fa-info-circle me-1"></i>Klik gambar untuk preview
                                                        </p>
                                                    @else
                                                        <div class="text-center p-5 bg-light rounded">
                                                            <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                                            <p class="text-muted mt-3">Belum ada lampiran gambar</p>
                                                        </div>
                                                    @endif
                                                    <p class='text-primary text-xs pt-3 mb-0'>Maksimal Size :
                                                        <b>{{ $header->length }} KB</b>
                                                    </p>
                                                    @if ($header->note != '')
                                                        <p class='text-primary text-xs pt-1'>
                                                            {{ $header->note }}
                                                        </p>
                                                    @else
                                                        <p class='text-primary text-xs pt-1'>Format Gambar :
                                                            <b>JPG, PNG, GIF</b>
                                                        </p>
                                                    @endif
                                                    <script>
                                                        {{ $header->field }}.onchange = evt => {
                                                            const [file] = {{ $header->field }}.files
                                                            if (file) {
                                                                {{ $header->field . 'preview' }}.src = URL.createObjectURL(file)
                                                            }
                                                        }
                                                        $('#{{ $header->field }}edit').click(function() {
                                                            $('input[name="{{ $header->field }}"]').click();

                                                        });
                                                    </script>
                                                    {{-- field type password --}}
                                                @elseif ($header->type == 'password')
                                                    <div class="flex flex-col mb-2 input-group pass">
                                                        <input class="form-control {{ $header->class }}" disabled
                                                            {{ $primary ? ' key=true type=hidden' : ($header->filter == '1' ? ' type=password' : ' type=hidden') }}
                                                            value="{{ $list ? '' : $header->default }}"
                                                            name="{{ $header->field }}" max="{{ $header->length }}">
                                                        <span class="input-group-text" id="button-addon"
                                                            style="border-color:#d2d6da;"><i class="fas fa-eye showpass"
                                                                style="cursor: pointer;"></i></span>
                                                    </div>
                                                    <p class='text-primary text-xs pt-1'>Default Password :
                                                        <b>{{ $header->default }}</b>
                                                    </p>
                                                    {{-- field type date --}}
                                                @elseif ($header->type == 'date')
                                                    <input
                                                        class="form-control {{ $header->primary == '1' ? ' bg-dark text-light' : '' }} {{ $header->class }}"
                                                        type="date" disabled {{ $primary ? ' key=true' : '' }}
                                                        value="{{ $list ? $list->{$header->field} : old($header->field) }}"
                                                        name="{{ $header->field }}">
                                                    @if ($header->note != '')
                                                        <p class='text-secondary text-xs pt-1 px-1'>
                                                            {{ '*) ' . $header->note }}
                                                        </p>
                                                    @endif
                                                    {{-- field type hidden --}}
                                                @elseif ($header->type == 'hidden')
                                                    <input class="form-control {{ $header->class }}" type="hidden"
                                                        value="{{ $header->default }}" name="{{ $header->field }}"
                                                        max="{{ $header->length }}">
                                                    {{-- field type enum --}}
                                                @elseif ($header->type == 'enum')
                                                    <select
                                                        class="form-select {{ $header->primary == '1' ? ' bg-dark text-light' : '' }} {{ $header->class }}"
                                                        name="{{ $header->field }}" disabled
                                                        {{ $primary || $generateid ? ' key=true' : '' }}>
                                                        <option value=""></option>
                                                        @if ($header->query != '')
                                                            @php
                                                                $data_query = DB::select($header->query);
                                                            @endphp
                                                            @foreach ($data_query as $q)
                                                                <?php $sAsArray = array_values((array) $q); ?>
                                                                <option value="{{ $sAsArray[0] }}"
                                                                    {{ $sAsArray[0] == $list->{$header->field} ? 'selected' : '' }}>
                                                                    {{ $sAsArray[1] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @if ($header->note != '')
                                                        <p class='text-secondary text-xs pt-1 px-1'>
                                                            {{ '*) ' . $header->note }}
                                                        </p>
                                                    @endif
                                                    <script>
                                                        $(this).val('{{ $list->{$header->field} }}')
                                                    </script>
                                                @endif
                                                @error($header->field)
                                                    <p class='text-danger text-xs pt-1'> {{ $message }} </p>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row px-2 py-2">
                                    <div class="col-lg">
                                        <div class="nav-wrapper"><code>Note : <i aria-hidden="true" style=""
                                                    class="fas fa-circle text-dark"></i> Data primary key</code></div>
                                    </div>
                                </div>
                                <hr class="horizontal dark">
                            </div>
                            <div class="card-footer align-items-center pt-0 pb-2">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- check flag js on dmenu --}}
        @if ($jsmenu == '1')
            @if (view()->exists("js.{$dmenu}"))
                @push('addjs')
                    {{-- file js in folder (resources/views/js) --}}
                    @include('js.' . $dmenu);
                @endpush
            @else
                @push('addjs')
                    <script>
                        Swal.fire({
                            title: 'JS Not Found!!',
                            text: 'Please Create File JS',
                            icon: 'error',
                            confirmButtonColor: '#028284'
                        });
                    </script>
                @endpush
            @endif
        @endif
        @push('js')
            <script>
                $(document).ready(function() {
                    //set disable all input form
                    $('#{{ $dmenu }}-form').find('label').addClass('disabled');
                    $('#{{ $dmenu }}-form').find('input').attr('disabled', 'disabled');
                    $('#{{ $dmenu }}-form').find('select').attr('disabled', 'disabled');
                    $('#{{ $dmenu }}-form').find('textarea').attr('disabled', 'disabled');
                    $('#{{ $dmenu }}-form').find('input[key="true"]').parent('.form-group').css('display',
                        '');
                    $('#{{ $dmenu }}-form').find('select[key="true"]').parent('.form-group').css('display',
                        '');
                    $('.icon-modal-search').css('display', 'none');
                    // function enable input form
                    function enable_text() {
                        $('#{{ $dmenu }}-form').find('label').removeClass('disabled');
                        $('#{{ $dmenu }}-form').find('input').removeAttr('disabled');
                        $('#{{ $dmenu }}-form').find('select').removeAttr('disabled');
                        $('#{{ $dmenu }}-form').find('textarea').removeAttr('disabled');
                        $('#{{ $dmenu }}-form').find('input[key="true"]').parent('.form-group').css('display',
                            'none');
                        $('#{{ $dmenu }}-form').find('select[key="true"]').parent('.form-group').css('display',
                            'none');
                        $('.icon-modal-search').css('display', '');
                    }
                    //event button edit
                    $('#{{ $dmenu }}-edit').click(function() {
                        enable_text();
                        $(this).css('display', 'none');
                        $('#{{ $dmenu }}-save').css('display', '');
                    });
                });
            </script>
        @endpush
    @endif
@endsection
