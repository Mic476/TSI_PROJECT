@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
{{-- section content --}}
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => ''])
    <div class="card shadow-lg mx-4">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-lg">
                    <div class="nav-wrapper">
                        {{-- button back --}}
                        <button class="btn btn-secondary mb-0" onclick="history.back()"><i class="fas fa-circle-left me-1">
                            </i><span class="font-weight-bold">Kembali</button>
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
                                <div class="col-md-6">
                                    <label class="form-control-label">Area</label>
                                    <input class="form-control" type="text" value="{{ $list->nama_area ?? '' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label">Jenis Pelaksana</label>
                                    <input class="form-control" type="text" value="{{ $list->work_type ?? '' }}" disabled>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="form-control-label">Nama Pengaju</label>
                                    <input class="form-control" type="text" value="{{ $list->requester_name ?? '' }}" disabled>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-control-label">Deskripsi Pekerjaan</label>
                                    <textarea class="form-control" rows="4" disabled>{{ $list->job_description ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="form-control-label">Nama Vendor</label>
                                    <input class="form-control" type="text" value="{{ $list->vendor_name ?? '' }}" disabled>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="form-control-label">Status</label>
                                    <input class="form-control" type="text" value="{{ $list->request_status ?? '' }}" disabled>
                                </div>
                            </div>
                            @if (!empty($attachment_files))
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label class="form-control-label">Lampiran</label>
                                    <div class="row mt-2">
                                        @foreach ($attachment_files as $attachmentFile)
                                            <div class="col-md-4 mb-3">
                                                <a href="{{ asset('storage/' . $attachmentFile) }}" target="_blank" rel="noopener noreferrer">
                                                    <img src="{{ asset('storage/' . $attachmentFile) }}"
                                                        alt="Lampiran"
                                                        class="img-fluid rounded shadow-sm"
                                                        style="max-height: 180px; object-fit: cover; width: 100%;">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-primary text-xs pt-2">
                                        <i class="fas fa-info-circle me-1"></i>Klik gambar untuk membuka ukuran penuh
                                    </p>
                                </div>
                            </div>
                            @endif
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
@endsection
@push('js')
    <script>
        $(document).ready(function() {});
    </script>
@endpush
