@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
{{-- section content --}}
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => ''])
    <style>
        .status-badge {
            display: inline-flex !important;
            align-items: center !important;
            padding: 4px 10px !important;
            border-radius: 999px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: capitalize !important;
            line-height: 1.2 !important;
        }

        .status-pending {
            background: rgba(59, 130, 246, 0.18) !important;
            color: #1e40af !important;
        }

        .status-review {
            background: rgba(20, 184, 166, 0.18) !important;
            color: #0f766e !important;
        }

        .status-pengadaan {
            background: rgba(245, 158, 11, 0.18) !important;
            color: #b45309 !important;
        }

        .status-pengerjaan {
            background: rgba(20, 184, 166, 0.18) !important;
            color: #0f766e !important;
        }

        .status-approved {
            background: rgba(34, 197, 94, 0.18) !important;
            color: #15803d !important;
        }

        .status-completed {
            background: rgba(99, 102, 241, 0.18) !important;
            color: #4338ca !important;
        }

        .status-verifikasi {
            background: rgba(168, 85, 247, 0.18) !important;
            color: #7c3aed !important;
        }

        .status-revisi {
            background: rgba(251, 146, 60, 0.18) !important;
            color: #c2410c !important;
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.18) !important;
            color: #b91c1c !important;
        }

        .status-cancel {
            background: rgba(148, 163, 184, 0.2) !important;
            color: #475569 !important;
        }
    </style>
    
    {{-- Global Status Badge CSS --}}
    @push('css')
        <style>
            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 10px;
                border-radius: 999px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: capitalize;
            }
            
            .status-pending {
                background: rgba(59, 130, 246, 0.18);
                color: #1e40af;
            }
            
            .status-review {
                background: rgba(20, 184, 166, 0.18);
                color: #0f766e;
            }
            
            .status-pengadaan {
                background: rgba(245, 158, 11, 0.18);
                color: #b45309;
            }
            
            .status-pengerjaan {
                background: rgba(20, 184, 166, 0.18);
                color: #0f766e;
            }
            
            .status-approved {
                background: rgba(34, 197, 94, 0.18);
                color: #15803d;
            }
            
            .status-completed {
                background: rgba(99, 102, 241, 0.18);
                color: #4338ca;
            }
            
            .status-verifikasi {
                background: rgba(168, 85, 247, 0.18);
                color: #7c3aed;
            }
            
            .status-revisi {
                background: rgba(251, 146, 60, 0.18);
                color: #c2410c;
            }
            
            .status-rejected {
                background: rgba(239, 68, 68, 0.18);
                color: #b91c1c;
            }
            
            .status-cancel {
                background: rgba(148, 163, 184, 0.2);
                color: #475569;
            }
        </style>
    @endpush
    
    @if ($dmenu == 'trreq')
        @push('css')
            <link
                rel="stylesheet">
            <style>
                :root {
                    --pu-ink: #0f172a;
                    --pu-mute: #64748b;
                    --pu-teal: #0ea5a5;
                    --pu-amber: #f59e0b;
                    --pu-slate: #e2e8f0;
                    --pu-card: #ffffff;
                    --pu-bg: linear-gradient(135deg, #eff6ff 0%, #fdf4ff 45%, #fef9c3 100%);
                }

                .pu-wrap {
                    font-family: var(--bs-body-font-family);
                    position: relative;
                    z-index: 3;
                    padding-top: 12px;
                }

                .pu-hero {
                    background: var(--pu-bg);
                    border-radius: 18px;
                    padding: 24px 28px;
                    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
                    margin-bottom: 20px;
                    position: relative;
                    overflow: hidden;
                    z-index: 3;
                }

                .pu-hero::after {
                    content: "";
                    position: absolute;
                    inset: -20% -10% auto auto;
                    width: 220px;
                    height: 220px;
                    background: radial-gradient(circle, rgba(14, 165, 165, 0.25), transparent 65%);
                    border-radius: 50%;
                }

                .pu-hero h2 {
                    font-weight: 700;
                    color: var(--pu-ink);
                }

                .pu-hero p {
                    color: var(--pu-mute);
                    margin-bottom: 0;
                }

                .pu-table-card {
                    border-radius: 18px;
                    border: 1px solid var(--pu-slate);
                    overflow: hidden;
                    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
                }

                .pu-table tbody tr {
                    border-bottom: 1px solid #eef2f7;
                }

                .pu-table tbody tr:hover {
                    background: #f8fafc;
                }

                .pu-actions-inline .btn {
                    border-radius: 10px;
                }

                .pu-table .btn-group {
                    gap: 0;
                }

                .pu-table .btn-group .btn {
                    border-radius: 0;
                }

                .pu-table .btn-group .btn:first-child {
                    border-top-left-radius: 10px;
                    border-bottom-left-radius: 10px;
                }

                .pu-table .btn-group .btn:last-of-type {
                    border-top-right-radius: 10px;
                    border-bottom-right-radius: 10px;
                }

                .pu-empty {
                    padding: 32px;
                    text-align: center;
                    color: var(--pu-mute);
                }
            </style>
        @endpush
        @php
            // Get data
            $items = $table_detail_d ?? collect();
            
            // Jika kosong, query langsung dari database
            if ($items->isEmpty()) {
                $items = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                    ->leftJoin('ms_area', 'pl_non_periodic.area_id', '=', 'ms_area.id')
                    ->select('pl_non_periodic.*', 'ms_area.nama_area as area_name')
                    ->orderBy('pl_non_periodic.created_at', 'desc')
                    ->get();
            }
            
            $displayItems = $items;
            $statusField = 'request_status';
            if ($displayItems->first() && isset($displayItems->first()->status)) {
                $statusField = 'status';
            }

            $hrdUserIds = \Illuminate\Support\Facades\DB::table('users')
                ->where(function ($q) {
                    $q->whereRaw("FIND_IN_SET('hrdxxx', REPLACE(LOWER(idroles), ' ', ''))")
                        ->orWhereRaw("FIND_IN_SET('hrd', REPLACE(LOWER(idroles), ' ', ''))");
                })
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $hrdUsernames = \Illuminate\Support\Facades\DB::table('users')
                ->where(function ($q) {
                    $q->whereRaw("FIND_IN_SET('hrdxxx', REPLACE(LOWER(idroles), ' ', ''))")
                        ->orWhereRaw("FIND_IN_SET('hrd', REPLACE(LOWER(idroles), ' ', ''))");
                })
                ->pluck('username')
                ->map(fn ($username) => strtolower((string) $username))
                ->all();

            $isHrdSubmission = function ($item) use ($hrdUserIds, $hrdUsernames) {
                $uid = isset($item->user_id) ? (string) $item->user_id : '';
                $uidInt = is_numeric($uid) ? (int) $uid : null;
                $createdBy = strtolower((string) ($item->user_create ?? ''));

                if ($uidInt !== null && in_array($uidInt, $hrdUserIds, true)) {
                    return true;
                }

                if ($uid !== '' && !is_numeric($uid) && in_array(strtolower($uid), $hrdUsernames, true)) {
                    return true;
                }

                return $createdBy !== '' && in_array($createdBy, $hrdUsernames, true);
            };

            $currentUserId = $user_login->id ?? null;
            $sessionUsername = strtolower(trim((string) (session('username') ?? '')));
            $modelUsername = strtolower(trim((string) ($user_login->username ?? '')));
            $currentUsername = $sessionUsername !== '' && !is_numeric($sessionUsername)
                ? $sessionUsername
                : $modelUsername;

            $isCurrentViewerHrd = ($currentUserId && in_array((int) $currentUserId, $hrdUserIds, true))
                || ($currentUsername !== '' && in_array($currentUsername, $hrdUsernames, true));

            $isOwnedByCurrentUser = function ($item) use ($currentUserId, $currentUsername) {
                $uid = isset($item->user_id) ? (string) $item->user_id : '';
                $createdBy = strtolower((string) ($item->user_create ?? ''));

                $byUserId = $currentUserId && $uid !== '' && is_numeric($uid) && (int) $uid === (int) $currentUserId;
                $byLegacyUserId = $currentUsername !== '' && $uid !== '' && !is_numeric($uid) && strtolower($uid) === $currentUsername;
                $byUserCreate = $currentUsername !== '' && $createdBy === $currentUsername;

                return $byUserId || $byLegacyUserId || $byUserCreate;
            };
            
            // Filter pending items - status pending atau review yang belum ada head_approval_date
            $pendingItems = $displayItems->filter(function ($item) use ($statusField, $isHrdSubmission) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                return ($statusValue === 'pending' || $statusValue === 'review')
                    && empty($item->head_approval_date)
                    && !$isHrdSubmission($item);
            });
            
            // Approved count
            $approvedCount = $displayItems->filter(function ($item) use ($statusField) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                return !empty($item->head_approval_date) && in_array($statusValue, ['review', 'approved', 'pengadaan', 'pengerjaan', 'completed']);
            })->count();
            
            // Rejected count
            $rejectedCount = $displayItems->filter(function ($item) use ($statusField) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                return in_array($statusValue, ['rejected', 'reject']);
            })->count();
            
            // Completed count
            $completedCount = $displayItems->where($statusField, 'completed')->count();
            
            // History items - semua yang sudah diproses head atau ditolak
            $historyItems = $displayItems->filter(function ($item) use ($statusField) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                return !empty($item->head_approval_date) || in_array($statusValue, ['rejected', 'reject', 'cancel', 'cancelled', 'canceled']);
            });

            // Verifikasi HRD di menu Pengajuan PU (mirip user)
            $verifikasiItems = collect();
            if ($isCurrentViewerHrd) {
                $verifikasiItems = $displayItems->filter(function ($item) use ($statusField, $isOwnedByCurrentUser) {
                    $statusValue = strtolower($item->{$statusField} ?? '');
                    return $statusValue === 'verifikasi' && $isOwnedByCurrentUser($item);
                })->values();
            }
        @endphp
        <div class="container-fluid py-4 pu-wrap">
            <div class="pu-hero">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-1">Daftar Pengajuan Pekerjaan PU</h2>
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Pending Approval</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $pendingItems->count() }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                        <i class="fas fa-clock text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Approved</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $approvedCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                        <i class="fas fa-check text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Rejected</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $rejectedCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                        <i class="fas fa-times text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Completed</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $completedCount }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                        <i class="fas fa-check-circle text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Head Approval - Pending Items --}}
            @if($pendingItems->count() > 0)
            <div class="card pu-table-card mb-4">
                <div class="card-header border-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h5 class="mb-1">Daftar Pengajuan Baru</h5>
                        <span class="text-muted small">Menunggu keputusan Head</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table pu-table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-xs text-center">Aksi</th>
                                <th class="text-uppercase text-xs">No</th>
                                <th class="text-uppercase text-xs">Tanggal Pengajuan</th>
                                <th class="text-uppercase text-xs">Area</th>
                                <th class="text-uppercase text-xs">Deskripsi</th>
                                <th class="text-uppercase text-xs">Status</th>
                                <th class="text-uppercase text-xs">Catatan Head</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingItems as $detail)
                                @php
                                    $primary = '';
                                    if (isset($table_primary_d) && count($table_primary_d) > 0) {
                                        foreach ($table_primary_d as $p) {
                                            $primary = $primary == '' ? $detail->{$p->field} : $primary . ':' . $detail->{$p->field};
                                        }
                                    }
                                    if ($primary == '' && isset($detail->id)) {
                                        $primary = $detail->id;
                                    }
                                    $rawStatus = $detail->request_status ?? $detail->status ?? '';
                                    $status = strtolower($rawStatus);
                                    if (in_array($status, ['approved'], true)) {
                                        $status = 'review';
                                    } elseif ($status === 'reject') {
                                        $status = 'rejected';
                                    } elseif (in_array($status, ['canceled', 'cancelled'], true)) {
                                        $status = 'cancel';
                                    }
                                    $badgeClass = 'status-badge status-' . ($status ?: 'pending');
                                    $dateValue = $detail->created_at ?? $detail->tanggal ?? $detail->date ?? null;
                                    $displayDate = $dateValue
                                        ? \Illuminate\Support\Carbon::parse($dateValue)->format('d-m-Y')
                                        : '-';
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm mb-0 px-3" type="button"
                                                title="View Data"
                                                onclick="window.location='{{ url($url_menu . '/show' . '/' . encrypt($primary)) }}'">
                                                <i class="fas fa-eye"></i><span class="font-weight-bold"> View</span>
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-primary mb-0 px-2 dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li class="px-2 mb-2">
                                                    <button class="dropdown-item d-flex align-items-center gap-2 text-white rounded-2 mb-1"
                                                        style="background-color:#ff6b47;border-color:#ff6b47;"
                                                        type="button"
                                                        data-bs-toggle="modal" data-bs-target="#headActionModal"
                                                        data-action="Approve" data-id="{{ $primary }}">
                                                        <i class="fas fa-check me-2 text-white"></i>Setujui
                                                    </button>
                                                </li>
                                                <li class="px-2">
                                                    <button class="dropdown-item d-flex align-items-center gap-2 text-white rounded-2"
                                                        style="background-color:#ff4d6d;border-color:#ff4d6d;"
                                                        type="button"
                                                        data-bs-toggle="modal" data-bs-target="#headActionModal"
                                                        data-action="Reject" data-id="{{ $primary }}">
                                                        <i class="fas fa-times me-2 text-white"></i>Tolak
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-sm">{{ $loop->iteration }}</td>
                                    <td class="text-sm">{{ $displayDate }}</td>
                                    <td class="text-sm">{{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->job_description ?? $detail->description ?? '-' }}</td>
                                    <td class="text-sm">
                                        <span class="{{ $badgeClass }}">{{ $status ?: 'pending' }}</span>
                                    </td>
                                    <td class="text-sm">{{ $detail->head_note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="pu-empty">Belum ada pengajuan baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if($verifikasiItems->count() > 0)
            <div class="card pu-table-card mb-4" style="border: 2px solid #f59e0b; box-shadow: 0 4px 20px rgba(245, 158, 11, 0.15);">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div>
                            <h5 class="mb-1 text-dark">
                                <i class="fas fa-clipboard-check text-warning me-2"></i>
                                Verifikasi Pekerjaan
                            </h5>
                            <p class="text-sm mb-0 text-muted">Pekerjaan yang diselesaikan petugas dan memerlukan verifikasi Anda</p>
                        </div>
                        <span class="badge bg-gradient-warning text-white px-3 py-2">{{ $verifikasiItems->count() }} Task</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table pu-table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-xs text-center">Aksi</th>
                                <th class="text-uppercase text-xs">No</th>
                                <th class="text-uppercase text-xs">Tanggal Pengajuan</th>
                                <th class="text-uppercase text-xs">Area</th>
                                <th class="text-uppercase text-xs">Deskripsi</th>
                                <th class="text-uppercase text-xs text-center">Dokumentasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($verifikasiItems as $detail)
                                @php
                                    $dateValue = $detail->created_at ?? $detail->tanggal ?? $detail->date ?? null;
                                    $displayDate = $dateValue
                                        ? \Illuminate\Support\Carbon::parse($dateValue)->format('d-m-Y')
                                        : '-';
                                    $docCount = \Illuminate\Support\Facades\DB::table('pl_documentation')
                                        ->where('non_periodic_id', $detail->id)
                                        ->where('is_active', '1')
                                        ->count();
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button class="btn btn-success btn-sm mb-0 px-2" type="button"
                                                data-bs-toggle="modal" data-bs-target="#trreqVerifyModal"
                                                data-action="approve"
                                                data-non-periodic-id="{{ $detail->id }}"
                                                title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm mb-0 px-2" type="button"
                                                data-bs-toggle="modal" data-bs-target="#trreqVerifyModal"
                                                data-action="reject"
                                                data-non-periodic-id="{{ $detail->id }}"
                                                title="Minta Revisi">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-sm">{{ $loop->iteration }}</td>
                                    <td class="text-sm">{{ $displayDate }}</td>
                                    <td class="text-sm">{{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->job_description ?? $detail->description ?? '-' }}</td>
                                    <td class="text-sm text-center">
                                        @if ($docCount > 0)
                                            <span class="badge bg-info">{{ $docCount }} Lampiran</span>
                                        @else
                                            <span class="badge bg-secondary">Belum ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <h5 class="mb-1">Riwayat Pengajuan</h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">Total rejected: {{ $rejectedCount }}</span>
                            <select id="head-history-filter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Semua</option>
                                <option value="pending">Pending</option>
                                <option value="review">Review</option>
                                <option value="pengadaan">Pengadaan</option>
                                <option value="pengerjaan">Pengerjaan</option>
                                <option value="completed">Completed</option>
                                <option value="revisi">Revisi</option>
                                <option value="rejected">Rejected</option>
                                <option value="cancel">Cancel</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table display" id="list_riwayat_pengajuan">
                        <thead class="thead-light" style="background-color: #00b7bd4f;">
                            <tr>
                                <th>No</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Area</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Catatan Head</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($historyItems as $detail)
                                @php
                                    $rawStatus = $detail->request_status ?? $detail->status ?? '';
                                    $status = strtolower($rawStatus);
                                    if (in_array($status, ['approved'], true)) {
                                        $status = 'review';
                                    } elseif ($status === 'reject') {
                                        $status = 'rejected';
                                    } elseif (in_array($status, ['canceled', 'cancelled'], true)) {
                                        $status = 'cancel';
                                    }
                                    $badgeClass = 'status-badge status-' . ($status ?: 'pending');
                                    $dateValue = $detail->created_at ?? $detail->tanggal ?? $detail->date ?? null;
                                    $displayDate = $dateValue
                                        ? \Illuminate\Support\Carbon::parse($dateValue)->format('d-m-Y')
                                        : '-';
                                @endphp
                                <tr data-status="{{ $status }}">
                                    <td class="text-sm">{{ $loop->iteration }}</td>
                                    <td class="text-sm">{{ $displayDate }}</td>
                                    <td class="text-sm">{{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->job_description ?? $detail->description ?? '-' }}</td>
                                    <td class="text-sm">
                                        <span class="{{ $badgeClass }}">{{ $status ?: 'pending' }}</span>
                                    </td>
                                    <td class="text-sm">{{ $detail->head_note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="trreqVerifyModal" tabindex="-1" aria-labelledby="trreqVerifyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('work-schedule.verify') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="trreqVerifyModalLabel">Verifikasi Pekerjaan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="non_periodic_id" id="trreq-verify-non-periodic-id" value="">
                            <input type="hidden" name="decision" id="trreq-verify-decision" value="">
                            <div class="mb-3">
                                <label class="form-label" id="trreq-verify-notes-label">Catatan (opsional)</label>
                                <textarea class="form-control" name="notes" id="trreq-verify-notes" rows="3" placeholder="Tulis catatan jika diperlukan"></textarea>
                            </div>
                            <div class="mb-3" id="trreq-verify-attachment-wrapper" style="display: none;">
                                <label class="form-label" for="trreq-verify-attachments">Lampiran Revisi (opsional)</label>
                                <input class="form-control" type="file" name="revision_attachments[]" id="trreq-verify-attachments" accept=".jpg,.jpeg,.png,.webp,.jfif,.heic,.heif,.pdf" multiple>
                                <small class="text-muted">Format: JPG, PNG, WEBP, HEIC, PDF (maks 10MB/file)</small>
                            </div>
                            <div class="alert alert-info mb-0">
                                <small>
                                    <strong>Setuju:</strong> Pekerjaan akan ditandai selesai.<br>
                                    <strong>Revisi:</strong> Pekerjaan akan dikembalikan ke petugas untuk perbaikan.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="trreqVerifySubmit">Setujui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="headActionModal" tabindex="-1" aria-labelledby="headActionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ url('/pengajuan-head/update') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="headActionLabel">Approval Head</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="head-request-id" value="">
                            <input type="hidden" name="request_status" value="review">
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" name="head_note" rows="3" placeholder="Tulis catatan"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="headActionSubmit">Approve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @push('js')
            <script>
                const headActionModal = document.getElementById('headActionModal');
                if (headActionModal) {
                    headActionModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const action = button.getAttribute('data-action') || 'Approve';
                        const title = action === 'Reject' ? 'Catatan Penolakan' : 'Catatan Persetujuan';
                        const submit = document.getElementById('headActionSubmit');
                        const label = document.getElementById('headActionLabel');
                        const inputId = document.getElementById('head-request-id');
                        const inputStatus = document.getElementById('head-request-status');

                        if (label) {
                            label.textContent = title;
                        }
                        if (submit) {
                            submit.textContent = action === 'Reject' ? 'Tolak' : 'Setujui';
                        }
                        if (inputId) {
                            inputId.value = button.getAttribute('data-id') || '';
                        }
                        if (inputStatus) {
                            inputStatus.value = action === 'Reject' ? 'rejected' : 'review';
                        }
                    });
                }

                const headHistoryFilter = document.getElementById('head-history-filter');
                if (headHistoryFilter) {
                    headHistoryFilter.addEventListener('change', function() {
                        const value = this.value.toLowerCase();
                        document.querySelectorAll('table tbody tr[data-status]').forEach(function(row) {
                            const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
                            if (!row.closest('.pu-table-card')) {
                                return;
                            }
                            row.style.display = value === '' || rowStatus === value ? '' : 'none';
                        });
                    });
                }

                const trreqVerifyModal = document.getElementById('trreqVerifyModal');
                if (trreqVerifyModal) {
                    trreqVerifyModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const action = button.getAttribute('data-action') || 'approve';
                        const nonPeriodicId = button.getAttribute('data-non-periodic-id') || '';

                        const modalTitle = document.getElementById('trreqVerifyModalLabel');
                        const submitBtn = document.getElementById('trreqVerifySubmit');
                        const decisionInput = document.getElementById('trreq-verify-decision');
                        const nonPeriodicIdInput = document.getElementById('trreq-verify-non-periodic-id');
                        const notesInput = document.getElementById('trreq-verify-notes');
                        const notesLabel = document.getElementById('trreq-verify-notes-label');
                        const revisionAttachmentWrapper = document.getElementById('trreq-verify-attachment-wrapper');
                        const revisionAttachmentInput = document.getElementById('trreq-verify-attachments');

                        if (action === 'approve') {
                            modalTitle.textContent = 'Setujui Pekerjaan';
                            submitBtn.textContent = 'Setujui';
                            submitBtn.className = 'btn btn-success';
                            decisionInput.value = 'approve';
                            if (notesLabel) notesLabel.textContent = 'Catatan (opsional)';
                            if (notesInput) notesInput.required = false;
                            if (revisionAttachmentWrapper) revisionAttachmentWrapper.style.display = 'none';
                        } else {
                            modalTitle.textContent = 'Minta Revisi';
                            submitBtn.textContent = 'Minta Revisi';
                            submitBtn.className = 'btn btn-warning';
                            decisionInput.value = 'reject';
                            if (notesLabel) notesLabel.textContent = 'Catatan (wajib diisi)';
                            if (notesInput) notesInput.required = true;
                            if (revisionAttachmentWrapper) revisionAttachmentWrapper.style.display = '';
                        }

                        nonPeriodicIdInput.value = nonPeriodicId;
                        if (notesInput) notesInput.value = '';
                        if (revisionAttachmentInput) revisionAttachmentInput.value = '';
                    });
                }
            </script>
        @endpush
    @elseif ($dmenu == 'trhrd')
        @push('css')
            <link
                rel="stylesheet">
            <style>
                :root {
                    --pu-ink: #0f172a;
                    --pu-mute: #64748b;
                    --pu-teal: #0ea5a5;
                    --pu-amber: #f59e0b;
                    --pu-slate: #e2e8f0;
                    --pu-card: #ffffff;
                    --pu-bg: linear-gradient(135deg, #f1f5f9 0%, #ecfeff 45%, #fef9c3 100%);
                }

                .pu-wrap {
                    font-family: var(--bs-body-font-family);
                    position: relative;
                    z-index: 3;
                    padding-top: 12px;
                }

                .pu-hero {
                    background: var(--pu-bg);
                    border-radius: 18px;
                    padding: 24px 28px;
                    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
                    margin-bottom: 20px;
                    position: relative;
                    overflow: hidden;
                    z-index: 3;
                }

                .pu-hero::after {
                    content: "";
                    position: absolute;
                    inset: -20% -10% auto auto;
                    width: 220px;
                    height: 220px;
                    background: radial-gradient(circle, rgba(14, 165, 165, 0.25), transparent 65%);
                    border-radius: 50%;
                }

                .pu-hero h2 {
                    font-weight: 700;
                    color: var(--pu-ink);
                }

                .pu-hero p {
                    color: var(--pu-mute);
                    margin-bottom: 0;
                }

                .pu-table-card {
                    border-radius: 18px;
                    border: 1px solid var(--pu-slate);
                    overflow: hidden;
                    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
                }

                .pu-table tbody tr {
                    border-bottom: 1px solid #eef2f7;
                }

                .pu-table tbody tr:hover {
                    background: #f8fafc;
                }

                .pu-actions-inline .btn {
                    border-radius: 10px;
                }

                .kpi-card {
                    background: var(--pu-card);
                    border-radius: 18px;
                    padding: 18px 20px;
                    box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
                    border: 1px solid rgba(226, 232, 240, 0.8);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 16px;
                    height: 100%;
                }

                .kpi-row {
                    margin-top: 12px;
                    position: relative;
                    z-index: 3;
                }

                .kpi-label {
                    color: #94a3b8;
                    font-size: 0.7rem;
                    letter-spacing: 0.08em;
                    text-transform: uppercase;
                    margin-bottom: 4px;
                }

                .kpi-value {
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: var(--pu-ink);
                    margin: 0;
                }

                .kpi-desc {
                    color: var(--pu-mute);
                    font-size: 0.85rem;
                    margin: 4px 0 0;
                }

                .kpi-icon {
                    width: 54px;
                    height: 54px;
                    border-radius: 50%;
                    display: grid;
                    place-items: center;
                    color: #fff;
                    font-size: 1.1rem;
                    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15);
                }

                .kpi-icon.teal {
                    background: linear-gradient(135deg, #22c55e, #16a34a);
                }

                .kpi-icon.amber {
                    background: linear-gradient(135deg, #f59e0b, #f97316);
                }

                .kpi-icon.blue {
                    background: linear-gradient(135deg, #38bdf8, #2563eb);
                }

                .kpi-icon.red {
                    background: linear-gradient(135deg, #fb7185, #ef4444);
                }

                .pu-empty {
                    padding: 32px;
                    text-align: center;
                    color: var(--pu-mute);
                }
            </style>
        @endpush
        @php
            // Get real data untuk HRD approval
            $items = $table_detail_d ?? collect();
            
            // Jika kosong, query langsung dari database
            if ($items->isEmpty()) {
                $items = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                    ->leftJoin('ms_area', 'pl_non_periodic.area_id', '=', 'ms_area.id')
                    ->leftJoin('users as worker', 'pl_non_periodic.worker_id', '=', 'worker.id')
                    ->select(
                        'pl_non_periodic.*',
                        'ms_area.nama_area as area_name',
                        \Illuminate\Support\Facades\DB::raw("CONCAT(worker.firstname, ' ', worker.lastname) as worker_name")
                    )
                    ->orderBy('pl_non_periodic.created_at', 'desc')
                    ->get();
            }

            // Pastikan area_name selalu tersedia untuk tampilan tabel
            $areaMap = \Illuminate\Support\Facades\DB::table('ms_area')
                ->pluck('nama_area', 'id');
            
            // Query petugas untuk dropdown
            $workers = \Illuminate\Support\Facades\DB::table('users')
                ->whereRaw("LOWER(REPLACE(idroles, ' ', '')) REGEXP '(^|,)ptg'")
                ->where('isactive', '1')
                ->orderBy('firstname')
                ->get();
            
            $displayItems = $items->map(function ($item) use ($areaMap) {
                if (empty($item->area_name) && !empty($item->area_id)) {
                    $item->area_name = $areaMap->get((int) $item->area_id)
                        ?? $areaMap->get((string) $item->area_id);
                }

                return $item;
            });

            // Step 1: approval items dari head (status review dan belum di-approval HRD)
            $approvalItems = $displayItems->filter(function ($item) {
                $statusValue = strtolower($item->request_status ?? '');

                return in_array($statusValue, ['review', 'pending', 'approved']) && !empty($item->head_approval_date) && empty($item->hrd_approval_date);
            });

            // Step 2: in-progress items setelah HRD approve
            $pendingItems = $displayItems->filter(function ($item) {
                $statusValue = strtolower($item->request_status ?? '');
                return !empty($item->hrd_approval_date) && in_array($statusValue, ['review', 'pengadaan', 'pengerjaan']);
            });

            // History items - status yang sudah selesai atau ditolak
            $historyItems = $displayItems->filter(function ($item) {
                $statusValue = strtolower($item->request_status ?? '');
                return in_array($statusValue, ['completed', 'rejected', 'reject', 'cancel', 'cancelled', 'canceled']);
            });
        @endphp
        <div class="container-fluid py-4 pu-wrap">
            <div class="pu-hero">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-1">Daftar Pengajuan HRD</h2>
                    </div>
                </div>
            </div>


            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h5 class="mb-1">Menunggu Persetujuan</h5>
                        <span class="text-muted small">Approval HRD</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table display" id="list_hrd_approval">
                        <thead class="thead-light" style="background-color: #00b7bd4f;">
                            <tr>
                                <th>Aksi</th>
                                <th>No</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Area</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Catatan HRD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($approvalItems as $detail)
                                @php
                                    $primary = '';
                                    if (isset($table_primary_d) && count($table_primary_d) > 0) {
                                        foreach ($table_primary_d as $p) {
                                            $primary = $primary == '' ? $detail->{$p->field} : $primary . ':' . $detail->{$p->field};
                                        }
                                    }
                                    if ($primary == '' && isset($detail->id)) {
                                        $primary = $detail->id;
                                    }
                                    $rawStatus = $detail->request_status ?? 'review';
                                    $status = strtolower($rawStatus);
                                    if (in_array($status, ['approved'], true)) {
                                        $status = 'review';
                                    } elseif ($status === 'reject') {
                                        $status = 'rejected';
                                    } elseif (in_array($status, ['canceled', 'cancelled'], true)) {
                                        $status = 'cancel';
                                    }
                                    $statusText = $status === 'review' ? 'approved' : $status;
                                    $badgeClass = 'status-badge status-' . ($statusText ?: 'approved');
                                    $dateValue = $detail->created_at ?? $detail->tanggal ?? $detail->date ?? null;
                                    $displayDate = $dateValue
                                        ? \Illuminate\Support\Carbon::parse($dateValue)->format('d-m-Y')
                                        : '-';
                                    $displayNote = $detail->hrd_note ?? '-';
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Aksi approval HRD">
                                            <button class="btn btn-info btn-sm mb-0 px-3 text-white" type="button"
                                                title="Lihat Detail"
                                                onclick="window.location='{{ url($url_menu . '/show' . '/' . encrypt($primary)) }}'">
                                                <i class="fas fa-eye"></i><span class="font-weight-bold"> Detail</span>
                                            </button>
                                            <button class="btn btn-primary btn-sm mb-0 px-3" type="button"
                                                data-bs-toggle="modal" data-bs-target="#hrdApprovalModal"
                                                data-id="{{ $primary }}"
                                                data-status="{{ $rawStatus }}"
                                                data-work-type="{{ $detail->work_type ?? '' }}"
                                                data-vendor-name="{{ $detail->vendor_name ?? '' }}"
                                                data-worker-id="{{ $detail->worker_id ?? '' }}"
                                                data-note="{{ $detail->hrd_note ?? '' }}">
                                                <i class="fas fa-check"></i><span class="font-weight-bold"> Proses</span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-sm">{{ $loop->iteration }}</td>
                                    <td class="text-sm">{{ $displayDate }}</td>
                                    <td class="text-sm">{{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->job_description ?? $detail->description ?? '-' }}</td>
                                    <td class="text-sm">
                                        <span class="{{ $badgeClass }}">{{ $statusText ?: 'approved' }}</span>
                                    </td>
                                    <td class="text-sm">{{ $displayNote }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan menunggu approval.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h5 class="mb-1">Daftar Pengajuan</h5>
                        <span class="text-muted small">Menunggu penyelesaian</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table display" id="list_hrd_pending">
                        <thead class="thead-light" style="background-color: #00b7bd4f;">
                            <tr>
                                <th>Aksi</th>
                                <th>No</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Area</th>
                                <th>Deskripsi</th>
                                <th>Pekerja</th>
                                <th>Status</th>
                                <th>Catatan HRD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingItems as $detail)
                                @php
                                    $primary = '';
                                    if (isset($table_primary_d) && count($table_primary_d) > 0) {
                                        foreach ($table_primary_d as $p) {
                                            $primary = $primary == '' ? $detail->{$p->field} : $primary . ':' . $detail->{$p->field};
                                        }
                                    }
                                    if ($primary == '' && isset($detail->id)) {
                                        $primary = $detail->id;
                                    }
                                    $rawStatus = $detail->request_status ?? '';
                                    $status = strtolower($rawStatus);
                                    if (in_array($status, ['approved'], true)) {
                                        $status = 'review';
                                    } elseif ($status === 'reject') {
                                        $status = 'rejected';
                                    } elseif (in_array($status, ['canceled', 'cancelled'], true)) {
                                        $status = 'cancel';
                                    }
                                    $badgeClass = 'status-badge status-' . ($status ?: 'pending');
                                    $dateValue = $detail->created_at ?? $detail->tanggal ?? $detail->date ?? null;
                                    $displayDate = $dateValue
                                        ? \Illuminate\Support\Carbon::parse($dateValue)->format('d-m-Y')
                                        : '-';
                                    $displayNote = $detail->hrd_note ?? '-';
                                @endphp 
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm mb-0 px-3" type="button"
                                                title="View Data"
                                                onclick="window.location='{{ url($url_menu . '/show' . '/' . encrypt($primary)) }}'">
                                                <i class="fas fa-eye"></i><span class="font-weight-bold"> View</span>
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-primary mb-0 px-2 dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li class="px-2 mb-2">
                                                    <button class="dropdown-item d-flex align-items-center gap-2 text-white rounded-2"
                                                        style="background-color:#ff6b47;border-color:#ff6b47;"
                                                        type="button"
                                                        data-bs-toggle="modal" data-bs-target="#hrdActionModal"
                                                        data-id="{{ $primary }}"
                                                        data-status="{{ $rawStatus }}"
                                                        data-note="{{ $detail->hrd_note ?? '' }}"
                                                        data-work-type="{{ $detail->work_type ?? '' }}"
                                                        data-vendor-name="{{ $detail->vendor_name ?? '' }}"
                                                        data-worker-id="{{ $detail->worker_id ?? '' }}"
                                                        >
                                                        <i class="fas fa-pen me-2 text-white"></i>Update
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-sm">{{ $loop->iteration }}</td>
                                    <td class="text-sm">{{ $displayDate }}</td>
                                    <td class="text-sm">{{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->job_description ?? $detail->description ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->worker_name ?? '-' }}</td>
                                    <td class="text-sm">
                                        <span class="{{ $badgeClass }}">{{ $status ?: 'pending' }}</span>
                                    </td>
                                    <td class="text-sm">{{ $displayNote }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Belum ada pengajuan berjalan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <h5 class="mb-1">Riwayat Pengajuan</h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">Total selesai: {{ $historyItems->count() }}</span>
                            <select id="hrd-history-filter" class="form-select form-select-sm" style="width: 180px;">
                                <option value="">Semua</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                                <option value="cancel">Cancel</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table display" id="list_hrd_history">
                        <thead class="thead-light" style="background-color: #00b7bd4f;">
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <th>Area</th>
                                <th>Deskripsi</th>
                                <th>Pekerja</th>
                                <th>Waktu Pengadaan</th>
                                <th>Waktu Pengerjaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($historyItems as $detail)
                                @php
                                    $rawStatus = $detail->request_status ?? '';
                                    $status = strtolower($rawStatus);
                                    if (in_array($status, ['pending', 'approved'], true)) {
                                        $status = 'review';
                                    } elseif ($status === 'reject') {
                                        $status = 'rejected';
                                    } elseif (in_array($status, ['canceled', 'cancelled'], true)) {
                                        $status = 'cancel';
                                    }
                                    $dateValue = $detail->created_at ?? $detail->tanggal ?? $detail->date ?? null;
                                    $displayDate = $dateValue
                                        ? \Illuminate\Support\Carbon::parse($dateValue)->format('d-m-Y')
                                        : '-';
                                    
                                    // Format Waktu Pengadaan sebagai range
                                    if ($detail->pengadaan_started_at) {
                                        $startPengadaan = \Illuminate\Support\Carbon::parse($detail->pengadaan_started_at)->format('d-m-Y');
                                        if ($detail->pengadaan_ended_at) {
                                            $endPengadaan = \Illuminate\Support\Carbon::parse($detail->pengadaan_ended_at)->format('d-m-Y');
                                            $displayPengadaan = $startPengadaan . ' s/d ' . $endPengadaan;
                                        } else {
                                            $displayPengadaan = $startPengadaan . ' s/d -';
                                        }
                                    } else {
                                        $displayPengadaan = '-';
                                    }
                                    
                                    // Format Waktu Pengerjaan sebagai range
                                    if ($detail->pengerjaan_started_at) {
                                        $startPengerjaan = \Illuminate\Support\Carbon::parse($detail->pengerjaan_started_at)->format('d-m-Y');
                                        if ($detail->pengerjaan_ended_at) {
                                            $endPengerjaan = \Illuminate\Support\Carbon::parse($detail->pengerjaan_ended_at)->format('d-m-Y');
                                            $displayPengerjaan = $startPengerjaan . ' s/d ' . $endPengerjaan;
                                        } else {
                                            $displayPengerjaan = $startPengerjaan . ' s/d -';
                                        }
                                    } else {
                                        $displayPengerjaan = '-';
                                    }
                                @endphp
                                <tr data-status="{{ $status }}">
                                    <td class="text-sm">{{ $displayDate }}</td>
                                    <td class="text-sm">{{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->job_description ?? $detail->description ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->worker_name ?? '-' }}</td>
                                    <td class="text-sm">{{ $displayPengadaan }}</td>
                                    <td class="text-sm">{{ $displayPengerjaan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="hrdApprovalModal" tabindex="-1" aria-labelledby="hrdApprovalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ url('/pengajuan-hrd/update') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="hrdApprovalLabel">Approval HRD</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="hrd-approval-id">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="request_status" id="hrd-approval-status" required onchange="toggleApprovalWorkerVendorFields()">
                                    <option value="">- Pilih Status -</option>
                                    <option value="pengadaan">Pengadaan</option>
                                    <option value="pengerjaan">Pengerjaan</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </div>
                            <div id="pengerjaan-approval-fields-group" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Pelaksana</label>
                                    <select class="form-select" name="work_type" id="hrd-approval-work-type" onchange="toggleApprovalWorkerVendor()">
                                        <option value="">- Pilih Pelaksana -</option>
                                        <option value="internal">Internal</option>
                                        <option value="vendor">Vendor</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="hrd-approval-worker-row" style="display: none;">
                                    <label class="form-label">Petugas</label>
                                    <select class="form-select" name="worker_id" id="hrd-approval-worker-id">
                                        <option value="">Pilih Petugas</option>
                                        @foreach($workers as $worker)
                                            <option value="{{ $worker->id }}">{{ $worker->firstname }} {{ $worker->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3" id="hrd-approval-vendor-row" style="display: none;">
                                    <label class="form-label">Nama Vendor</label>
                                    <input type="text" class="form-control" name="vendor_name" id="hrd-approval-vendor-name"
                                        placeholder="Nama vendor">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan HRD</label>
                                <textarea class="form-control" name="hrd_note" id="hrd-approval-note" rows="4"
                                    placeholder="Tulis catatan HRD"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="hrdActionModal" tabindex="-1" aria-labelledby="hrdActionLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ url('/pengajuan-hrd/update') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="hrdActionLabel">Update HRD</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="hrd-id">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="request_status" id="hrd-status" required onchange="toggleWorkerVendorFields()">
                                    <option value="">- Pilih Status -</option>
                                    <option value="pengadaan">Pengadaan</option>
                                    <option value="pengerjaan">Pengerjaan</option>
                                    <option value="cancel">Cancel</option>
                                </select>
                            </div>
                            <!-- Field untuk Pengerjaan -->
                            <div id="pengerjaan-fields-group" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label">Pelaksana</label>
                                    <select class="form-select" name="work_type" id="hrd-work-type" onchange="toggleWorkerVendor()">
                                        <option value="">- Pilih Pelaksana -</option>
                                        <option value="internal">Internal</option>
                                        <option value="vendor">Vendor</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="hrd-worker-row" style="display: none;">
                                    <label class="form-label">Petugas</label>
                                    <select class="form-select" name="worker_id" id="hrd-worker-id">
                                        <option value="">Pilih Petugas</option>
                                        @foreach($workers as $worker)
                                            <option value="{{ $worker->id }}">{{ $worker->firstname }} {{ $worker->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3" id="hrd-vendor-row" style="display: none;">
                                    <label class="form-label">Nama Vendor</label>
                                    <input type="text" class="form-control" name="vendor_name" id="hrd-vendor-name"
                                        placeholder="Nama vendor">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan HRD</label>
                                <textarea class="form-control" name="hrd_note" id="hrd-note" rows="4"
                                    placeholder="Tulis catatan HRD"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @push('js')
            <script>
                const hrdApprovalModal = document.getElementById('hrdApprovalModal');
                if (hrdApprovalModal) {
                    hrdApprovalModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const id = button.getAttribute('data-id') || '';
                        const status = button.getAttribute('data-status') || '';
                        const normalizedStatus = (status || '').toLowerCase();
                        const workType = button.getAttribute('data-work-type') || 'internal';
                        const vendorName = button.getAttribute('data-vendor-name') || '';
                        const workerId = button.getAttribute('data-worker-id') || '';
                        const note = button.getAttribute('data-note') || '';

                        const inputId = document.getElementById('hrd-approval-id');
                        const inputStatus = document.getElementById('hrd-approval-status');
                        const inputWorkType = document.getElementById('hrd-approval-work-type');
                        const inputWorkerId = document.getElementById('hrd-approval-worker-id');
                        const inputVendorName = document.getElementById('hrd-approval-vendor-name');
                        const inputNote = document.getElementById('hrd-approval-note');

                        if (inputId) inputId.value = id;
                        if (inputStatus) {
                            if (normalizedStatus === 'reject' || normalizedStatus === 'rejected') {
                                inputStatus.value = 'rejected';
                            } else if (normalizedStatus === 'pengadaan' || normalizedStatus === 'pengerjaan') {
                                inputStatus.value = normalizedStatus;
                            } else {
                                inputStatus.value = '';
                            }
                        }
                        if (inputWorkType) inputWorkType.value = workType || 'internal';
                        if (inputWorkerId) inputWorkerId.value = workerId;
                        if (inputVendorName) inputVendorName.value = vendorName;
                        if (inputNote) inputNote.value = note;

                        toggleApprovalWorkerVendorFields();
                        toggleApprovalWorkerVendor();
                    });
                }

                function toggleApprovalWorkerVendorFields() {
                    const statusSelect = document.getElementById('hrd-approval-status');
                    const pengerjaanGroup = document.getElementById('pengerjaan-approval-fields-group');

                    if (!statusSelect || !pengerjaanGroup) return;

                    const selectedStatus = (statusSelect.value || '').toLowerCase();
                    pengerjaanGroup.style.display = selectedStatus === 'pengerjaan' ? 'block' : 'none';
                }

                function toggleApprovalWorkerVendor() {
                    const workTypeSelect = document.getElementById('hrd-approval-work-type');
                    const workerRow = document.getElementById('hrd-approval-worker-row');
                    const vendorRow = document.getElementById('hrd-approval-vendor-row');

                    if (!workTypeSelect) return;

                    const isInternal = workTypeSelect.value === 'internal';

                    if (workerRow) {
                        workerRow.style.display = isInternal ? 'block' : 'none';
                    }
                    if (vendorRow) {
                        vendorRow.style.display = isInternal ? 'none' : 'block';
                    }
                }

                // Toggle pengerjaan fields berdasarkan status di hrdActionModal
                function toggleWorkerVendorFields() {
                    const statusSelect = document.getElementById('hrd-status');
                    const pengerjaanGroup = document.getElementById('pengerjaan-fields-group');
                    
                    if (!statusSelect || !pengerjaanGroup) return;
                    
                    const selectedStatus = statusSelect.value;
                    pengerjaanGroup.style.display = selectedStatus === 'pengerjaan' ? 'block' : 'none';
                }

                // Toggle worker/vendor field
                function toggleWorkerVendor() {
                    const workTypeSelect = document.getElementById('hrd-work-type');
                    const workerRow = document.getElementById('hrd-worker-row');
                    const vendorRow = document.getElementById('hrd-vendor-row');
                    
                    if (!workTypeSelect) return;
                    
                    const isInternal = workTypeSelect.value === 'internal';
                    
                    if (workerRow) {
                        workerRow.style.display = isInternal ? 'block' : 'none';
                    }
                    if (vendorRow) {
                        vendorRow.style.display = isInternal ? 'none' : 'block';
                    }
                }

                // Initialize hrdActionModal (replacement for old listener)
                const hrdActionModal = document.getElementById('hrdActionModal');
                if (hrdActionModal) {
                    hrdActionModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const id = button.getAttribute('data-id') || '';
                        const status = button.getAttribute('data-status') || 'review';
                        const normalizedStatus = (status || '').toLowerCase();
                        const note = button.getAttribute('data-note') || '';
                        const workType = button.getAttribute('data-work-type') || 'internal';
                        const vendorName = button.getAttribute('data-vendor-name') || '';
                        const workerId = button.getAttribute('data-worker-id') || '';

                        const inputId = document.getElementById('hrd-id');
                        const inputStatus = document.getElementById('hrd-status');
                        const inputWorkType = document.getElementById('hrd-work-type');
                        const workerRow = document.getElementById('hrd-worker-row');
                        const inputWorkerId = document.getElementById('hrd-worker-id');
                        const vendorRow = document.getElementById('hrd-vendor-row');
                        const inputVendorName = document.getElementById('hrd-vendor-name');
                        const inputNote = document.getElementById('hrd-note');

                        if (inputId) inputId.value = id;
                        if (inputStatus) {
                            inputStatus.value = normalizedStatus || 'review';
                            
                            // Tampilkan/hide opsi berdasarkan status saat ini (one-way flow)
                            const options = inputStatus.querySelectorAll('option');
                            options.forEach(opt => {
                                opt.style.display = 'block'; // reset dulu
                                opt.disabled = false;
                            });
                            
                            // Aturan transisi berdasarkan status saat ini
                            if (normalizedStatus === 'pengadaan') {
                                // Dari pengadaan hanya bisa ke: pengerjaan atau cancel
                                options.forEach(opt => {
                                    if (opt.value === 'pengadaan') {
                                        opt.style.display = 'none'; // sembunyikan pengadaan
                                    }
                                });
                            } else if (normalizedStatus === 'pengerjaan') {
                                // Dari pengerjaan hanya bisa ke: cancel (atau tetap pengerjaan untuk update)
                                options.forEach(opt => {
                                    if (opt.value === 'pengadaan') {
                                        opt.style.display = 'none'; // sembunyikan pengadaan
                                    }
                                    if (opt.value === 'pengerjaan') {
                                        opt.style.display = 'none'; // sembunyikan pengerjaan juga
                                    }
                                });
                            }
                        }
                        
                        if (inputWorkType) inputWorkType.value = workType || 'internal';
                        if (inputWorkerId) inputWorkerId.value = workerId;
                        if (inputVendorName) inputVendorName.value = vendorName;
                        if (inputNote) inputNote.value = note;

                        // Trigger toggle untuk menampilkan pengerjaan fields sesuai status
                        toggleWorkerVendorFields();

                        // Show/hide worker or vendor based on work type
                        if (inputWorkType) {
                            if (workerRow) {
                                workerRow.style.display = inputWorkType.value === 'internal' ? 'block' : 'none';
                            }
                            if (vendorRow) {
                                vendorRow.style.display = inputWorkType.value === 'vendor' ? 'block' : 'none';
                            }
                        }
                    });

                    const workTypeSelect = document.getElementById('hrd-work-type');
                    if (workTypeSelect) {
                        workTypeSelect.addEventListener('change', function() {
                            const workerRow = document.getElementById('hrd-worker-row');
                            const vendorRow = document.getElementById('hrd-vendor-row');
                            
                            if (workerRow) {
                                workerRow.style.display = this.value === 'internal' ? 'block' : 'none';
                            }
                            if (vendorRow) {
                                vendorRow.style.display = this.value === 'vendor' ? 'block' : 'none';
                            }
                        });
                    }
                }

                const hrdHistoryFilter = document.getElementById('hrd-history-filter');
                if (hrdHistoryFilter) {
                    hrdHistoryFilter.addEventListener('change', function() {
                        const value = this.value.toLowerCase();
                        document.querySelectorAll('table tbody tr[data-status]').forEach(function(row) {
                            const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
                            if (!row.closest('.pu-table-card')) {
                                return;
                            }
                            row.style.display = value === '' || rowStatus === value ? '' : 'none';
                        });
                    });
                }

            </script>
        @endpush
    @elseif ($dmenu == 'trdper')

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4>Daftar Periodic HRD</h4>
                                    <p>Manajemen jadwal kerja periodic dan assigmen petugas.</p>
                                </div>
                                @if ($authorize->add == '1')
                                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location='{{ URL::to($url_menu . '/add/') }}'">
                                        <i class="fas fa-plus me-2"></i>Tambah Jadwal
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover display" id="list_periodic_hrd">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width: 200px;">Action</th>
                                            <th>Tahun</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Get all periodic headers from database
                                            $periodicHeaders = \Illuminate\Support\Facades\DB::table('pl_periodic_header')
                                                ->where('is_active', '1')
                                                ->orderBy('tahun', 'desc')
                                                ->get();
                                        @endphp
                                        @forelse ($periodicHeaders as $header)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button class="btn btn-primary btn-sm dropdown-toggle" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown" 
                                                                aria-expanded="false">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ URL::to($url_menu . '/show/' . $header->tahun) }}">
                                                                    <i class="fas fa-calendar-alt me-2"></i>Lihat Detail
                                                                </a>
                                                            </li>
                                                            @if ($authorize->edit == '1')
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ URL::to($url_menu . '/edit/' . $header->tahun) }}">
                                                                        <i class="fas fa-edit me-2"></i>Edit Jadwal
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if ($authorize->delete == '1')
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#" 
                                                                       onclick="confirmDeletePeriodicHeader(event, 'delete-form-{{ $header->id }}', '{{ $header->tahun }}')">
                                                                        <i class="fas fa-trash me-2"></i>Hapus
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    @if ($authorize->delete == '1')
                                                        <form id="delete-form-{{ $header->id }}" action="{{ URL::to($url_menu . '/' . $header->tahun) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
                                                </td>
                                                <td class="text-sm font-weight-bold">{{ $header->tahun }}</td>
                                                <td class="text-sm">{{ $header->keterangan ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                                    Belum ada data periodic HRD. Klik tombol "Tambah Jadwal" untuk menambahkan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('js')
            <script>
                function confirmDeletePeriodicHeader(event, formId, tahun) {
                    event.preventDefault();
                    const submitDelete = () => {
                        const form = document.getElementById(formId);
                        if (form) {
                            form.submit();
                        }
                    };

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Konfirmasi Hapus',
                            text: `Hapus data tahun ${tahun}?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                submitDelete();
                            }
                        });
                        return;
                    }

                    if (confirm(`Hapus data tahun ${tahun}?`)) {
                        submitDelete();
                    }
                }
            </script>
        @endpush

    @elseif ($dmenu == 'trpetp')
        @push('css')
            <link
                rel="stylesheet">
            <style>
                :root {
                    --pu-ink: #0f172a;
                    --pu-mute: #64748b;
                    --pu-teal: #0ea5a5;
                    --pu-amber: #f59e0b;
                    --pu-slate: #e2e8f0;
                    --pu-card: #ffffff;
                    --pu-bg: linear-gradient(135deg, #eff6ff 0%, #fef9c3 55%, #ecfeff 100%);
                }

                .pu-wrap {
                    font-family: var(--bs-body-font-family);
                    position: relative;
                    z-index: 3;
                    padding-top: 12px;
                }

                .pu-hero {
                    background: var(--pu-bg);
                    border-radius: 18px;
                    padding: 24px 28px;
                    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
                    margin-bottom: 20px;
                    position: relative;
                    overflow: hidden;
                    z-index: 3;
                }

                .pu-hero::after {
                    content: "";
                    position: absolute;
                    inset: -20% -10% auto auto;
                    width: 220px;
                    height: 220px;
                    background: radial-gradient(circle, rgba(14, 165, 165, 0.25), transparent 65%);
                    border-radius: 50%;
                }

                .pu-hero h2 {
                    font-weight: 700;
                    color: var(--pu-ink);
                }

                .pu-hero p {
                    color: var(--pu-mute);
                    margin-bottom: 0;
                }

                .pu-table-card {
                    border-radius: 18px;
                    border: 1px solid var(--pu-slate);
                    overflow: hidden;
                    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
                }

                .pu-table tbody tr {
                    border-bottom: 1px solid #eef2f7;
                }

                .pu-table tbody tr:hover {
                    background: #f8fafc;
                }

                .pu-empty {
                    padding: 24px;
                    text-align: center;
                    color: var(--pu-mute);
                }
            </style>
        @endpush
        @php
            // Use $user_login from controller (already passed to all views)
            $currentUser = $user_login ?? auth()->user();
            if (!$currentUser && session()->has('username')) {
                $currentUser = \Illuminate\Support\Facades\DB::table('users')
                    ->where('username', session('username'))
                    ->first();
            }
            $currentUserId = $currentUser ? $currentUser->id : null;
            $currentUserRoles = $currentUser ? (string) ($currentUser->idroles ?? '') : '';
            $normalizedRoles = ',' . str_replace(' ', '', strtolower($currentUserRoles)) . ',';
            $isPetugas = str_contains($normalizedRoles, ',ptgoff,') || str_contains($normalizedRoles, ',ptgspu,');
            
            // Get all user IDs with the same role as current user for role-based filtering
            $roleGroupUserIds = [];
            if ($isPetugas && $currentUser) {
                $currentUserRole = str_contains($normalizedRoles, ',ptgoff,') ? 'ptgoff' : 'ptgspu';
                $roleGroupUserIds = \Illuminate\Support\Facades\DB::table('users')
                    ->whereRaw("FIND_IN_SET(?, REPLACE(idroles, ' ', ''))", [$currentUserRole])
                    ->where('isactive', '1')
                    ->pluck('id')
                    ->toArray();
            }

            // Get filter values from request, default periode = bulan_ini
            $filterPeriode = request('periode', 'bulan_ini');
            $filterArea = request('area', '');

            // Calculate date range based on periode filter
            $dateStart = null;
            $dateEnd = null;
            $today = \Illuminate\Support\Carbon::today();
            
            switch ($filterPeriode) {
                case 'hari_ini':
                    $dateStart = $today->format('Y-m-d');
                    $dateEnd = $today->format('Y-m-d');
                    break;
                case 'minggu_ini':
                    $dateStart = $today->copy()->startOfWeek()->format('Y-m-d');
                    $dateEnd = $today->copy()->endOfWeek()->format('Y-m-d');
                    break;
                case 'bulan_ini':
                    $dateStart = $today->copy()->startOfMonth()->format('Y-m-d');
                    $dateEnd = $today->copy()->endOfMonth()->format('Y-m-d');
                    break;
                case 'semua':
                default:
                    // No date filter
                    break;
            }

            // Get all areas for filter dropdown
            $allAreas = \Illuminate\Support\Facades\DB::table('ms_area')
                ->where('is_active', '1')
                ->orderBy('nama_area', 'asc')
                ->get();

            // Query periodic items with filters
            $query = \Illuminate\Support\Facades\DB::table('pl_periodic_items')
                ->join('pl_periodic_detail', 'pl_periodic_items.detail_id', '=', 'pl_periodic_detail.id')
                ->join('ms_periodic', 'pl_periodic_detail.periodic_id', '=', 'ms_periodic.id')
                ->leftJoin('ms_area', 'pl_periodic_detail.area_id', '=', 'ms_area.id')
                ->leftJoin('users as worker', 'pl_periodic_detail.worker_id', '=', 'worker.id')
                ->select(
                    'pl_periodic_items.id as periodic_item_id',
                    'pl_periodic_items.planned_date',
                    'pl_periodic_items.realization_date',
                    'pl_periodic_items.created_at',
                    'ms_periodic.job_description',
                    'pl_periodic_detail.periode',
                    'pl_periodic_detail.area_id',
                    'ms_area.nama_area as area_name',
                    \Illuminate\Support\Facades\DB::raw("CONCAT(worker.firstname, ' ', worker.lastname) as worker_name")
                )
                ->where('pl_periodic_items.is_active', '1')
                ->where('pl_periodic_detail.is_active', '1')
                ->whereNotNull('pl_periodic_items.planned_date')
                ->orderBy('pl_periodic_items.planned_date', 'asc');

            // Apply role-based filter for petugas
            if ($isPetugas && !empty($roleGroupUserIds)) {
                $query->whereIn('pl_periodic_detail.worker_id', $roleGroupUserIds);
            }

            // Apply date filter
            if ($dateStart && $dateEnd) {
                $query->whereBetween('pl_periodic_items.planned_date', [$dateStart, $dateEnd]);
            }

            // Apply area filter
            if ($filterArea) {
                $query->where('pl_periodic_detail.area_id', $filterArea);
            }

            // Paginate results
            $perPage = 20;
            $currentPage = max(1, (int) request('page', 1));
            $totalItems = (clone $query)->count();
            $totalPages = max(1, ceil($totalItems / $perPage));
            $offset = ($currentPage - 1) * $perPage;
            
            $periodicItems = $query->skip($offset)->take($perPage)->get();
        @endphp
        
        <div class="container-fluid py-4 pu-wrap">
            <div class="pu-hero">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-1">Pekerjaan Periodic</h2>
                        <p class="text-sm">Daftar pekerjaan periodic berdasarkan tanggal rencana dari HRD.</p>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="card mb-3">
                <div class="card-body py-3">
                    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="filter-periode" class="form-label text-xs mb-1">
                                <i class="fas fa-calendar-alt me-1"></i> Periode
                            </label>
                            <select name="periode" id="filter-periode" class="form-select form-select-sm">
                                <option value="hari_ini" {{ $filterPeriode == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="minggu_ini" {{ $filterPeriode == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="bulan_ini" {{ $filterPeriode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="semua" {{ $filterPeriode == 'semua' ? 'selected' : '' }}>Semua</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filter-area" class="form-label text-xs mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i> Area
                            </label>
                            <select name="area" id="filter-area" class="form-select form-select-sm">
                                <option value="">Semua Area</option>
                                @foreach ($allAreas as $area)
                                    <option value="{{ $area->id }}" {{ $filterArea == $area->id ? 'selected' : '' }}>
                                        {{ $area->nama_area }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-sm me-2">
                                <i class="fas fa-filter me-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-redo me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                    @if ($totalItems > 0)
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Menampilkan {{ $periodicItems->count() }} dari {{ $totalItems }} pekerjaan
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card pu-table-card">
                <div class="table-responsive">
                    <table class="table pu-table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-xs text-center">Aksi</th>
                                <th class="text-uppercase text-xs">No</th>
                                <th class="text-uppercase text-xs">Tanggal Rencana</th>
                                <th class="text-uppercase text-xs">Area</th>
                                <th class="text-uppercase text-xs">Deskripsi</th>
                                <th class="text-uppercase text-xs">Periode</th>
                                <th class="text-uppercase text-xs">Tanggal Realisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($periodicItems as $detail)
                                <tr>
                                    <td class="text-center">
                                        @if (empty($detail->realization_date))
                                            <button class="btn btn-primary btn-sm mb-0 px-3" type="button"
                                                data-bs-toggle="modal" data-bs-target="#petugasPeriodicModal"
                                                data-periodic-item-id="{{ $detail->periodic_item_id }}"
                                                data-description="{{ $detail->job_description ?? '' }}"
                                                data-planned-date="{{ $detail->planned_date ?? '' }}"
                                                data-realization-date="{{ $detail->realization_date ?? '' }}">
                                                <i class="fas fa-calendar-check"></i><span class="font-weight-bold"> Input Realisasi</span>
                                            </button>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-sm">{{ ($currentPage - 1) * $perPage + $loop->iteration }}</td>
                                    <td class="text-sm">{{ $detail->planned_date ? \Illuminate\Support\Carbon::parse($detail->planned_date)->format('d-m-Y') : '-' }}</td>
                                    <td class="text-sm">{{ $detail->area_name ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->job_description ?? '-' }}</td>
                                    <td class="text-sm">{{ ucfirst($detail->periode ?? '-') }}</td>
                                    <td class="text-sm">{{ $detail->realization_date ? \Illuminate\Support\Carbon::parse($detail->realization_date)->format('d-m-Y') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="pu-empty">
                                        @if ($filterPeriode != 'semua' || $filterArea)
                                            Tidak ada pekerjaan periodic dengan filter yang dipilih.
                                        @else
                                            Belum ada pekerjaan periodic dengan tanggal rencana.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                @if ($totalPages > 1)
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Halaman {{ $currentPage }} dari {{ $totalPages }}
                            </small>
                        </div>
                        <nav aria-label="Pagination">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Button --}}
                                @if ($currentPage > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="?periode={{ $filterPeriode }}&area={{ $filterArea }}&page={{ $currentPage - 1 }}">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @php
                                    $start = max(1, $currentPage - 2);
                                    $end = min($totalPages, $currentPage + 2);
                                @endphp

                                @if ($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="?periode={{ $filterPeriode }}&area={{ $filterArea }}&page=1">1</a>
                                    </li>
                                    @if ($start > 2)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a class="page-link" href="?periode={{ $filterPeriode }}&area={{ $filterArea }}&page={{ $i }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                @if ($end < $totalPages)
                                    @if ($end < $totalPages - 1)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="?periode={{ $filterPeriode }}&area={{ $filterArea }}&page={{ $totalPages }}">{{ $totalPages }}</a>
                                    </li>
                                @endif

                                {{-- Next Button --}}
                                @if ($currentPage < $totalPages)
                                    <li class="page-item">
                                        <a class="page-link" href="?periode={{ $filterPeriode }}&area={{ $filterArea }}&page={{ $currentPage + 1 }}">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>

        <div class="modal fade" id="petugasPeriodicModal" tabindex="-1" aria-labelledby="petugasPeriodicLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('work-schedule.complete') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="petugasPeriodicLabel">Input Realisasi Pekerjaan Periodic</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="periodic_item_id" id="petugas-periodic-item-id">
                            <div class="mb-3">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" class="form-control" id="petugas-periodic-description" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Rencana</label>
                                <input type="text" class="form-control" id="petugas-periodic-planned-date" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Realisasi</label>
                                <input type="date" class="form-control" name="realization_date" id="petugas-periodic-realization-date" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dokumentasi (bisa pilih lebih dari 1 file)</label>
                                <input type="file" class="form-control" name="documentation[]" required multiple
                                    accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="description" rows="3"
                                    placeholder="Keterangan pekerjaan"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @push('js')
            <script>
                const petugasPeriodicModal = document.getElementById('petugasPeriodicModal');
                if (petugasPeriodicModal) {
                    petugasPeriodicModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const periodicItemId = button.getAttribute('data-periodic-item-id') || '';
                        const description = button.getAttribute('data-description') || '';
                        const plannedDate = button.getAttribute('data-planned-date') || '';
                        const realizationDate = button.getAttribute('data-realization-date') || '';

                        const inputPeriodicItemId = document.getElementById('petugas-periodic-item-id');
                        const inputDescription = document.getElementById('petugas-periodic-description');
                        const inputPlannedDate = document.getElementById('petugas-periodic-planned-date');
                        const inputRealizationDate = document.getElementById('petugas-periodic-realization-date');

                        if (inputPeriodicItemId) inputPeriodicItemId.value = periodicItemId;
                        if (inputDescription) inputDescription.value = description;
                        if (inputPlannedDate) inputPlannedDate.value = plannedDate;
                        if (inputRealizationDate) inputRealizationDate.value = realizationDate || new Date().toISOString().split('T')[0];
                    });
                }
            </script>
        @endpush
    @elseif ($dmenu == 'trpetn')
        @push('css')
            <link
                rel="stylesheet">
            <style>
                :root {
                    --pu-ink: #0f172a;
                    --pu-mute: #64748b;
                    --pu-teal: #0ea5a5;
                    --pu-amber: #f59e0b;
                    --pu-slate: #e2e8f0;
                    --pu-card: #ffffff;
                    --pu-bg: linear-gradient(135deg, #eff6ff 0%, #fef9c3 55%, #ecfeff 100%);
                }

                .pu-wrap {
                    font-family: var(--bs-body-font-family);
                    position: relative;
                    z-index: 3;
                    padding-top: 12px;
                }

                .pu-hero {
                    background: var(--pu-bg);
                    border-radius: 18px;
                    padding: 24px 28px;
                    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
                    margin-bottom: 20px;
                    position: relative;
                    overflow: hidden;
                    z-index: 3;
                }

                .pu-hero::after {
                    content: "";
                    position: absolute;
                    inset: -20% -10% auto auto;
                    width: 220px;
                    height: 220px;
                    background: radial-gradient(circle, rgba(14, 165, 165, 0.25), transparent 65%);
                    border-radius: 50%;
                }

                .pu-hero h2 {
                    font-weight: 700;
                    color: var(--pu-ink);
                }

                .pu-hero p {
                    color: var(--pu-mute);
                    margin-bottom: 0;
                }

                .pu-table-card {
                    border-radius: 18px;
                    border: 1px solid var(--pu-slate);
                    overflow: hidden;
                    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
                }

                .pu-table tbody tr {
                    border-bottom: 1px solid #eef2f7;
                }

                .pu-table tbody tr:hover {
                    background: #f8fafc;
                }

                .pu-empty {
                    padding: 24px;
                    text-align: center;
                    color: var(--pu-mute);
                }
            </style>
        @endpush
        @php
            // Use $user_login from controller (already passed to all views)
            $currentUser = $user_login ?? auth()->user();
            if (!$currentUser && session()->has('username')) {
                $currentUser = \Illuminate\Support\Facades\DB::table('users')
                    ->where('username', session('username'))
                    ->first();
            }
            $currentUserId = $currentUser ? $currentUser->id : null;
            $currentUserRoles = $currentUser ? (string) ($currentUser->idroles ?? '') : '';
            $normalizedRoles = ',' . str_replace(' ', '', strtolower($currentUserRoles)) . ',';
            $isPetugasOffice = str_contains($normalizedRoles, ',ptgoff,');
            $isPetugasPU = str_contains($normalizedRoles, ',ptgspu,');
            
            // Get all user IDs with ptgoff role for Office filtering
            $officeRoleUserIds = [];
            if ($isPetugasOffice) {
                $officeRoleUserIds = \Illuminate\Support\Facades\DB::table('users')
                    ->whereRaw("FIND_IN_SET('ptgoff', REPLACE(idroles, ' ', ''))")
                    ->where('isactive', '1')
                    ->pluck('id')
                    ->toArray();
            }

            $nonPeriodicItems = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                ->leftJoin('ms_area', 'pl_non_periodic.area_id', '=', 'ms_area.id')
                ->select(
                    'pl_non_periodic.id',
                    'pl_non_periodic.request_status',
                    'pl_non_periodic.job_description',
                    'pl_non_periodic.work_type',
                    'pl_non_periodic.*',
                    'ms_area.nama_area as area_name'
                )
                ->whereIn('pl_non_periodic.request_status', ['pengerjaan', 'revisi'])
                ->where('pl_non_periodic.is_active', '1');

            // Apply role-based filter: Petugas Office only sees assigned tasks, Petugas PU sees all
            if ($isPetugasOffice && !empty($officeRoleUserIds)) {
                $nonPeriodicItems->whereIn('pl_non_periodic.worker_id', $officeRoleUserIds);
            }

            $nonPeriodicItems = $nonPeriodicItems->orderBy('pl_non_periodic.created_at', 'desc')->get();

            // Parse attachment files from each item
            $nonPeriodicItems = $nonPeriodicItems->map(function ($item) {
                $attachments = [];
                if (!empty($item->attachment)) {
                    $rawAttachment = $item->attachment;
                    // Try JSON decode first
                    $decoded = json_decode($rawAttachment, true);
                    if (is_array($decoded)) {
                        $attachments = $decoded;
                    } else {
                        // Fallback: treat as comma-separated or single path
                        $parts = array_map('trim', explode(',', $rawAttachment));
                        $attachments = array_filter($parts);
                    }
                }

                $revisionAttachments = [];
                if (!empty($item->revision_attachment)) {
                    $rawRevisionAttachment = $item->revision_attachment;
                    $decodedRevision = json_decode($rawRevisionAttachment, true);
                    if (is_array($decodedRevision)) {
                        $revisionAttachments = $decodedRevision;
                    } else {
                        $partsRevision = array_map('trim', explode(',', $rawRevisionAttachment));
                        $revisionAttachments = array_filter($partsRevision);
                    }
                }
                $item->attachments = array_values($attachments);
                $item->revision_attachments = array_values($revisionAttachments);
                return $item;
            });

            $getPelaksanaLabel = function ($item) {
                $workType = strtolower(trim((string) ($item->work_type ?? '')));

                if ($workType === 'internal') {
                    return 'Internal';
                }

                if ($workType === 'vendor') {
                    return 'Vendor';
                }

                return '-';
            };

            $getPelaksanaClass = function ($item) {
                $workType = strtolower(trim((string) ($item->work_type ?? '')));

                if ($workType === 'internal') {
                    return 'background-color:#1d4ed8;color:#ffffff;';
                }

                if ($workType === 'vendor') {
                    return 'background-color:#f59e0b;color:#ffffff;';
                }

                return 'background-color:#6b7280;color:#ffffff;';
            };
        @endphp
        <div class="container-fluid py-4 pu-wrap">
            <div class="pu-hero">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-1">Pekerjaan Non-Periodic</h2>
                        <p class="text-sm">Daftar pekerjaan non-periodic untuk petugas.</p>
                    </div>
                </div>
            </div>

            @include('components.alert')

            <div class="card pu-table-card">
                <div class="table-responsive">
                    <table class="table pu-table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-xs text-center">Aksi</th>
                                <th class="text-uppercase text-xs">No</th>
                                <th class="text-uppercase text-xs">Tanggal Pengajuan</th>
                                <th class="text-uppercase text-xs">Area</th>
                                <th class="text-uppercase text-xs">Deskripsi</th>
                                <th class="text-uppercase text-xs">Pelaksana</th>
                                <th class="text-uppercase text-xs">Status</th>
                                <th class="text-uppercase text-xs">Catatan Revisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nonPeriodicItems as $detail)
                                @php
                                    $rawStatus = $detail->request_status ?? '';
                                    $status = strtolower($rawStatus);
                                    if (in_array($status, ['approved'], true)) {
                                        $status = 'review';
                                    } elseif ($status === 'reject') {
                                        $status = 'rejected';
                                    } elseif (in_array($status, ['canceled', 'cancelled'], true)) {
                                        $status = 'cancel';
                                    }
                                    $dateValue = $detail->created_at ?? $detail->tanggal ?? $detail->date ?? null;
                                    $displayDate = $dateValue
                                        ? \Illuminate\Support\Carbon::parse($dateValue)->format('d-m-Y')
                                        : '-';
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Aksi Pekerjaan">
                                            <button class="btn btn-info btn-sm mb-0 px-3" type="button"
                                                data-bs-toggle="modal" data-bs-target="#petugasDetailModal"
                                                data-tanggal="{{ $displayDate }}"
                                                data-area="{{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}"
                                                data-deskripsi="{{ $detail->job_description ?? $detail->description ?? '-' }}"
                                                data-attachments="{{ json_encode($detail->attachments ?? []) }}"
                                                data-revision-attachments="{{ json_encode($detail->revision_attachments ?? []) }}"
                                                data-catatan="{{ data_get($detail, 'requester_note') ?? '-' }}">
                                                <i class="fas fa-eye"></i><span class="font-weight-bold"> Detail</span>
                                            </button>
                                            <button class="btn btn-primary btn-sm mb-0 px-3" type="button"
                                                data-bs-toggle="modal" data-bs-target="#petugasCompleteModal"
                                                data-non-periodic-id="{{ $detail->id }}"
                                                data-description="{{ $detail->job_description ?? $detail->description ?? '' }}"
                                                onclick="document.getElementById('petugas-non-periodic-id').value='{{ $detail->id }}'; document.getElementById('petugas-job-description').value='{{ addslashes($detail->job_description ?? $detail->description ?? '') }}';">
                                                <i class="fas fa-check"></i><span class="font-weight-bold"> Selesai</span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-sm">{{ $loop->iteration }}</td>
                                    <td class="text-sm">{{ $displayDate }}</td>
                                    <td class="text-sm">{{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}</td>
                                    <td class="text-sm">{{ $detail->job_description ?? $detail->description ?? '-' }}</td>
                                    <td class="text-sm">
                                        <span class="badge" style="{{ $getPelaksanaClass($detail) }}">
                                            {{ $getPelaksanaLabel($detail) }}
                                        </span>
                                    </td>
                                    <td class="text-sm">
                                        @if($status === 'revisi')
                                            <span class="badge bg-warning">Revisi</span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-sm">
                                        @php
                                            $requesterNote = data_get($detail, 'requester_note');
                                        @endphp
                                        @if($status === 'revisi' && !empty($requesterNote))
                                            <small class="text-muted">{{ $requesterNote }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="pu-empty">Belum ada pekerjaan non-periodic.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="petugasCompleteModal" tabindex="-1" aria-labelledby="petugasCompleteLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('work-schedule.complete') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="petugasCompleteLabel">Konfirmasi Pekerjaan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="hidden" name="non_periodic_id" id="petugas-non-periodic-id">
                            <div class="mb-3">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" class="form-control" id="petugas-job-description" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dokumentasi (bisa pilih lebih dari 1 file)</label>
                                <input type="file" class="form-control" name="documentation[]" required multiple
                                    accept=".jpg,.jpeg,.png,.pdf">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" name="description" rows="3"
                                    placeholder="Keterangan pekerjaan"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="petugasDetailModal" tabindex="-1" aria-labelledby="petugasDetailLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="petugasDetailLabel">Detail Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label text-xs text-uppercase mb-1">Tanggal Pengajuan</label>
                            <input type="text" class="form-control" id="petugas-detail-tanggal" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-xs text-uppercase mb-1">Area</label>
                            <input type="text" class="form-control" id="petugas-detail-area" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-xs text-uppercase mb-1">Deskripsi</label>
                            <textarea class="form-control" id="petugas-detail-deskripsi" rows="3" readonly></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-xs text-uppercase mb-1">Lampiran Pengajuan</label>
                            <div id="petugas-detail-attachments" class="d-flex flex-wrap gap-2" style="max-height: 300px; overflow-y: auto;">
                                <p class="text-muted text-sm mb-0">Tidak ada lampiran.</p>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-xs text-uppercase mb-1">Lampiran Revisi</label>
                            <div id="petugas-detail-revision-attachments" class="d-flex flex-wrap gap-2" style="max-height: 300px; overflow-y: auto;">
                                <p class="text-muted text-sm mb-0">Tidak ada lampiran revisi.</p>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-xs text-uppercase mb-1">Catatan Revisi</label>
                            <textarea class="form-control" id="petugas-detail-catatan" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Image Preview Modal -->
        <div class="modal fade" id="previewImageModal" tabindex="-1" aria-labelledby="previewImageLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewImageLabel">Preview Lampiran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" style="background-color: #f8f9fa;">
                        <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 500px; border-radius: 4px;">
                    </div>
                    <div class="modal-footer">
                        <a id="downloadImageBtn" href="" download class="btn btn-primary">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @push('js')
            <script>
                const petugasCompleteModal = document.getElementById('petugasCompleteModal');
                if (petugasCompleteModal) {
                    petugasCompleteModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const nonPeriodicId = button.getAttribute('data-non-periodic-id') || '';
                        const description = button.getAttribute('data-description') || '';

                        const inputNonPeriodic = document.getElementById('petugas-non-periodic-id');
                        const inputDesc = document.getElementById('petugas-job-description');

                        if (inputNonPeriodic) inputNonPeriodic.value = nonPeriodicId;
                        if (inputDesc) inputDesc.value = description;
                    });
                }

                const petugasDetailModal = document.getElementById('petugasDetailModal');
                if (petugasDetailModal) {
                    petugasDetailModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const tanggal = button.getAttribute('data-tanggal') || '-';
                        const area = button.getAttribute('data-area') || '-';
                        const deskripsi = button.getAttribute('data-deskripsi') || '-';
                        const attachmentsJson = button.getAttribute('data-attachments') || '[]';
                        const revisionAttachmentsJson = button.getAttribute('data-revision-attachments') || '[]';
                        const catatan = button.getAttribute('data-catatan') || '-';

                        const inputTanggal = document.getElementById('petugas-detail-tanggal');
                        const inputArea = document.getElementById('petugas-detail-area');
                        const inputDeskripsi = document.getElementById('petugas-detail-deskripsi');
                        const inputAttachments = document.getElementById('petugas-detail-attachments');
                        const inputRevisionAttachments = document.getElementById('petugas-detail-revision-attachments');
                        const inputCatatan = document.getElementById('petugas-detail-catatan');

                        if (inputTanggal) inputTanggal.value = tanggal;
                        if (inputArea) inputArea.value = area;
                        if (inputDeskripsi) inputDeskripsi.value = deskripsi;
                        if (inputCatatan) inputCatatan.value = catatan;

                        const renderAttachmentFiles = function(container, filesJson, emptyMessage) {
                            if (!container) {
                                return;
                            }
                            try {
                                const files = JSON.parse(filesJson || '[]');
                                container.innerHTML = '';

                                if (files.length === 0) {
                                    container.innerHTML = '<p class="text-muted text-sm mb-0">' + emptyMessage + '</p>';
                                } else {
                                    files.forEach(function(file) {
                                        const fileUrl = '/storage/' + file;
                                        const isImage = /\.(jpg|jpeg|png|gif|webp|jfif|heic|heif)$/i.test(file);
                                        const isPdf = /\.pdf$/i.test(file);

                                        if (isImage) {
                                            const img = document.createElement('img');
                                            img.src = fileUrl;
                                            img.alt = 'Lampiran';
                                            img.style.cssText = 'max-width: 150px; max-height: 150px; border-radius: 4px; border: 1px solid #e9ecef; cursor: pointer;';
                                            img.onclick = function() {
                                                // Show preview modal
                                                const previewImg = document.getElementById('previewImage');
                                                const downloadBtn = document.getElementById('downloadImageBtn');
                                                if (previewImg) previewImg.src = fileUrl;
                                                if (downloadBtn) {
                                                    downloadBtn.href = fileUrl;
                                                    downloadBtn.download = file.split('/').pop();
                                                }
                                                const previewModal = new bootstrap.Modal(document.getElementById('previewImageModal'));
                                                previewModal.show();
                                            };
                                            container.appendChild(img);
                                        } else if (isPdf) {
                                            const pdfLink = document.createElement('a');
                                            pdfLink.href = fileUrl;
                                            pdfLink.target = '_blank';
                                            pdfLink.className = 'badge bg-danger';
                                            pdfLink.textContent = '📄 ' + file.split('/').pop();
                                            pdfLink.style.cssText = 'display: inline-block; padding: 6px 8px; cursor: pointer;';
                                            container.appendChild(pdfLink);
                                        }
                                    });
                                }
                            } catch (e) {
                                container.innerHTML = '<p class="text-muted text-sm mb-0">' + emptyMessage + '</p>';
                            }
                        };

                        renderAttachmentFiles(inputAttachments, attachmentsJson, 'Tidak ada lampiran.');
                        renderAttachmentFiles(inputRevisionAttachments, revisionAttachmentsJson, 'Tidak ada lampiran revisi.');
                    });
                }
            </script>
        @endpush
    @elseif ($dmenu == 'trpetd')
        <style>
                .pu-wrap {
                    padding-top: 12px;
                    margin-top: 8px;
                }

                @media (max-width: 768px) {
                    .pu-wrap {
                        margin-top: 6px;
                    }
                }

                .daily-top-progress {
                    background: #ffffff;
                    border: 1px solid #e9ecef;
                    border-radius: 12px;
                    padding: 20px 22px;
                    margin-bottom: 16px;
                }

                .daily-top-progress h2 {
                    font-weight: 700;
                    color: #344767;
                }

                .daily-top-progress p {
                    color: #67748e;
                    margin-bottom: 0;
                }

                .pu-table-card {
                    border: 1px solid #e9ecef;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: none;
                }

                .daily-progress {
                    height: 8px;
                    background: #e9ecef;
                    border-radius: 10px;
                    overflow: hidden;
                    margin-top: 10px;
                }

                .daily-progress-fill {
                    height: 100%;
                    background: #5e72e4;
                    transition: width 0.3s ease;
                }

                .daily-stats {
                    display: flex;
                    gap: 14px;
                    margin-top: 8px;
                    font-size: 0.85rem;
                }

                .daily-stat-item {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    color: #67748e;
                }

                .daily-stat-number {
                    font-weight: 700;
                    color: #344767;
                }

                .daily-section-header {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-bottom: 12px;
                    padding-bottom: 8px;
                    border-bottom: 1px solid #e9ecef;
                }

                .daily-section-title {
                    font-size: 0.82rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.3px;
                    color: #344767;
                }

                .daily-section-badge {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 22px;
                    height: 22px;
                    padding: 0 8px;
                    border-radius: 999px;
                    font-size: 0.72rem;
                    font-weight: 700;
                }

                .daily-section-badge.today {
                    background: #e8f0fe;
                    color: #5e72e4;
                }

                .daily-section-badge.backlog {
                    background: #fff3cd;
                    color: #b27a00;
                }

                .daily-list {
                    display: grid;
                    gap: 8px;
                }

                .daily-item {
                    border: 1px solid #e9ecef;
                    border-radius: 10px;
                    padding: 14px;
                    background: #ffffff;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 12px;
                    transition: background-color 0.2s ease;
                }

                .daily-item:hover {
                    background: #f8f9fa;
                }

                .daily-item.today {
                    border-color: #dbe2ef;
                    background: #ffffff;
                }

                .daily-item.backlog {
                    border-color: #f2e6c9;
                    background: #fffdf7;
                }

                .daily-item.completed {
                    opacity: 0.75;
                }

                .daily-item-content {
                    flex: 1;
                }

                .daily-title {
                    font-weight: 600;
                    color: #344767;
                    margin-bottom: 4px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .daily-date-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    padding: 2px 8px;
                    border-radius: 6px;
                    font-size: 0.7rem;
                    font-weight: 600;
                    background: #fff3cd;
                    color: #b27a00;
                }

                .daily-status {
                    font-size: 0.75rem;
                    color: #67748e;
                }

                .daily-inline-progress {
                    border: 1px solid #e9ecef;
                    border-radius: 10px;
                    padding: 10px 12px;
                    background: #f8f9fa;
                }

                .daily-inline-title {
                    font-size: 0.75rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    color: #344767;
                    margin-bottom: 6px;
                }

                .form-check-input:checked {
                    background-color: #5e72e4;
                    border-color: #5e72e4;
                }

                .pu-empty {
                    padding: 24px;
                    text-align: center;
                    color: #67748e;
                }

                .toast-success {
                    position: fixed;
                    top: 80px;
                    right: 20px;
                    z-index: 9999;
                    min-width: 300px;
                    background: #ffffff;
                    border-left: 4px solid #198754;
                    border-radius: 8px;
                    padding: 16px;
                    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.12);
                    animation: slideIn 0.3s ease;
                }

            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        </style>
        @php
            // Use $user_login from controller (already passed to all views)
            $currentUser = $user_login ?? auth()->user();
            if (!$currentUser && session()->has('username')) {
                $currentUser = \Illuminate\Support\Facades\DB::table('users')
                    ->where('username', session('username'))
                    ->first();
            }

            $currentUserId = $currentUser ? $currentUser->id : null;
            $today = \Illuminate\Support\Carbon::today()->toDateString();
            $yesterday = \Illuminate\Support\Carbon::yesterday()->toDateString();
            $twoDaysAgo = \Illuminate\Support\Carbon::today()->subDays(2)->toDateString();

            $todayCompletedCount = 0;
            $totalTodayCount = 0;
            $backlogCount = 0;
            
            // Get current user role for assigned_role based filtering
            $assignedRoleFilter = null;
            if ($currentUser) {
                $normalizedRoles = array_values(array_filter(explode(',', str_replace(' ', '', strtolower((string) ($currentUser->idroles ?? ''))))));
                foreach ($normalizedRoles as $roleCode) {
                    if (str_starts_with($roleCode, 'ptg')) {
                        $assignedRoleFilter = $roleCode;
                        break;
                    }
                }
            }

            $dailyTasksQuery = \Illuminate\Support\Facades\DB::table('ms_daily')
                ->where('is_active', '1');
            
            // Apply role-based filter using assigned_role column
            if ($assignedRoleFilter) {
                $dailyTasksQuery->where('assigned_role', $assignedRoleFilter);
            }
            
            $dailyTasks = $dailyTasksQuery->orderBy('id')->get();

            $taskIds = $dailyTasks->pluck('id');
            $todayLogs = collect();
            $backlogEntries = collect();
            $totalTodayCount = $dailyTasks->count();

            if ($dailyTasks->isNotEmpty()) {
                $allRelevantLogs = \Illuminate\Support\Facades\DB::table('rp_daily_log')
                    ->whereIn('work_date', [$today, $yesterday, $twoDaysAgo])
                    ->whereIn('daily_task_id', $taskIds)
                    ->get();

                $todayLogs = $allRelevantLogs
                    ->where('work_date', $today)
                    ->keyBy('daily_task_id');

                $todayCompletedCount = $todayLogs->filter(function ($log) {
                    return strtolower($log->job_status ?? '') === 'completed';
                })->count();

                $historyLogMap = $allRelevantLogs
                    ->whereIn('work_date', [$yesterday, $twoDaysAgo])
                    ->keyBy(function ($log) {
                        return $log->work_date . '|' . $log->daily_task_id;
                    });

                foreach ([$yesterday, $twoDaysAgo] as $historyDate) {
                    foreach ($dailyTasks as $task) {
                        $mapKey = $historyDate . '|' . $task->id;
                        $historyLog = $historyLogMap->get($mapKey);
                        $isCompleted = $historyLog && strtolower($historyLog->job_status ?? '') === 'completed';

                        if ($isCompleted) {
                            continue;
                        }

                        $backlogEntries->push((object) [
                            'task_id' => $task->id,
                            'job_description' => $task->job_description,
                            'work_date' => $historyDate,
                            'job_status' => $historyLog->job_status ?? 'pending',
                        ]);
                        $backlogCount++;
                    }
                }
            }

            $progressPercent = $totalTodayCount > 0 ? round(($todayCompletedCount / $totalTodayCount) * 100) : 0;
        @endphp
        <div class="container-fluid py-4 pu-wrap">
            <div class="daily-top-progress">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-1">Pekerjaan Daily</h2>
                        <p class="text-sm">Checklist harian petugas.</p>
                        <div class="daily-progress">
                            <div class="daily-progress-fill" style="width: {{ $progressPercent }}%;"></div>
                        </div>
                        <div class="daily-stats">
                            <div class="daily-stat-item">
                                <i class="fas fa-check-circle text-primary"></i>
                                <span class="daily-stat-number">{{ $todayCompletedCount }}/{{ $totalTodayCount }}</span>
                                <span>Selesai</span>
                            </div>
                            @if ($backlogCount > 0)
                                <div class="daily-stat-item">
                                    <i class="fas fa-clock text-warning"></i>
                                    <span class="daily-stat-number">{{ $backlogCount }}</span>
                                    <span>Tertinggal</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (session('message'))
                <div class="toast-success" id="toast-notification">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle text-success" style="font-size: 1.25rem;"></i>
                        <div>
                            <div style="font-weight: 600; color: #0f172a;">Berhasil!</div>
                            <div style="font-size: 0.875rem; color: #64748b;">{{ session('message') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card pu-table-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('daily-log.store') }}" id="daily-checklist-form">
                        @csrf
                        <input type="hidden" name="work_date" value="{{ $today }}">

                        <div class="daily-inline-progress mb-3">
                            <div class="daily-inline-title">Progress Hari Ini</div>
                            <div class="daily-progress mt-0">
                                <div class="daily-progress-fill" style="width: {{ $progressPercent }}%;"></div>
                            </div>
                            <div class="daily-stats">
                                <div class="daily-stat-item">
                                    <i class="fas fa-check-circle text-primary"></i>
                                    <span class="daily-stat-number">{{ $todayCompletedCount }}/{{ $totalTodayCount }}</span>
                                    <span>Selesai</span>
                                </div>
                                @if ($backlogCount > 0)
                                    <div class="daily-stat-item">
                                        <i class="fas fa-clock text-warning"></i>
                                        <span class="daily-stat-number">{{ $backlogCount }}</span>
                                        <span>Tertinggal</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="daily-section-header">
                            <span class="daily-section-title">Checklist Hari Ini</span>
                            <span class="daily-section-badge today">{{ $totalTodayCount }}</span>
                        </div>
                        <div class="daily-list">
                            @forelse ($dailyTasks as $task)
                                @php
                                    $log = $todayLogs->get($task->id);
                                    $isCompleted = $log && strtolower($log->job_status ?? '') === 'completed';
                                @endphp
                                <div class="daily-item today {{ $isCompleted ? 'completed' : '' }}">
                                    <div>
                                        <div class="daily-title">{{ $task->job_description }}</div>
                                        <div class="daily-status">Status: {{ $isCompleted ? 'completed' : 'pending' }}</div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input type="hidden" name="daily_status_by_date[{{ $today }}][{{ $task->id }}]" value="pending">
                                        <input class="form-check-input" type="checkbox"
                                            name="daily_status_by_date[{{ $today }}][{{ $task->id }}]" value="completed"
                                            id="daily-{{ $today }}-{{ $task->id }}" {{ $isCompleted ? 'checked' : '' }}>
                                        <label class="form-check-label" for="daily-{{ $today }}-{{ $task->id }}">Selesai</label>
                                    </div>
                                </div>
                            @empty
                                <div class="pu-empty">Belum ada daily checklist.</div>
                            @endforelse
                        </div>

                        @if ($backlogEntries->isNotEmpty())
                            <div class="daily-section-header mt-4">
                                <span class="daily-section-title">Belum Dicentang (2 Hari Terakhir)</span>
                                <span class="daily-section-badge backlog">{{ $backlogCount }}</span>
                            </div>
                            <div class="daily-list">
                                @foreach ($backlogEntries as $entry)
                                    @php
                                        $entryDateLabel = \Illuminate\Support\Carbon::parse($entry->work_date)->format('d M');
                                        $daysAgo = \Illuminate\Support\Carbon::parse($entry->work_date)->diffInDays(now());
                                    @endphp
                                    <div class="daily-item backlog">
                                        <div>
                                            <div class="daily-title">{{ $entry->job_description }}</div>
                                            <div class="daily-status">Tanggal: {{ $entryDateLabel }} Â· Status: pending</div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input type="hidden" name="daily_status_by_date[{{ $entry->work_date }}][{{ $entry->task_id }}]" value="pending">
                                            <input class="form-check-input" type="checkbox"
                                                name="daily_status_by_date[{{ $entry->work_date }}][{{ $entry->task_id }}]" value="completed"
                                                id="daily-{{ $entry->work_date }}-{{ $entry->task_id }}">
                                            <label class="form-check-label" for="daily-{{ $entry->work_date }}-{{ $entry->task_id }}">Selesai</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($dailyTasks->isNotEmpty())
                            <div class="mt-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="daily-save-button">
                                    <span class="daily-save-text">Simpan Checklist</span>
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        @push('js')
            <script>
                const dailyChecklistForm = document.getElementById('daily-checklist-form');
                if (dailyChecklistForm) {
                    dailyChecklistForm.addEventListener('submit', function() {
                        const saveButton = document.getElementById('daily-save-button');
                        if (!saveButton) {
                            return;
                        }

                        const saveText = saveButton.querySelector('.daily-save-text');
                        saveButton.disabled = true;
                        if (saveText) {
                            saveText.textContent = 'Menyimpan...';
                        }
                    });
                }

                const toastNotification = document.getElementById('toast-notification');
                if (toastNotification) {
                    setTimeout(() => {
                        toastNotification.style.animation = 'slideIn 0.3s ease reverse';
                        setTimeout(() => {
                            toastNotification.style.display = 'none';
                        }, 300);
                    }, 3000);
                }
            </script>
        @endpush
    @else
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-{{ $colomh > 1 ? '4' : '3' }}">
                <div class="row mb-2 mx-1">
                    <div class="card" style="min-height: 650px;">
                        <div class="card-header">
                            <h5 class="mb-0">List {{ $title_menu }}</h5>
                        </div>
                        <hr class="horizontal dark mt-0">
                        <div class="row px-4 py-2">
                            <div class="table-responsive">
                                <table class="table display" id="list_header">
                                    <thead class="thead-light" style="background-color: #00b7bd4f;">
                                        <tr>
                                            {{-- retrieve table header --}}
                                            @foreach ($table_header_h as $header_h)
                                                <th>{{ $header_h->alias }}</th>
                                            @endforeach
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- retrieve table detail --}}
                                        @foreach ($table_detail_h as $detail_h)
                                            @php
                                                $primary = '';
                                                foreach ($table_primary_h as $p) {
                                                    $primary == ''
                                                        ? ($primary = $detail_h->{$p->field})
                                                        : ($primary = $primary . ':' . $detail_h->{$p->field});
                                                }
                                            @endphp
                                            <tr>
                                                @foreach ($table_header_h as $field_h)
                                                    @php
                                                        $string = $field_h->field;
                                                    @endphp
                                                    {{-- field type join --}}
                                                    @if ($field_h->type == 'join')
                                                        <td
                                                            class="text-sm font-weight-{{ $field_h->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            @if ($field_h->query != '')
                                                                @php
                                                                    $query =
                                                                        $field_h->query .
                                                                        "'" .
                                                                        $detail_h->$string .
                                                                        "'";
                                                                    $data_query = DB::select($query);
                                                                @endphp
                                                                @foreach ($data_query as $q)
                                                                    <?php $sAsArray = array_values((array) $q); ?>
                                                                    {{ $sAsArray[0] != '' ? $sAsArray[0] : '' }}
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        {{-- field type enum --}}
                                                    @elseif ($field_h->type == 'enum')
                                                        <td
                                                            class="text-sm font-weight-{{ $field_h->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            @if ($field_h->query != '')
                                                                @php
                                                                    $data_query = DB::select($field_h->query);
                                                                @endphp
                                                                @foreach ($data_query as $q)
                                                                    <?php $sAsArray = array_values((array) $q); ?>
                                                                    {{ $detail_h->$string == $sAsArray[0] ? $sAsArray[1] : '' }}
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td
                                                            class="text-sm font-weight-{{ $field_h->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            {{ $detail_h->$string }}</td>
                                                    @endif
                                                @endforeach
                                                <td class="text-sm font-weight-normal">
                                                    {{-- button detail --}}
                                                    <button type="button" class="btn btn-primary mb-0 py-1 px-3"
                                                        title="View Data"
                                                        onclick="detail('{{ encrypt($primary) }}','{{ $gmenuid }}','{{ $dmenu }}','{{ $detail_h->$string }}')">
                                                        <i class="fas fa-info-circle"></i> </i><span
                                                            class="font-weight-bold">
                                                            Detail</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-{{ $colomh > 1 ? '8' : '9' }}">
                <div class="row mx-1">
                    <div class="card" style="min-height: 650px;">
                        <div class="card-header">
                            <h5 class="mb-0" id="label_detail">List Detail</h5>
                        </div>
                        <hr class="horizontal dark mt-0">
                        {{-- alert --}}
                        @include('components.alert')
                        <div class="row px-4 py-2">
                            <div class="table-responsive">
                                <table class="table display" id="list_detail">
                                    <thead class="thead-light" style="background-color: #00b7bd4f;">
                                        <tr>
                                            <th>Action</th>
                                            {{-- retrieve table header --}}
                                            @foreach ($table_header_d as $header_d)
                                                <th>{{ $header_d->alias }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- retrieve table detail --}}
                                        @foreach ($table_detail_d as $detail)
                                            @php
                                                $primary = '';
                                                foreach ($table_primary_h as $h) {
                                                    $primary == ''
                                                        ? ($primary = $detail->{$h->field})
                                                        : ($primary = $primary . ':' . $detail->{$h->field});
                                                }
                                                foreach ($table_primary_d as $p) {
                                                    $primary == ''
                                                        ? ($primary = $detail->{$p->field})
                                                        : ($primary = $primary . ':' . $detail->{$p->field});
                                                }
                                            @endphp
                                            <tr {{ ($detail->isactive ?? $detail->is_active ?? '1') == '0' ? 'style=background-color:#ffe9ed;' : '' }}>
                                                <td class="text-sm font-weight-normal">
                                                    <button type="submit" class="btn btn-primary mb-0 py-1 px-2"
                                                        title="View Data"
                                                        onclick="window.location='{{ url($url_menu . '/show' . '/' . encrypt($primary)) }}'">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    {{-- check authorize edit --}}
                                                    @if ($authorize->edit == '1')
                                                        {{-- button edit --}}
                                                        <button type="button" class="btn btn-warning mb-0 py-1 px-2"
                                                            title="Edit Data"
                                                            onclick="window.location='{{ url($url_menu . '/edit' . '/' . encrypt($primary)) }}'">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    @endif
                                                    {{-- check authorize delete --}}
                                                    @if ($authorize->delete == '1')
                                                        <form
                                                            onsubmit="return deleteData(event,'{{ $primary }}','Hapus')"
                                                            action="{{ url($url_menu . '/' . encrypt($primary)) }}"
                                                            method="POST" style="display: inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            {{-- button delete --}}
                                                            <button type="submit" class="btn btn-danger mb-0 py-1 px-2"
                                                                title="Hapus Data">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                                {{-- retrieve table detail --}}
                                                @foreach ($table_header_d as $field)
                                                    @php
                                                        $string = $field->field;
                                                    @endphp
                                                    {{-- field type enum --}}
                                                    @if ($field->type == 'enum')
                                                        <td
                                                            class="text-sm font-weight-{{ $field->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            @if ($field->query != '')
                                                                @php
                                                                    $data_query = DB::select($field->query);
                                                                @endphp
                                                                @foreach ($data_query as $q)
                                                                    <?php $sAsArray = array_values((array) $q); ?>
                                                                    {{ $detail->$string == $sAsArray[0] ? $sAsArray[1] : '' }}
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        {{-- field type file --}}
                                                    @elseif ($field->type == 'file')
                                                        <td
                                                            class="text-sm font-weight-{{ $field->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            @if ($detail->$string)
                                                                <a target="_blank"
                                                                    class="btn btn-sm btn-outline-success mb-0 py-1 px-2"
                                                                    href="{{ asset('/storage' . '/' . $detail->$string) }}">
                                                                    <i aria-hidden="true" class="fas fa-file-lines text-lg">
                                                                    </i>
                                                                    {{ $field->alias }}</a>
                                                            @endif
                                                        </td>
                                                        {{-- field type image --}}
                                                    @elseif($field->type == 'image')
                                                        <td
                                                            class="text-sm font-weight-{{ $field->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            <span class="my-2 text-xs">
                                                                <img src="{{ asset('/storage' . '/' . $detail->$string) }}"
                                                                    alt="image" style="height: 35px;"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#imageModal{{ $field->field }}">
                                                            </span>
                                                            <span
                                                                style="display: none;">{{ asset('/storage' . '/' . $detail->$string) }}
                                                            </span>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="imageModal{{ $field->field }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="imageModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="imageModalLabel">
                                                                                Preview Image
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <img src="{{ asset('/storage' . '/' . $detail->$string) }}"
                                                                                id="preview" alt="image"
                                                                                class="w-100 border-radius-lg shadow-sm">
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        {{-- field type join --}}
                                                    @elseif($field->type == 'join')
                                                        <td
                                                            class="text-sm font-weight-{{ $field->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            @if ($field->query != '')
                                                                @php
                                                                    $query =
                                                                        $field->query . "'" . $detail->$string . "'";
                                                                    $data_query = DB::select($query);
                                                                @endphp
                                                                @foreach ($data_query as $q)
                                                                    <?php $sAsArray = array_values((array) $q); ?>
                                                                    @if ($field->default != 'image')
                                                                        {{ $sAsArray[0] != '' ? $sAsArray[0] : '' }}
                                                                    @else
                                                                        <span class="my-2 text-xs">
                                                                            <img src="{{ asset('/storage' . '/' . $sAsArray[0]) }}"
                                                                                alt="image" style="height: 40px;"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#imageModalJoin{{ $primary }}">
                                                                        </span>
                                                                        <span
                                                                            style="display: none;">{{ asset('/storage' . '/' . $sAsArray[0]) }}
                                                                        </span>
                                                                        <!-- Modal -->
                                                                        <div class="modal fade"
                                                                            id="imageModalJoin{{ $primary }}"
                                                                            tabindex="-1" role="dialog"
                                                                            aria-labelledby="imageModalJoinLabel"
                                                                            aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered"
                                                                                role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="imageModalJoinLabel">
                                                                                            Preview Image
                                                                                        </h5>
                                                                                        <button type="button"
                                                                                            class="btn-close"
                                                                                            data-bs-dismiss="modal"
                                                                                            aria-label="Close">
                                                                                            <span
                                                                                                aria-hidden="true">&times;</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img src="{{ asset('/storage' . '/' . $sAsArray[0]) }}"
                                                                                            id="preview" alt="image"
                                                                                            class="w-100 border-radius-lg shadow-sm">
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        {{-- field type currency --}}
                                                    @elseif ($field->type == 'currency')
                                                        <td
                                                            class="text-sm font-weight-{{ $field->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            {{ $format->CurrencyFormat($detail->$string, $field->decimals, $field->sub) }}
                                                        </td>
                                                    @else
                                                        <td
                                                            class="text-sm font-weight-{{ $field->primary == '1' ? 'bold text-dark' : 'normal' }}">
                                                            {{ $detail->$string }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
@endsection
@push('js')
    <script>
        let columnAbjad = '';
        $(document).ready(function() {
            let numColumns = $('#list_{{ $dmenu }}').DataTable().columns().count();
            let columnNames = '';

            for (let index = 0; index < numColumns; index++) {
                columnNames = $('#list_{{ $dmenu }}').DataTable().columns(index).header()[0].textContent;
                if (columnNames == 'Status' || columnNames == 'status') {
                    columnAbjad = String.fromCharCode(65 + index);
                }
            }
        });
        //set table header into datatables
        $('#list_header').DataTable({
            "language": {
                "search": "Cari :",
                "lengthMenu": "Tampilkan _MENU_ baris",
                "zeroRecords": "Maaf - Data tidak ada",
                "info": "Data _START_ - _END_ dari _TOTAL_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(pencarian dari _MAX_ data)"
            },
            "pageLength": 15,
            responsive: true,
            dom: 'frtip'
        });
        //set table detail into datatables
        $('#list_detail').DataTable({
            "language": {
                "search": "Cari :",
                "lengthMenu": "Tampilkan _MENU_ baris",
                "zeroRecords": "Maaf - Data tidak ada",
                "info": "Data _START_ - _END_ dari _TOTAL_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(pencarian dari _MAX_ data)"
            },
            "pageLength": 15,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
                    text: '<i class="fas fa-plus me-1 text-lg btn-add"> </i><span class="font-weight-bold"> Tambah'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel me-1 text-lg text-success"> </i><span class="font-weight-bold"> Excel',
                    autoFilter: true,
                    sheetName: 'Exported data',
                    // title: 'Nama File Excel',
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        // Loop over the cells in column
                        sheet.querySelectorAll('row c[r^="' + columnAbjad + '"]').forEach((row) => {
                            // Get the value
                            let cell = row.querySelector('is t');
                            if (cell && cell.textContent === 'Not Active') {
                                row.setAttribute('s', '10'); //red background
                            }
                        });
                    },
                    exportOptions: {
                        @push('js')
                            @if ($dmenu != 'trreq' && $dmenu != 'trhrd')
                                <script>
                                    let columnAbjad = '';
                                    $(document).ready(function() {
                                        let numColumns = $('#list_{{ $dmenu }}').DataTable().columns().count();
                                        let columnNames = '';

                                        for (let index = 0; index < numColumns; index++) {
                                            columnNames = $('#list_{{ $dmenu }}').DataTable().columns(index).header()[0].textContent;
                                            if (columnNames == 'Status' || columnNames == 'status') {
                                                columnAbjad = String.fromCharCode(65 + index);
                                            }
                                        }
                                    });
                                    //set table header into datatables
                                    $('#list_header').DataTable({
                                        "language": {
                                            "search": "Cari :",
                                            "lengthMenu": "Tampilkan _MENU_ baris",
                                            "zeroRecords": "Maaf - Data tidak ada",
                                            "info": "Data _START_ - _END_ dari _TOTAL_",
                                            "infoEmpty": "Tidak ada data",
                                            "infoFiltered": "(pencarian dari _MAX_ data)"
                                        },
                                        "pageLength": 15,
                                        responsive: true,
                                        dom: 'frtip'
                                    });
                                    //set table detail into datatables
                                    $('#list_detail').DataTable({
                                        "language": {
                                            "search": "Cari :",
                                            "lengthMenu": "Tampilkan _MENU_ baris",
                                            "zeroRecords": "Maaf - Data tidak ada",
                                            "info": "Data _START_ - _END_ dari _TOTAL_",
                                            "infoEmpty": "Tidak ada data",
                                            "infoFiltered": "(pencarian dari _MAX_ data)"
                                        },
                                        "pageLength": 15,
                                        responsive: true,
                                        dom: 'Bfrtip',
                                        buttons: [{
                                                text: '<i class="fas fa-plus me-1 text-lg btn-add"> </i><span class="font-weight-bold"> Tambah'
                                            },
                                            {
                                                extend: 'excelHtml5',
                                                text: '<i class="fas fa-file-excel me-1 text-lg text-success"> </i><span class="font-weight-bold"> Excel',
                                                autoFilter: true,
                                                sheetName: 'Exported data',
                                                // title: 'Nama File Excel',
                                                customize: function(xlsx) {
                                                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                                    // Loop over the cells in column
                                                    sheet.querySelectorAll('row c[r^="' + columnAbjad + '"]').forEach((row) => {
                                                        // Get the value
                                                        let cell = row.querySelector('is t');
                                                        if (cell && cell.textContent === 'Not Active') {
                                                            row.setAttribute('s', '10'); //red background
                                                        }
                                                    });
                                                },
                                                exportOptions: {
                                                    columns: ':visible:not(:last-child)'
                                                },
                                            },
                                            {
                                                extend: 'pdfHtml5',
                                                text: '<i class="fas fa-file-pdf me-1 text-lg text-danger"> </i><span class="font-weight-bold"> PDF',
                                                exportOptions: {
                                                    columns: ':visible:not(:last-child)'
                                                },
                                            },
                                            {
                                                extend: 'print',
                                                text: '<i class="fas fa-print me-1 text-lg text-info"> </i><span class="font-weight-bold"> Print',
                                                exportOptions: {
                                                    columns: ':visible:not(:last-child)'
                                                },
                                            },
                                        ]
                                    });
                                    //set color button datatables
                                    $('.dt-button').addClass('btn btn-secondary');
                                    $('.dt-button').removeClass('dt-button');
                                    //setting button add
                                    var id = "{{ Session::has('idtrans') ? encrypt(Session::get('idtrans')) : '' }}";
                                    var btnadd = $('.btn-add').parents('.btn-secondary');
                                    btnadd.removeClass('btn-secondary');
                                    btnadd.addClass('btn btn-primary');
                                    btnadd.attr('onclick', "window.location='{{ URL::to($url_menu . '/add/') }}" + "/" + id +
                                        "'");
                                    //check authorize button datatables
                                    <?= $authorize->add == '0' ? 'btnadd.remove();' : '' ?>
                                    <?= $authorize->excel == '0' ? "$('.buttons-excel').remove();" : '' ?>
                                    <?= $authorize->pdf == '0' ? "$('.buttons-pdf').remove();" : '' ?>
                                    <?= $authorize->print == '0' ? "$('.buttons-print').remove();" : '' ?>
                                    // function detail ajax
                                    function detail(id, gmenu, dmenu) {
                                        $.ajax({
                                            url: "{{ url($url_menu) . '/ajax' }}",
                                            type: "GET",
                                            dataType: "JSON",
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            data: {
                                                id: id,
                                                gmenu: gmenu,
                                                dmenu: dmenu
                                            },
                                            success: function(data) {
                                                // set title detail
                                                $('#label_detail').text('List Detail -> ' + data['ajaxid']);
                                                // reset datatables
                                                $('#list_detail').DataTable().destroy();
                                                $('#list_detail tbody').remove();
                                                // retrieve data into tabel
                                                $('#list_detail').append('<tbody></tbody>');
                                                var i = 1;
                                                var result = eval(data['table_detail_d_ajax']);
                                                //looping data detail
                                                for (var index in result) {
                                                    var result1 = eval(data['table_primary_d_ajax']);
                                                    var result2 = eval(data['table_header_d_ajax']);
                                                    var primary = '';
                                                    for (var index1 in result1) {
                                                        // initialized primary key
                                                        primary == '' ? (primary = result[index][result1[index1].field]) : (primary =
                                                            primary + ':' + result[index][result1[index1].field]);
                                                    }
                                                    // set variable columns
                                                    var vtd = `
                                                                <td class="text-sm font-weight-normal">
                                                                    <button type="submit" class="btn btn-primary mb-0 py-1 px-2"
                                                                        title="View Data"
                                                                        onclick="window.location='{{ url($url_menu . '/show/') }}` + '/' + data[
                                                            'encrypt_primary'][
                                                            index
                                                        ] + `'">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    {{-- check authorize edit --}}
                                                                    @if ($authorize->edit == '1')
                                                                        {{-- button edit --}}
                                                                        <button type="button" class="btn btn-warning mb-0 py-1 px-2"
                                                                            title="Edit Data"
                                                                            onclick="window.location='{{ url($url_menu . '/edit/') }}` + '/' +
                                                        data[
                                                            'encrypt_primary'][
                                                            index
                                                        ] + `'">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                    @endif
                                                                    {{-- check authorize delete --}}
                                                                    @if ($authorize->delete == '1')
                                                                        <form onsubmit="return deleteData(event,'` + primary + `','Hapus')"
                                                                            action="{{ url($url_menu . '/') }}` + '/' + data['encrypt_primary'][
                                                            index
                                                        ] + `"
                                                                            method="POST" style="display: inline">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-danger mb-0 py-1 px-2"
                                                                                title="Hapus Data">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>
                                                                `;
                                                    for (var index2 in result2) {
                                                        var primary_color = result2[index2].primary == '1' ? 'bold text-dark' : 'normal';
                                                        var vtex = result[index][result2[index2].field];
                                                        if (result2[index2].type == 'enum') {
                                                            if (result2[index2].query != '') {
                                                                var data_enum = eval(data[result2[index2].field]);
                                                                for (var index3 in data_enum) {
                                                                    if (result[index][result2[index2].field] == data_enum[index3].value) {
                                                                        vtex = data_enum[index3].name;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        vtd += `
                                                            <td class="text-sm font-weight-` + primary_color + `">` + vtex + `</td>
                                                        `;
                                                    }
                                                    // set row data in table
                                                    $('#list_detail tbody').append('<tr>' + vtd + '</tr>');
                                                }
                                                // reset datatables
                                                $('#list_detail').DataTable({
                                                    "language": {
                                                        "search": "Cari :",
                                                        "lengthMenu": "Tampilkan _MENU_ baris",
                                                        "zeroRecords": "Maaf - Data tidak ada",
                                                        "info": "Data _START_ - _END_ dari _TOTAL_",
                                                        "infoEmpty": "Tidak ada data",
                                                        "infoFiltered": "(pencarian dari _MAX_ data)"
                                                    },
                                                    "pageLength": 15,
                                                    responsive: true,
                                                    dom: 'Bfrtip',
                                                    buttons: [{
                                                            text: '<i class="fas fa-plus me-1 text-lg btn-add"> </i><span class="font-weight-bold"> Tambah'
                                                        },
                                                        {
                                                            extend: 'excelHtml5',
                                                            text: '<i class="fas fa-file-excel me-1 text-lg text-success"> </i><span class="font-weight-bold"> Excel',
                                                            autoFilter: true,
                                                            sheetName: 'Exported data',
                                                            // title: 'Nama File Excel',
                                                            customize: function(xlsx) {
                                                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                                                // Loop over the cells in column
                                                                sheet.querySelectorAll('row c[r^="' + columnAbjad + '"]').forEach((row) => {
                                                                    // Get the value
                                                                    let cell = row.querySelector('is t');
                                                                    if (cell && cell.textContent === 'Not Active') {
                                                                        row.setAttribute('s', '10'); //red background
                                                                    }
                                                                });
                                                            },
                                                            exportOptions: {
                                                                columns: ':visible:not(:last-child)'
                                                            },
                                                        },
                                                        {
                                                            extend: 'pdfHtml5',
                                                            text: '<i class="fas fa-file-pdf me-1 text-lg text-danger"> </i><span class="font-weight-bold"> PDF',
                                                            exportOptions: {
                                                                columns: ':visible:not(:last-child)'
                                                            },
                                                        },
                                                        {
                                                            extend: 'print',
                                                            text: '<i class="fas fa-print me-1 text-lg text-info"> </i><span class="font-weight-bold"> Print',
                                                            exportOptions: {
                                                                columns: ':visible:not(:last-child)'
                                                            },
                                                        },
                                                    ]
                                                });
                                                //set color button datatables
                                                $('.dt-button').addClass('btn btn-secondary');
                                                $('.dt-button').removeClass('dt-button');
                                                //setting button add
                                                var id = "{{ Session::has('idtrans') ? encrypt(Session::get('idtrans')) : '' }}";
                                                var btnadd = $('.btn-add').parents('.btn-secondary');
                                                btnadd.removeClass('btn-secondary');
                                                btnadd.addClass('btn btn-primary');
                                                btnadd.attr('onclick', "window.location='{{ URL::to($url_menu . '/add/') }}" +
                                                    "/" + id + "'");
                                                //check authorize button datatables
                                                <?= $authorize->add == '0' ? 'btnadd.remove();' : '' ?>
                                                <?= $authorize->excel == '0' ? "$('.buttons-excel').remove();" : '' ?>
                                                <?= $authorize->pdf == '0' ? "$('.buttons-pdf').remove();" : '' ?>
                                                <?= $authorize->print == '0' ? "$('.buttons-print').remove();" : '' ?>
                                            }
                                        })
                                    }
                                </script>
                            @endif
                        @endpush
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print me-1 text-lg text-info"> </i><span class="font-weight-bold"> Print',
                                exportOptions: {
                                    columns: ':visible:not(:last-child)'
                                },
                            },
                        ]
                    }).draw();
                    //set color button datatables
                    $('.dt-button').addClass('btn btn-secondary');
                    $('.dt-button').removeClass('dt-button');
                    //setting button add
                    var btnadd = $('.btn-add').parents('.btn-secondary');
                    btnadd.removeClass('btn-secondary');
                    btnadd.addClass('btn btn-primary');
                    btnadd.attr('onclick', "window.location='{{ URL::to($url_menu . '/add/') }}" + "/" +
                        id +
                        "'");
                    //check authorize button datatables
                    <?= $authorize->add == '0' ? 'btnadd.remove();' : '' ?>
                    <?= $authorize->excel == '0' ? "$('.buttons-excel').remove();" : '' ?>
                    <?= $authorize->pdf == '0' ? "$('.buttons-pdf').remove();" : '' ?>
                    <?= $authorize->print == '0' ? "$('.buttons-print').remove();" : '' ?>
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        title: 'Sorry!',
                        text: 'Error Get data!',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        }
        //function delete
        function deleteData(event, name, msg) {
            event.preventDefault(); // Prevent default form submission
            Swal.fire({
                title: 'Konfirmasi',
                text: `Apakah Anda Yakin ${msg} Data ${name} ini?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Ya, ${msg}`,
                cancelButtonText: 'Batal',
                confirmButtonColor: '#028284'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Find the closest form element and submit it manually
                    event.target.closest('form').submit();
                }
            });
        }
        // function currency
        function currencyFormat(nominal, decimal = 0, prefix = 'Rp.') {
            return prefix + ' ' + parseFloat(nominal).toFixed(decimal).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    </script>
@endpush




