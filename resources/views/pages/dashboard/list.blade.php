@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => ''])
    @php
        $user = $user_login ?? auth()->user();
        $fullName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''));
        $candidateName = $fullName !== '' ? $fullName : ($user->username ?? 'User');
        $userName = is_numeric($candidateName) ? ($user->username ?? 'User') : $candidateName;
        $userId = $user->id ?? null;
        $sessionUsername = strtolower(trim((string) (session('username') ?? '')));
        $modelUsername = strtolower(trim((string) ($user->username ?? '')));
        $usernameFromId = $userId
            ? strtolower((string) (\Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->value('username') ?? ''))
            : '';
        $userLogin = $sessionUsername !== '' && !is_numeric($sessionUsername)
            ? $sessionUsername
            : ($modelUsername !== '' && !is_numeric($modelUsername)
                ? $modelUsername
                : $usernameFromId);
        $roleTokens = array_filter(array_map('trim', explode(',', strtolower($user->idroles ?? ''))));
        $isPrivilegedRole = count(array_intersect($roleTokens, ['admins', 'admin', 'hrdxxx', 'hrd', 'headxx'])) > 0;

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

        $isVisibleOnDashboard = function ($item) use ($isPrivilegedRole, $userId, $userLogin, $hrdUserIds, $hrdUsernames) {
            $uid = isset($item->user_id) ? (string) $item->user_id : '';
            $uidInt = is_numeric($uid) ? (int) $uid : null;
            $createdBy = strtolower((string) ($item->user_create ?? ''));
            $isHrd = ($uidInt !== null && in_array($uidInt, $hrdUserIds, true))
                || ($uid !== '' && !is_numeric($uid) && in_array(strtolower($uid), $hrdUsernames, true))
                || ($createdBy !== '' && in_array($createdBy, $hrdUsernames, true));
            $matchedUserId = $userId && $uidInt !== null && $uidInt === (int) $userId;
            $matchedUserCreate = $userLogin !== '' && $createdBy === $userLogin;

            if ($isPrivilegedRole) {
                return true;
            }

            if ($isHrd) {
                return false;
            }

            if ($matchedUserId) {
                return true;
            }

            if ($matchedUserCreate) {
                return true;
            }

            return false;
        };
        
        // Get real data from pl_non_periodic table
        $allRequests = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
            ->when(!$isPrivilegedRole && ($userId || $userLogin), function ($query) use ($userId, $userLogin) {
                $query->where(function ($inner) use ($userId, $userLogin) {
                    if ($userId) {
                        $inner->where('user_id', $userId);
                    }
                    if ($userLogin) {
                        $inner->orWhere('user_create', $userLogin);
                    }
                });
            })
            ->where(function($query) {
                $query->where('is_active', 1)
                      ->orWhereNull('is_active');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($item) use ($isVisibleOnDashboard) {
                return $isVisibleOnDashboard($item);
            })
            ->values();
        
        // Calculate summary statistics
        $totalRequests = $allRequests->count();
        
        // VERIFIKASI: pekerjaan selesai oleh petugas dan menunggu verifikasi user
        $verifikasiRequests = $allRequests->filter(function($item) {
            $status = strtolower(trim($item->request_status ?? ''));
            return in_array($status, ['verifikasi', 'review', 'pending', 'approved']) && empty($item->hrd_approval_date);
        })->count();
        
        // PENDING: status review/pending/approved yang belum di-approve HRD
        $pendingRequests = $allRequests->filter(function($item) {
            $status = strtolower(trim($item->request_status ?? ''));
            return in_array($status, ['review', 'pending', 'approved']) && empty($item->hrd_approval_date);
        })->count();

        $inProgressRequests = $allRequests->filter(function($item) {
            $status = strtolower(trim($item->request_status ?? ''));
            $reviewApproved = in_array($status, ['review', 'approved']) && !empty($item->hrd_approval_date);
            $inProgress = in_array($status, ['pengadaan', 'pengerjaan', 'revisi']);
            return $reviewApproved || $inProgress;
        })->count();
        
        $completedRequests = $allRequests->filter(function($item) {
            $status = strtolower(trim($item->request_status ?? ''));
            return $status === 'completed';
        })->count();
        
        $stats = [
            [
                'label' => 'Total Permintaan',
                'value' => $totalRequests,
                'icon' => 'ni-collection',
                'class' => 'primary',
                'desc' => 'Semua permintaan',
            ],
            [
                'label' => 'Menunggu Verifikasi',
                'value' => $verifikasiRequests,
                'icon' => 'ni-time-alarm',
                'class' => 'warning',
                'desc' => 'Belum ditinjau',
            ],
            [
                'label' => 'Ditugaskan',
                'value' => $inProgressRequests,
                'icon' => 'ni-settings-gear-65',
                'class' => 'info',
                'desc' => 'Sedang dalam pengerjaan',
            ],
            [
                'label' => 'Selesai',
                'value' => $completedRequests,
                'icon' => 'ni-check-bold',
                'class' => 'success',
                'desc' => 'Pekerjaan selesai',
            ],
        ];
        
        // Calculate percentage for status bars
        $total = $totalRequests > 0 ? $totalRequests : 1;
        $statusBars = [
            ['label' => 'Menunggu Verifikasi', 'value' => round(($verifikasiRequests / $total) * 100), 'class' => 'warning'],
            ['label' => 'Ditugaskan', 'value' => round(($inProgressRequests / $total) * 100), 'class' => 'info'],
            ['label' => 'Selesai', 'value' => round(($completedRequests / $total) * 100), 'class' => 'success'],
        ];
        
        // Get most frequent area
        $mostFrequentArea = $allRequests->groupBy('area_id')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->keys()
            ->first();
        
        // Try to get area name from ms_area table, fallback to area_id if not found
        $areaName = '-';
        if ($mostFrequentArea) {
            $areaFromTable = \Illuminate\Support\Facades\DB::table('ms_area')
                ->where('id', $mostFrequentArea)
                ->value('nama_area');
            
            // If not found in table, use the area_id value directly (might be a string like "Kantor")
            $areaName = $areaFromTable ?? $mostFrequentArea;
        }
        
        // Get most common work type
        $internalCount = $allRequests->where('work_type', 'internal')->count();
        $vendorCount = $allRequests->where('work_type', 'vendor')->count();
        $workTypeValue = $internalCount > $vendorCount ? 'Perbaikan AC dan listrik' : 'Perbaikan AC dan listrik';
        
        // Vendor status
        $vendorStatus = $internalCount > $vendorCount ? 'Vendor Internal' : 'Vendor External';
        
        $highlights = [
            ['label' => 'Area paling sering', 'value' => $areaName],
            ['label' => 'Jenis pekerjaan', 'value' => $workTypeValue],
            ['label' => 'Vendor aktif', 'value' => $vendorStatus],
        ];
        
        // Get recent activities (last 5 requests)
        $recentActivities = $allRequests->take(5);
        $activities = [];
        
        foreach ($recentActivities as $request) {
            $statusText = '';
            $statusClass = '';
            
            $status = strtolower(trim($request->request_status ?? ''));
            
            // Normalize legacy statuses
            if ($status === 'verifikasi') {
                $statusText = 'Menunggu Verifikasi';
                $statusClass = 'bg-warning';
            } elseif (in_array($status, ['review', 'pending', 'approved'])) {
                $statusText = 'Menunggu Verifikasi';
                $statusClass = 'bg-primary';
            } elseif (in_array($status, ['pengadaan', 'pengerjaan'])) {
                $statusText = 'Ditugaskan';
                $statusClass = 'bg-info';
            } elseif ($status === 'completed') {
                $statusText = 'Selesai';
                $statusClass = 'bg-success';
            } elseif (in_array($status, ['reject', 'rejected'])) {
                $statusText = 'Ditolak';
                $statusClass = 'bg-danger';
            } elseif (in_array($status, ['cancel', 'cancelled', 'canceled'])) {
                $statusText = 'Dibatalkan';
                $statusClass = 'bg-secondary';
            } else {
                $statusText = 'Pending';
                $statusClass = 'bg-primary';
            }
            
            $createdDate = \Carbon\Carbon::parse($request->created_at);
            $dateText = '';
            
            if ($createdDate->isToday()) {
                $dateText = 'Hari ini';
            } elseif ($createdDate->isYesterday()) {
                $dateText = 'Kemarin';
            } elseif ($createdDate->diffInDays() < 7) {
                $dateText = floor($createdDate->diffInDays()) . ' hari lalu';
            } elseif ($createdDate->diffInWeeks() < 4) {
                $dateText = floor($createdDate->diffInWeeks()) . ' minggu lalu';
            } else {
                $dateText = $createdDate->format('d M Y');
            }
            
            $activities[] = [
                'title' => $request->job_description ?? 'Permintaan PU',
                'status' => $statusText,
                'status_class' => $statusClass,
                'date' => $dateText,
            ];
        }
    @endphp

    <div class="container-fluid py-4 dashboard-page">
        <div class="row">
            <div class="col-12">
                <div class="card dashboard-hero mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between">
                            <div>
                                <p class="text-xs text-uppercase mb-2">Notifikasi PU</p>
                                <h4 class="mb-2">Selamat datang, {{ $userName }}</h4>
                            </div>
                            <div class="text-lg-end mt-3 mt-lg-0">
                                <span class="badge bg-white text-dark">{{ now()->format('d M Y') }}</span>
                                <div class="mt-2">
                                    <a class="btn btn-sm btn-light" href="{{ url('transc/user') }}">
                                        <i class="ni ni-send me-1"></i>Buat Permintaan
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            @foreach ($stats as $stat)
                                <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-8">
                                                    <p class="text-xs text-uppercase text-secondary mb-1">{{ $stat['label'] }}</p>
                                                    <h5 class="font-weight-bolder mb-1">{{ $stat['value'] }}</h5>
                                                    <p class="mb-0 text-xs text-secondary">{{ $stat['desc'] }}</p>
                                                </div>
                                                <div class="col-4 text-end">
                                                    <div class="icon icon-shape bg-gradient-{{ $stat['class'] }} shadow-{{ $stat['class'] }} text-center rounded-circle">
                                                        <i class="ni {{ $stat['icon'] }} text-lg opacity-10" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card h-100 dashboard-section-card">
                    <div class="card-header pb-0">
                        <h6 class="mb-1">Aktivitas Terbaru</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Permintaan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activities as $activity)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-3 py-2">
                                                    <div>
                                                        <div class="icon icon-shape icon-sm bg-gradient-primary shadow text-center">
                                                            <i class="ni ni-bell-55 text-white opacity-10"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="text-sm mb-0">{{ $activity['title'] }}</h6>
                                                        <p class="text-xs text-secondary mb-0">Permintaan pekerjaan PU</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $activity['status_class'] }}">{{ $activity['status'] }}</span>
                                            </td>
                                            <td class="align-middle text-end">
                                                <span class="text-xs text-secondary">{{ $activity['date'] }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-secondary py-4">Belum ada aktivitas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100 dashboard-section-card">
                    <div class="card-body">
                        <div class="dashboard-visual mb-4">
                            <div class="dashboard-visual-content">
                                <p class="text-xs text-uppercase mb-2">Status Proyek</p>
                                <h5 class="mb-1">PU Monitoring</h5>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="mb-2">Status Bulan Ini</h6>
                            @foreach ($statusBars as $bar)
                                <div class="d-flex justify-content-between text-xs text-secondary mb-1">
                                    <span>{{ $bar['label'] }}</span>
                                    <span>{{ $bar['value'] }}%</span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $bar['class'] }}" role="progressbar" style="width: {{ $bar['value'] }}%"></div>
                                </div>
                            @endforeach
                        </div>

                        <div class="card shadow-none border">
                            <div class="card-body p-3">
                                <h6 class="mb-3">Highlight</h6>
                                @foreach ($highlights as $item)
                                    <div class="d-flex justify-content-between text-sm mb-2">
                                        <span class="text-secondary">{{ $item['label'] }}</span>
                                        <span class="fw-bold text-dark">{{ $item['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
        .dashboard-page {
            background:
                radial-gradient(circle at 10% -10%, rgba(37, 99, 235, 0.08), transparent 35%),
                radial-gradient(circle at 100% 0%, rgba(16, 185, 129, 0.08), transparent 30%);
            border-radius: 18px;
            padding-bottom: 1.25rem;
        }

        .dashboard-hero {
            color: #fff;
            background: linear-gradient(135deg, #0f5fae 0%, #1f7bc2 45%, #22a6a2 100%);
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 12px 28px rgba(15, 95, 174, 0.24);
        }

        .dashboard-hero h4 {
            letter-spacing: 0.2px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }

        .dashboard-hero .card {
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(13, 110, 253, 0.08);
            border-radius: 0.9rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-hero .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.1);
        }

        .dashboard-hero .text-uppercase {
            letter-spacing: 0.45px;
        }

        .dashboard-hero .badge.bg-white {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 999px;
            font-weight: 600;
        }

        .dashboard-hero .text-white-50 {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .dashboard-section-card {
            border: 1px solid #e5eaf2;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        }

        .dashboard-section-card .card-header {
            border-bottom: 1px solid #edf2f7;
        }

        .dashboard-section-card .table tbody tr {
            border-bottom: 1px solid #edf2f7;
        }

        .dashboard-section-card .table tbody tr:nth-child(even) {
            background: rgba(248, 250, 252, 0.65);
        }

        .dashboard-section-card .table tbody tr:hover {
            background: rgba(230, 240, 255, 0.45);
        }

        .dashboard-section-card .badge {
            border-radius: 999px;
            padding: 0.42rem 0.55rem;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .dashboard-visual {
            border-radius: 1rem;
            min-height: 180px;
            padding: 1.5rem;
            color: #fff;
            background: radial-gradient(circle at top left, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0)),
                linear-gradient(135deg, #2563eb, #3b82f6 55%, #0ea5a4);
            display: flex;
            align-items: flex-end;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.2);
        }
        .dashboard-visual-content {
            max-width: 80%;
        }
        .dashboard-visual p {
            color: rgba(255, 255, 255, 0.85);
        }
    </style>
