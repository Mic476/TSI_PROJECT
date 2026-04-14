@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
{{-- section content --}}
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => ''])
    <style>
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
            line-height: 1.2;
        }

        .status-pending {
            background: rgba(59, 130, 246, 0.18);
            color: #1e40af;
        }

        .status-draft {
            background: rgba(100, 116, 139, 0.2);
            color: #334155;
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

        .status-completed {
            background: rgba(99, 102, 241, 0.18);
            color: #4338ca;
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.18);
            color: #b91c1c;
        }

        .status-cancel {
            background: rgba(148, 163, 184, 0.2);
            color: #475569;
        }

        .status-verifikasi {
            background: rgba(168, 85, 247, 0.18);
            color: #7c3aed;
        }

        .status-revisi {
            background: rgba(251, 146, 60, 0.18);
            color: #c2410c;
        }

        /* Image Modal Lightbox */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            animation: fadeIn 0.3s;
        }

        .image-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .image-modal-content {
            max-width: 90%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            animation: zoomIn 0.3s;
        }

        .image-modal-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
            transition: color 0.3s;
        }

        .image-modal-close:hover {
            color: #0ea5e9;
        }

        .image-modal-caption {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes zoomIn {
            from { transform: scale(0.5); }
            to { transform: scale(1); }
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .image-modal-content {
                max-width: 95%;
            }
        }
    </style>
    <div class="container-fluid py-4">
        @php
            // Gunakan data dari controller, atau query langsung jika kosong
            $displayItems = $table_detail_d ?? collect();
            $currentUserId = $user_login->id ?? null;
            $sessionUsername = strtolower(trim((string) (session('username') ?? '')));
            $modelUsername = strtolower(trim((string) ($user_login->username ?? '')));
            $usernameFromId = $currentUserId
                ? strtolower((string) (\Illuminate\Support\Facades\DB::table('users')->where('id', $currentUserId)->value('username') ?? ''))
                : '';
            $currentUsername = $sessionUsername !== '' && !is_numeric($sessionUsername)
                ? $sessionUsername
                : ($modelUsername !== '' && !is_numeric($modelUsername)
                    ? $modelUsername
                    : $usernameFromId);

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

            $isCurrentViewerHrd = ($currentUserId && in_array((int) $currentUserId, $hrdUserIds, true))
                || ($currentUsername !== '' && in_array($currentUsername, $hrdUsernames, true));

            $currentUserRoles = array_map('trim', explode(',', strtolower((string) ($user_login->idroles ?? ''))));
            $isCurrentViewerAdmin = in_array('admins', $currentUserRoles, true) || in_array('admin', $currentUserRoles, true);

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

            $isVisibleForCurrentUser = function ($item) use ($currentUserId, $currentUsername, $isHrdSubmission, $isCurrentViewerHrd, $isCurrentViewerAdmin) {
                if ($isCurrentViewerAdmin) {
                    return true;
                }

                $uid = isset($item->user_id) ? (string) $item->user_id : '';
                $createdBy = strtolower((string) ($item->user_create ?? ''));
                $isHrd = $isHrdSubmission($item);
                $byUserId = $currentUserId && $uid !== '' && is_numeric($uid) && (int) $uid === (int) $currentUserId;
                $byLegacyUserId = $currentUsername !== '' && $uid !== '' && !is_numeric($uid) && strtolower($uid) === $currentUsername;
                $byUserCreate = $currentUsername !== '' && $createdBy === $currentUsername;

                if ($isCurrentViewerHrd && $isHrd && ($byUserId || $byLegacyUserId || $byUserCreate)) {
                    return true;
                }

                if ($isHrd) {
                    return false;
                }

                if ($byUserId) {
                    return true;
                }

                if ($byLegacyUserId) {
                    return true;
                }

                if ($byUserCreate) {
                    return true;
                }

                return false;
            };
            
            // Jika kosong, query langsung dari database
            if ($displayItems->isEmpty()) {
                $displayItems = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                    ->leftJoin('ms_area', 'pl_non_periodic.area_id', '=', 'ms_area.id')
                    ->select('pl_non_periodic.*', 'ms_area.nama_area as area_name')
                    ->orderBy('pl_non_periodic.created_at', 'desc')
                    ->get();
            }

            $displayItems = $displayItems->filter(function ($item) use ($isVisibleForCurrentUser) {
                return $isVisibleForCurrentUser($item);
            })->values();
            
            $statusField = 'request_status';
            
            // VERIFIKASI: pengajuan yang diselesaikan petugas dan perlu verifikasi user
            $verifikasiItems = $displayItems->filter(function ($item) use ($statusField) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                return $statusValue === 'verifikasi';
            });
            $verifikasiCount = $verifikasiItems->count();
            
            // PENDING: status review yang belum ada HRD approval (menunggu approval)
            $pendingCount = $displayItems->filter(function ($item) use ($statusField) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                return in_array($statusValue, ['review', 'pending', 'approved']) && empty($item->hrd_approval_date);
            })->count();
            
            // DALAM PROSES: review yang sudah approved + pengadaan + pengerjaan + revisi
            $progressCount = $displayItems->filter(function ($item) use ($statusField) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                $reviewApproved = in_array($statusValue, ['review', 'approved']) && !empty($item->hrd_approval_date);
                $inProgress = in_array($statusValue, ['pengadaan', 'pengerjaan', 'revisi']);
                return $reviewApproved || $inProgress;
            })->count();
            
            $rejectedCount = $displayItems->filter(function ($item) use ($statusField) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                return in_array($statusValue, ['rejected', 'reject']);
            })->count();
            
            $completedCount = $displayItems->where($statusField, 'completed')->count();
            
            // History items - exclude verifikasi
            $historyItems = $displayItems->filter(function ($item) use ($statusField) {
                $statusValue = strtolower($item->{$statusField} ?? '');
                return $statusValue !== 'verifikasi';
            });
        @endphp
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                        <div>
                            <h6 class="mb-1">Pengajuan Pekerjaan PU</h6>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a class="btn btn-primary mb-0" href="{{ URL::to($url_menu . '/add') }}">
                                <i class="fas fa-plus me-1"></i>Tambah Pengajuan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pengajuan</p>
                                    <h5 class="font-weight-bolder">{{ $displayItems->count() }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-bullet-list-67 text-lg opacity-10" aria-hidden="true"></i>
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
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Menunggu Verifikasi</p>
                                    <h5 class="font-weight-bolder">{{ $verifikasiCount }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="fas fa-clipboard-check text-lg opacity-10" aria-hidden="true"></i>
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
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Dalam Proses</p>
                                    <h5 class="font-weight-bolder">{{ $progressCount }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                    <i class="ni ni-settings text-lg opacity-10" aria-hidden="true"></i>
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
                                    <h5 class="font-weight-bolder">{{ $completedCount }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Verifikasi Section --}}
        @if($verifikasiCount > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card" style="border: 2px solid #f59e0b; box-shadow: 0 4px 20px rgba(245, 158, 11, 0.15);">
                    <div class="card-header border-0 pb-0" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                            <div>
                                <h5 class="mb-1 text-dark">
                                    <i class="fas fa-clipboard-check text-warning me-2"></i>
                                    <strong>Verifikasi Pekerjaan</strong>
                                </h5>
                                <p class="text-sm mb-0 text-muted">Pekerjaan yang diselesaikan petugas dan memerlukan verifikasi Anda</p>
                            </div>
                            <span class="badge bg-gradient-warning text-white px-3 py-2">
                                {{ $verifikasiCount }} Task
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table display" id="list_user_verifikasi">
                                <thead class="thead-light" style="background-color: #00b7bd4f;">
                                    <tr>
                                        <th>Aksi</th>
                                        <th>No</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Area</th>
                                        <th>Deskripsi</th>
                                        <th>Dokumentasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($verifikasiItems as $detail)
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
                                            $dateValue = $detail->created_at ?? $detail->tanggal ?? $detail->date ?? null;
                                            $displayDate = $dateValue
                                                ? \Illuminate\Support\Carbon::parse($dateValue)->format('d-m-Y')
                                                : '-';
                                            
                                            // Get documentation for this non-periodic item
                                            $allDocs = \Illuminate\Support\Facades\DB::table('pl_documentation')
                                                ->where('non_periodic_id', $detail->id)
                                                ->where('is_active', '1')
                                                ->orderBy('created_at', 'desc')
                                                ->get();
                                            
                                            $documentation = $allDocs->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-success btn-sm mb-0 px-2" type="button"
                                                        data-bs-toggle="modal" data-bs-target="#verifyModal"
                                                        data-action="approve" 
                                                        data-non-periodic-id="{{ $detail->id }}"
                                                        data-request-id="{{ $primary }}"
                                                        title="Setujui">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-warning btn-sm mb-0 px-2" type="button"
                                                        data-bs-toggle="modal" data-bs-target="#verifyModal"
                                                        data-action="reject"
                                                        data-non-periodic-id="{{ $detail->id }}"
                                                        data-request-id="{{ $primary }}"
                                                        title="Minta Revisi">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">{{ $loop->iteration }}</span>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">{{ $displayDate }}</span>
                                            </td>
                                            <td>
                                                <span class="text-xs">{{ $detail->area_name ?? $detail->area ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-xs">{{ Str::limit($detail->job_description ?? $detail->description ?? '-', 50) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($allDocs->count() > 0)
                                                    @php
                                                        // Helper function untuk generate URL file
                                                        $getFileUrl = function($filePath) {
                                                            $normalizedPath = trim((string) $filePath);
                                                            $normalizedPath = str_replace('\\', '/', $normalizedPath);

                                                            if ($normalizedPath === '') {
                                                                return '';
                                                            }

                                                            if (preg_match('/^https?:\/\//i', $normalizedPath)) {
                                                                return $normalizedPath;
                                                            }

                                                            $normalizedPath = preg_replace('#^/?public/#', '', $normalizedPath);
                                                            $normalizedPath = preg_replace('#^/?storage/#', '', $normalizedPath);
                                                            $normalizedPath = ltrim($normalizedPath, '/');

                                                            return asset('storage/' . $normalizedPath);
                                                        };
                                                        
                                                        // Check if file is image
                                                        $isImage = function($filePath) {
                                                            $normalizedPath = str_replace('\\', '/', trim((string) $filePath));
                                                            $pathOnly = parse_url($normalizedPath, PHP_URL_PATH) ?? $normalizedPath;
                                                            $ext = strtolower(pathinfo($pathOnly, PATHINFO_EXTENSION));
                                                            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                                                        };
                                                        
                                                        $fileName = $detail->job_description ?? 'Dokumentasi';
                                                    @endphp
                                                    
                                                    {{-- Simple Button untuk buka semua lampiran --}}
                                                    <button type="button" 
                                                            class="btn btn-info btn-sm"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#allDocsModal{{ $detail->id }}">
                                                        <i class="fas fa-paperclip me-1"></i>
                                                        {{ $allDocs->count() }} Lampiran
                                                    </button>
                                                    
                                                    {{-- Modal untuk semua dokumentasi --}}
                                                    <div class="modal fade" id="allDocsModal{{ $detail->id }}" tabindex="-1">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-images me-2"></i>
                                                                        Dokumentasi Pekerjaan
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row g-3">
                                                                        @foreach($allDocs as $doc)
                                                                            @php
                                                                                $docUrl = $getFileUrl($doc->file);
                                                                            @endphp
                                                                            <div class="col-md-6">
                                                                                <div class="card h-100">
                                                                                    @if($isImage($doc->file))
                                                                                        <img src="{{ $docUrl }}" 
                                                                                             class="card-img-top" 
                                                                                             alt="Foto {{ $loop->iteration }}"
                                                                                             style="height: 250px; object-fit: cover; cursor: pointer;"
                                                                                             onclick="openImageModal('{{ $docUrl }}', 'Foto {{ $loop->iteration }}')">
                                                                                    @else
                                                                                        <div class="d-flex align-items-center justify-content-center bg-gradient-primary" 
                                                                                             style="height: 250px;">
                                                                                            <i class="fas fa-file-pdf fa-4x text-white"></i>
                                                                                        </div>
                                                                                    @endif
                                                                                    <div class="card-body">
                                                                                        <p class="text-sm mb-2">
                                                                                            <strong>{{ $doc->description ?? 'Lampiran ' . $loop->iteration }}</strong>
                                                                                        </p>
                                                                                        <a href="{{ $docUrl }}" target="_blank" class="btn btn-primary btn-sm w-100">
                                                                                            <i class="fas fa-external-link-alt me-1"></i>
                                                                                            Buka di Tab Baru
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary text-xs">Belum ada dokumentasi</span>
                                                @endif
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
        @endif

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                            <h6 class="mb-0">Riwayat Pengajuan</h6>
                            <div class="d-flex align-items-center gap-2">
                                <label for="user-status-filter" class="text-sm mb-0">Filter Status</label>
                                <select id="user-status-filter" class="form-select form-select-sm" style="width: 180px;">
                                    <option value="">Semua</option>
                                    <option value="draft">Draft</option>
                                    <option value="pending">Pending</option>
                                    <option value="review">Review</option>
                                    <option value="pengadaan">Pengadaan</option>
                                    <option value="pengerjaan">Pengerjaan</option>
                                    <option value="revisi">Revisi</option>
                                    <option value="completed">Completed</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancel">Cancel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table display" id="list_user_history">
                            <thead class="thead-light" style="background-color: #00b7bd4f;">
                                <tr>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Area</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Catatan Head</th>
                                    <th>Catatan HRD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($historyItems as $detail)
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
                                        $rawStatus = $detail->{$statusField} ?? '';
                                        $status = strtolower($rawStatus);
                                        if (in_array($status, ['approved'], true)) {
                                            $status = 'review';
                                        } elseif ($status === 'reject') {
                                            $status = 'rejected';
                                        } elseif (in_array($status, ['canceled', 'cancelled'], true)) {
                                            $status = 'cancel';
                                        }
                                        $badgeClass = 'status-badge status-' . ($status ?: 'pending');
                                    @endphp
                                    <tr data-status="{{ $status }}">
                                        <td class="text-center">
                                            @if ($primary != '')
                                                <div class="btn-group">
                                                    <button class="btn btn-primary btn-sm mb-0 px-3" type="button"
                                                        title="View Data"
                                                        onclick="window.location='{{ url($url_menu . '/show' . '/' . encrypt($primary)) }}'">
                                                        <i class="fas fa-eye"></i><span class="font-weight-bold"> View</span>
                                                    </button>
                                                    @if ($authorize->edit == '1')
                                                        <button type="button"
                                                            class="btn btn-sm btn-primary mb-0 px-2 dropdown-toggle dropdown-toggle-split"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="visually-hidden">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @if ($status === 'draft')
                                                                <li class="px-2">
                                                                    <button type="button"
                                                                        class="dropdown-item d-flex align-items-center gap-2 text-white rounded-2"
                                                                        style="background-color:#ff6b47;border-color:#ff6b47;"
                                                                        onclick="window.location='{{ url($url_menu . '/edit' . '/' . encrypt($primary)) }}'">
                                                                        <i class="fas fa-edit me-2 text-white"></i>Edit Draft
                                                                    </button>
                                                                </li>
                                                                <li class="px-2 mt-1">
                                                                    <form method="POST" action="{{ route('user.draft.confirm') }}" class="m-0 draft-confirm-form">
                                                                        @csrf
                                                                        <input type="hidden" name="id_encrypt" value="{{ encrypt($detail->id ?? $primary) }}">
                                                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-white rounded-2" style="background-color:#0ea5a5;border-color:#0ea5a5;">
                                                                            <i class="fas fa-paper-plane me-2 text-white"></i>Konfirmasi Draft
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                <li class="px-2 mt-1">
                                                                    <form method="POST" action="{{ route('user.draft.delete') }}" class="m-0 draft-delete-form">
                                                                        @csrf
                                                                        <input type="hidden" name="id_encrypt" value="{{ encrypt($detail->id ?? $primary) }}">
                                                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-white rounded-2" style="background-color:#ef4444;border-color:#ef4444;">
                                                                            <i class="fas fa-trash me-2 text-white"></i>Hapus Draft
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    @endif
                                                </div>
                                            @else
                                                <button class="btn btn-sm btn-secondary mb-0" disabled>Detail</button>
                                            @endif
                                        </td>
                                        <td class="text-sm">{{ $loop->iteration }}</td>
                                        <td class="text-sm">{{ \Carbon\Carbon::parse($detail->created_at)->format('d-m-Y') ?? '-' }}</td>
                                        <td class="text-sm">
                                            {{ $detail->area_name ?? $detail->area ?? $detail->area_id ?? '-' }}
                                        </td>
                                        <td class="text-sm">
                                            {{ $detail->job_description ?? $detail->description ?? '-' }}
                                        </td>
                                        <td class="text-sm">
                                            <span class="{{ $badgeClass }}">{{ $status ?: '-' }}</span>
                                        </td>
                                        <td class="text-sm">
                                            {{ $detail->head_note ?? '-' }}
                                        </td>
                                        <td class="text-sm">
                                            {{ $detail->hrd_note ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-sm text-secondary">Belum ada pengajuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Image Modal Lightbox --}}
        <div id="imageModal" class="image-modal" onclick="closeImageModal()">
            <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
            <img id="imageModalContent" class="image-modal-content" alt="Preview">
            <div id="imageModalCaption" class="image-modal-caption"></div>
        </div>

        {{-- Modal Verifikasi --}}
        <div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('work-schedule.verify') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="verifyModalLabel">Verifikasi Pekerjaan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="non_periodic_id" id="verify-non-periodic-id" value="">
                            <input type="hidden" name="decision" id="verify-decision" value="">
                            <div class="mb-3">
                                <label class="form-label" id="verify-notes-label">Catatan (opsional)</label>
                                <textarea class="form-control" name="notes" id="verify-notes" rows="3" placeholder="Tulis catatan jika diperlukan"></textarea>
                            </div>
                            <div class="mb-3" id="verify-attachment-wrapper" style="display: none;">
                                <label class="form-label" for="verify-attachments">Lampiran Revisi (opsional)</label>
                                <input class="form-control" type="file" name="revision_attachments[]" id="verify-attachments" accept=".jpg,.jpeg,.png,.webp,.jfif,.heic,.heif,.pdf" multiple>
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
                            <button type="submit" class="btn btn-primary" id="verifySubmit">Setujui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            // Image Modal Functions
            function openImageModal(imageUrl, caption) {
                const modal = document.getElementById('imageModal');
                const modalImg = document.getElementById('imageModalContent');
                const modalCaption = document.getElementById('imageModalCaption');
                
                modal.classList.add('active');
                modalImg.src = imageUrl;
                modalCaption.textContent = caption;
                
                // Prevent body scroll when modal is open
                document.body.style.overflow = 'hidden';
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                modal.classList.remove('active');
                
                // Restore body scroll
                document.body.style.overflow = 'auto';
            }

            // Close modal with ESC key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeImageModal();
                }
            });

            // Status filter
            const statusFilter = document.getElementById('user-status-filter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const value = this.value.toLowerCase();
                    document.querySelectorAll('table tbody tr[data-status]').forEach(function(row) {
                        const rowStatus = (row.getAttribute('data-status') || '').toLowerCase();
                        row.style.display = value === '' || rowStatus === value ? '' : 'none';
                    });
                });
            }

            // Verify modal handler
            const verifyModal = document.getElementById('verifyModal');
            if (verifyModal) {
                verifyModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const action = button.getAttribute('data-action') || 'approve';
                    const nonPeriodicId = button.getAttribute('data-non-periodic-id') || '';
                    
                    const modalTitle = document.getElementById('verifyModalLabel');
                    const submitBtn = document.getElementById('verifySubmit');
                    const decisionInput = document.getElementById('verify-decision');
                    const nonPeriodicIdInput = document.getElementById('verify-non-periodic-id');
                    const notesInput = document.getElementById('verify-notes');
                    const notesLabel = document.getElementById('verify-notes-label');
                    const revisionAttachmentWrapper = document.getElementById('verify-attachment-wrapper');
                    const revisionAttachmentInput = document.getElementById('verify-attachments');
                    
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

            document.querySelectorAll('.draft-confirm-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Draft?',
                        text: 'Setelah dikonfirmasi, pengajuan akan masuk ke alur approval dan tidak bisa dihapus sebagai draft.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Konfirmasi',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#0ea5a5'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.draft-delete-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Hapus Draft?',
                        text: 'Draft yang dihapus tidak dapat dikembalikan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#ef4444'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection




