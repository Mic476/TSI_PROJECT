@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => ''])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="mb-0">Dashboard Petugas - {{ $user_role }}</h4>
                        <p class="text-muted mb-0">Selamat datang {{ auth()->user()?->firstname ?? 'Petugas' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="{{ $user_role === 'Office' ? 'col-md-4' : 'col-md-3' }}">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <h6 class="text-xs text-uppercase mb-1">Total Tugas</h6>
                        <h3 class="mb-0">{{ $total_tasks }}</h3>
                    </div>
                </div>
            </div>
            <div class="{{ $user_role === 'Office' ? 'col-md-4' : 'col-md-3' }}">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <h6 class="text-xs text-uppercase mb-1">Daily</h6>
                        <h3 class="mb-0">{{ $daily_count ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="{{ $user_role === 'Office' ? 'col-md-4' : 'col-md-3' }}">
                <div class="card border-left-info">
                    <div class="card-body">
                        <h6 class="text-xs text-uppercase mb-1">Periodic</h6>
                        <h3 class="mb-0">{{ $periodic_count }}</h3>
                    </div>
                </div>
            </div>
            @if ($user_role !== 'Office')
                <div class="col-md-3">
                    <div class="card border-left-success">
                        <div class="card-body">
                            <h6 class="text-xs text-uppercase mb-1">Pending Verifikasi</h6>
                            <h3 class="mb-0">{{ $pending_verification }}</h3>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pekerjaan Belum Selesai -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Daftar Pekerjaan Belum Selesai</h6>
                    </div>
                    <div class="card-body">
                        @if ($total_tasks > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-xs">Tanggal</th>
                                            <th class="text-uppercase text-xs">Jenis</th>
                                            <th class="text-uppercase text-xs">Pekerjaan</th>
                                            <th class="text-uppercase text-xs">Keterangan</th>
                                            <th class="text-uppercase text-xs">Status</th>
                                            <th class="text-uppercase text-xs">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($non_periodic_tasks as $task)
                                            <tr>
                                                <td>
                                                    <small>{{ $task->created_at ? \Carbon\Carbon::parse($task->created_at)->format('d M Y') : '-' }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">Non Periodic</span>
                                                </td>
                                                <td>
                                                    <small>{{ $task->job_description ?? '-' }}</small>
                                                </td>
                                                <td>
                                                    <small>
                                                        {{ $task->area_name ?? '-' }}
                                                        @if (!empty($task->work_type))
                                                            | {{ ucfirst($task->work_type) }}
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    @if (($task->request_status ?? '') === 'revisi')
                                                        <span class="badge bg-warning">Revisi</span>
                                                    @elseif (($task->request_status ?? '') === 'pengerjaan')
                                                        <span class="badge bg-info">Pengerjaan</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($task->request_status ?? '-') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user_role === 'Office')
                                                        <span class="text-xs text-muted">Tidak tersedia</span>
                                                    @else
                                                        <a href="{{ url('pekerjaan-nonperiodic') }}" class="btn btn-sm btn-primary mb-0">
                                                            Buka Transaksi
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

                                        @forelse ($periodic_items as $item)
                                            <tr>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($item->planned_date)->format('d M Y') }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">Periodic</span>
                                                </td>
                                                <td>
                                                    <small>{{ $item->job_description ?? '-' }}</small>
                                                </td>
                                                <td>
                                                    <small>
                                                        {{ $item->area_name ?? '-' }}
                                                        @if (!empty($item->periode))
                                                            | {{ ucfirst($item->periode) }}
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">Belum Realisasi</span>
                                                </td>
                                                <td>
                                                    <a href="{{ url('pekerjaan-periodic') }}" class="btn btn-sm btn-primary mb-0">
                                                        Buka Transaksi
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p>Tidak ada pekerjaan yang perlu diselesaikan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-left-primary {
            border-left: 4px solid #0d6efd !important;
        }
        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
        .border-left-info {
            border-left: 4px solid #0dcaf0 !important;
        }
        .border-left-success {
            border-left: 4px solid #198754 !important;
        }
    </style>
@endsection
