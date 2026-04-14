@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100', 'title_menu' => 'Dashboard HRD'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard', 'title_group' => 'HRD', 'title_menu' => 'Dashboard'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-4">
                            <div class="col">
                                <h2 class="mb-0">
                                    <i class="fas fa-chart-line text-primary"></i> Dashboard HRD
                                </h2>
                            </div>
                        </div>

                        @php
                            // 1. APPROVAL STATISTICS
                            $totalApprovalNeeded = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                                ->where(function($q) {
                                    $q->where('is_active', 1)->orWhereNull('is_active');
                                })
                                ->where('request_status', 'LIKE', '%review%')
                                ->count();

                            $approvedRequests = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                                ->where(function($q) {
                                    $q->where('is_active', 1)->orWhereNull('is_active');
                                })
                                ->whereIn('request_status', ['pengadaan', 'pengerjaan'])
                                ->count();

                            $rejectedRequests = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                                ->where(function($q) {
                                    $q->where('is_active', 1)->orWhereNull('is_active');
                                })
                                ->where('request_status', 'LIKE', '%reject%')
                                ->count();

                            $completedRequests = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                                ->where(function($q) {
                                    $q->where('is_active', 1)->orWhereNull('is_active');
                                })
                                ->where('request_status', 'completed')
                                ->count();

                            // 2. PERIODIC SCHEDULE STATS
                            $periodicHeaders = \Illuminate\Support\Facades\DB::table('pl_periodic_header')
                                ->count();

                            $periodicDetails = \Illuminate\Support\Facades\DB::table('pl_periodic_detail')
                                ->count();

                            $generatedItems = \Illuminate\Support\Facades\DB::table('pl_periodic_items')
                                ->count();

                            $unrealizedItems = \Illuminate\Support\Facades\DB::table('pl_periodic_items')
                                ->whereNull('realization_date')
                                ->count();

                            // 3. DAILY TASK STATS
                            $totalDailyTasks = \Illuminate\Support\Facades\DB::table('ms_daily')
                                ->where('is_active', 1)
                                ->count();

                            $todayLoggedTasks = \Illuminate\Support\Facades\DB::table('rp_daily_log')
                                ->whereDate('work_date', \Illuminate\Support\Carbon::today()->toDateString())
                                ->distinct()
                                ->count('daily_task_id');

                            $todayCompleted = \Illuminate\Support\Facades\DB::table('rp_daily_log')
                                ->whereDate('work_date', \Illuminate\Support\Carbon::today()->toDateString())
                                ->whereRaw('LOWER(COALESCE(job_status, \'\')) = ?', ['completed'])
                                ->distinct()
                                ->count('daily_task_id');

                            $dailyTarget = $totalDailyTasks;
                            $completionRate = $dailyTarget > 0 ? round(($todayCompleted / $dailyTarget) * 100) : 0;

                            // 4. AREA & WORKERS
                            $totalAreas = \Illuminate\Support\Facades\DB::table('ms_area')->count();
                            $totalWorkers = \Illuminate\Support\Facades\DB::table('users')
                                ->whereRaw("FIND_IN_SET('ptgoff', REPLACE(idroles, ' ', '')) OR FIND_IN_SET('ptgspu', REPLACE(idroles, ' ', ''))")
                                ->count();

                            // 5. TOP AREAS by Requests
                            $topAreas = \Illuminate\Support\Facades\DB::table('pl_non_periodic')
                                ->where(function($q) { $q->where('is_active', 1)->orWhereNull('is_active'); })
                                ->select('area_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
                                ->groupBy('area_id')
                                ->orderBy('count', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        <!-- KEY METRICS CARDS -->
                        <div class="row mb-4">
                            <!-- Menunggu Approval -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card h-100" style="border-left: 4px solid #f59e0b;">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm text-muted mb-0">Menunggu Approval</p>
                                                    <h5 class="font-weight-bolder mb-0" style="color: #f59e0b;">{{ $totalApprovalNeeded }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape icon-sm rounded" style="background: rgba(245, 158, 11, 0.15);">
                                                    <i class="fas fa-inbox" style="color: #f59e0b; font-size: 1.5rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Disetujui -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card h-100" style="border-left: 4px solid #10b981;">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm text-muted mb-0">Telah Disetujui</p>
                                                    <h5 class="font-weight-bolder mb-0" style="color: #10b981;">{{ $approvedRequests }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape icon-sm rounded" style="background: rgba(16, 185, 129, 0.15);">
                                                    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.5rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ditolak -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card h-100" style="border-left: 4px solid #ef4444;">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm text-muted mb-0">Ditolak</p>
                                                    <h5 class="font-weight-bolder mb-0" style="color: #ef4444;">{{ $rejectedRequests }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape icon-sm rounded" style="background: rgba(239, 68, 68, 0.15);">
                                                    <i class="fas fa-times-circle" style="color: #ef4444; font-size: 1.5rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selesai -->
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card h-100" style="border-left: 4px solid #8b5cf6;">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm text-muted mb-0">Selesai</p>
                                                    <h5 class="font-weight-bolder mb-0" style="color: #8b5cf6;">{{ $completedRequests }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape icon-sm rounded" style="background: rgba(139, 92, 246, 0.15);">
                                                    <i class="fas fa-flag-checkered" style="color: #8b5cf6; font-size: 1.5rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PERIODIC & DAILY ROW -->
                        <div class="row mb-4">
                            <!-- Jadwal Periodic -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header pb-0 bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-calendar-check text-info"></i> Jadwal Periodic
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center mb-3">
                                            <div class="col-6 border-right">
                                                <p class="text-sm text-muted mb-1">Template Aktif</p>
                                                <h5 class="font-weight-bolder text-info">{{ $periodicDetails }}</h5>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-sm text-muted mb-1">Item Generated</p>
                                                <h5 class="font-weight-bolder text-info">{{ $generatedItems }}</h5>
                                            </div>
                                        </div>
                                        <div class="alert alert-warning mb-0" role="alert">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span class="ms-2">{{ $unrealizedItems }} item belum direalisasikan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Daily Completion -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header pb-0 bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-tasks text-success"></i> Daily Checklist Hari Ini
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-sm">Progress Completion</span>
                                                <span class="font-weight-bold">{{ $completionRate }}%</span>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completionRate }}%;" aria-valuenow="{{ $completionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <p class="text-sm text-muted mb-0">
                                            <strong>{{ $todayCompleted }}</strong> dari <strong>{{ $dailyTarget }}</strong> task hari ini
                                        </p>
                                        @if ($todayLoggedTasks < $dailyTarget)
                                            <p class="text-xs text-muted mb-0 mt-1">{{ $todayLoggedTasks }} task sudah diinput hari ini</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AREA & PERFORMANCE -->
                        <div class="row mb-4">
                            <!-- Top Areas -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header pb-0 bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-chart-bar text-primary"></i> Area dengan Permintaan Terbanyak
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($topAreas->count() > 0)
                                            <div class="list-group list-group-flush">
                                                @foreach($topAreas as $area)
                                                    @php
                                                        $areaName = \Illuminate\Support\Facades\DB::table('ms_area')
                                                            ->where('id', $area->area_id)
                                                            ->value('nama_area') ?? 'Area ' . $area->area_id;
                                                    @endphp
                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                        <span>{{ $areaName }}</span>
                                                        <span class="badge bg-primary rounded-pill">{{ $area->count }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted text-sm">Tidak ada data permintaan</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Resources Summary -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header pb-0 bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-users-cog text-success"></i> Ringkasan Sumber Daya
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-6 border-right mb-3">
                                                <p class="text-sm text-muted mb-1">Petugas Aktif</p>
                                                <h5 class="font-weight-bolder text-success">{{ $totalWorkers }}</h5>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <p class="text-sm text-muted mb-1">Area Kerja</p>
                                                <h5 class="font-weight-bolder text-info">{{ $totalAreas }}</h5>
                                            </div>
                                            <div class="col-12">
                                                <p class="text-sm text-muted mb-1">Daily Task Master</p>
                                                <h5 class="font-weight-bolder text-warning">{{ $totalDailyTasks }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QUICK ACTIONS -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header pb-0 bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-lightning-bolt"></i> Aksi Cepat
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-2">
                                            <div class="col-lg-3 col-md-6">
                                                <a href="{{ url('pengajuan-hrd') }}" class="btn btn-outline-primary w-100 rounded-lg">
                                                    <i class="fas fa-stamp"></i> Approval Pengajuan
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <a href="{{ url('daftar-periodic-hrd') }}" class="btn btn-outline-info w-100 rounded-lg">
                                                    <i class="fas fa-plus-square"></i> Input Jadwal Periodic
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <a href="{{ url('area') }}" class="btn btn-outline-warning w-100 rounded-lg">
                                                    <i class="fas fa-map"></i> Kelola Area
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <a href="{{ url('daily') }}" class="btn btn-outline-success w-100 rounded-lg">
                                                    <i class="fas fa-list-check"></i> Daily Task Master
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

