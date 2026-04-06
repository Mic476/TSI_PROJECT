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
                            </i><span class="font-weight-bold">Kembali</span></button>
                        {{-- check authorize add --}}
                        @if ($authorize->add == '1')
                            {{-- button save --}}
                            <button class="btn btn-primary mb-0"
                                onclick="event.preventDefault(); document.getElementById('{{ $dmenu }}-form').submit();"><i
                                    class="fas fa-floppy-disk me-1"> </i><span class="font-weight-bold">Simpan</span></button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body pb-0">
                        @include('components.alert')
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <form role="form" method="POST" action="{{ URL::to($url_menu) }}" id="{{ $dmenu }}-form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Insert {{ $title_menu }}</p>
                            <hr class="horizontal dark mt-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-control-label">Area <span class="text-danger">*</span></label>
                                    <select class="form-select" name="area_id" required>
                                        <option value="">Pilih area</option>
                                        @foreach ($areaOptions ?? [] as $areaName => $areaId)
                                            <option value="{{ $areaId }}" {{ old('area_id') == $areaId ? 'selected' : '' }}>{{ $areaName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label">Nama Pengaju <span class="text-danger">*</span></label>
                                    <select class="form-select" name="requester_name" required>
                                        <option value="">Pilih nama pengaju</option>
                                        @foreach ($requesterOptions ?? [] as $requester)
                                            <option value="{{ $requester }}" {{ old('requester_name') == $requester ? 'selected' : '' }}>{{ $requester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-control-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="job_description" rows="4"
                                        placeholder="Contoh: Lampu pecah di area toilet lantai 2, butuh penggantian segera."></textarea>
                                    @php
                                        $normalizedRoles = ',' . str_replace(' ', '', strtolower((string) ($user_login->idroles ?? ''))) . ',';
                                        $isHrdSubmitter = str_contains($normalizedRoles, ',hrdxxx,') || str_contains($normalizedRoles, ',hrd,');
                                    @endphp
                                    <p class="text-xs text-secondary mt-2 mb-0">
                                        {{ $isHrdSubmitter ? 'Status awal otomatis: review (langsung ke approval HRD, tanpa head approval).' : 'Status awal otomatis: pending.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-control-label">Lampiran (Opsional, bisa lebih dari 1)</label>
                                    <input class="form-control" name="attachment[]" type="file" accept="image/*,.pdf" multiple>
                                    <p class="text-xs text-secondary mt-2 mb-0">Upload foto kondisi atau dokumen pendukung (multi file).</p>
                                </div>
                            </div>
                            <input type="hidden" name="request_status" value="pending">
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
