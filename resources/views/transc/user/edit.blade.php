                        <div class="card-body">
                            <p class="text-uppercase text-sm">Edit {{ $title_menu }}</p>
                            <hr class="horizontal dark mt-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-control-label">Area <span class="text-danger">*</span></label>
                                    <select class="form-select" name="area_id" required>
                                        <option value="">Pilih area</option>
                                        @foreach ($areaOptions ?? [] as $areaName => $areaId)
                                            <option value="{{ $areaId }}" {{ old('area_id', $list->area_id ?? '') == $areaId ? 'selected' : '' }}>{{ $areaName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label">Nama Pengaju <span class="text-danger">*</span></label>
                                    <select class="form-select" name="requester_name" required>
                                        <option value="">Pilih nama pengaju</option>
                                        @foreach ($requesterOptions ?? [] as $requester)
                                            <option value="{{ $requester }}" {{ old('requester_name', $list->requester_name ?? '') == $requester ? 'selected' : '' }}>{{ $requester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label">Kategori Pekerjaan <span class="text-danger">*</span></label>
                                    <select class="form-select" name="work_category">
                                        <option value="">Pilih kategori</option>
                                        <option value="mechanical" {{ old('work_category', $list->work_category ?? '') == 'mechanical' ? 'selected' : '' }}>Mechanical</option>
                                        <option value="electrical" {{ old('work_category', $list->work_category ?? '') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                                        <option value="civil" {{ old('work_category', $list->work_category ?? '') == 'civil' ? 'selected' : '' }}>Civil</option>
                                        <option value="housekeeping" {{ old('work_category', $list->work_category ?? '') == 'housekeeping' ? 'selected' : '' }}>Housekeeping</option>
                                        <option value="other" {{ old('work_category', $list->work_category ?? '') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-control-label">Jenis Pelaksana <span class="text-danger">*</span></label>
                                    <select class="form-select" name="work_type" id="work_type">
                                        <option value="">Belum ditentukan</option>
                                        <option value="internal" {{ old('work_type', $list->work_type ?? '') == 'internal' ? 'selected' : '' }}>Internal</option>
                                        <option value="vendor" {{ old('work_type', $list->work_type ?? '') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label">Prioritas <span class="text-danger">*</span></label>
                                    <select class="form-select" name="priority">
                                        <option value="">Pilih prioritas</option>
                                        <option value="low" {{ old('priority', $list->priority ?? '') == 'low' ? 'selected' : '' }}>Rendah</option>
                                        <option value="medium" {{ old('priority', $list->priority ?? '') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                        <option value="high" {{ old('priority', $list->priority ?? '') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                        <option value="urgent" {{ old('priority', $list->priority ?? '') == 'urgent' ? 'selected' : '' }}>Darurat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3" id="vendor-row" style="display:none;">
                                <div class="col-md-12">
                                    <label class="form-control-label">Nama Vendor</label>
                                    <input class="form-control" name="vendor_name" type="text"
                                        value="{{ old('vendor_name', $list->vendor_name ?? '') }}"
                                        placeholder="Nama vendor jika pekerjaan melalui vendor">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-control-label">Lokasi Detail</label>
                                    <input class="form-control" name="location_detail" type="text"
                                        value="{{ old('location_detail', $list->location_detail ?? '') }}"
                                        placeholder="Contoh: Lantai 2, Ruang Server">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label">Target Waktu</label>
                                    <input class="form-control" name="target_date" type="date"
                                        value="{{ old('target_date', $list->target_date ?? '') }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-control-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="job_description" rows="4"
                                        placeholder="Contoh: Lampu pecah di area toilet lantai 2, butuh penggantian segera.">{{ old('job_description', $list->job_description ?? '') }}</textarea>
                                    <p class="text-xs text-secondary mt-2 mb-0">Status awal otomatis: review.</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-control-label">Kontak Pemohon</label>
                                    <input class="form-control" name="contact_name" type="text"
                                        value="{{ old('contact_name', $list->contact_name ?? '') }}"
                                        placeholder="Nama pemohon">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label">No. Telepon</label>
                                    <input class="form-control" name="contact_phone" type="text"
                                        value="{{ old('contact_phone', $list->contact_phone ?? '') }}"
                                        placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-control-label">Lampiran (Opsional, bisa lebih dari 1)</label>
                                    <input class="form-control" name="attachment[]" type="file" accept="image/*,.pdf" multiple>
                                    @if (!empty($attachment_files))
                                        <p class="text-xs text-secondary mt-2 mb-1">File saat ini:</p>
                                        @foreach ($attachment_files as $attachmentFile)
                                            <p class="text-xs text-secondary mb-1">- {{ $attachmentFile }}</p>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <input type="hidden" name="request_status" value="{{ old('request_status', $list->request_status ?? 'review') }}">
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
        $(document).ready(function() {
            function toggleVendor() {
                if ($('#work_type').val() === 'vendor') {
                    $('#vendor-row').show();
                } else {
                    $('#vendor-row').hide();
                }
            }

            toggleVendor();
            $('#work_type').on('change', toggleVendor);
        });
    </script>
@endpush
