@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
{{-- section content --}}
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => ''])
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row mx-1">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Result {{ $title_menu }}</h5>
                        </div>
                        <hr class="horizontal dark mt-0">
                        <div class="row px-4 py-2">
                            <div class="col-lg">
                                <div class="nav-wrapper row">
                                    <div class="col">
                                        {{-- button back --}}
                                        <button class="btn btn-secondary mb-0" onclick="history.back()"><i
                                                class="fas fa-circle-left me-1"> </i><span
                                                class="font-weight-bold">Kembali</button>
                                    </div>
                                    <div class="col-md-3 md-auto justify-content-end row">
                                        <div class="col">
                                            {{-- set value filter --}}
                                            <?php $check_filter = ''; ?>
                                            @foreach ($filter as $key => $value)
                                                <input type="hidden" name="f{{ $key }}"
                                                    value="{{ $value }}" />
                                                @php
                                                    if (
                                                        preg_replace('/\s+/', '', strtolower(@$table_class->alias)) ==
                                                        preg_replace('/\s+/', '', strtolower($key))
                                                    ) {
                                                        $check_filter = $value == '%' ? '' : $value;
                                                    }
                                                @endphp
                                            @endforeach
                                        </div>
                                        <div class="col">
                                            {{-- display label alias on class filter --}}
                                            Filter {{ @$table_class->alias }} :
                                            <select class="form-select" id="{{ @$table_class->field }}"
                                                style="width: 150px;">
                                                @if (@$table_class->query != '')
                                                    @if ($check_filter != '')
                                                        @php
                                                            $data_query = DB::select(@$table_class->query);
                                                        @endphp
                                                        @foreach ($data_query as $q)
                                                            <?php $sAsArray = array_values((array) $q); ?>
                                                            @if ($check_filter == $sAsArray[0])
                                                                <option value="{{ $sAsArray[0] }}">
                                                                    {{ $sAsArray[0] }} - {{ $sAsArray[1] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <option value="" selected>% - All</option>
                                                        @php
                                                            $data_query = DB::select(@$table_class->query);
                                                        @endphp
                                                        @foreach ($data_query as $q)
                                                            <?php $sAsArray = array_values((array) $q); ?>
                                                            <option value="{{ $sAsArray[0] }}">
                                                                {{ $sAsArray[0] }} - {{ $sAsArray[1] }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row px-4 py-2">
                            <div class="table-responsive">
                                <table class="table display" id="list_{{ $dmenu }}">
                                    {{-- check result data --}}
                                    @if ($table_result)
                                        <thead class="thead-light" style="background-color: #00b7bd4f;">
                                            <tr>
                                                <th>No</th>
                                                {{-- set table header --}}
                                                @foreach ($table_result as $result)
                                                    @php
                                                        $sAsArray = array_keys((array) $result);
                                                        $i = 0;
                                                    @endphp
                                                @endforeach
                                                @foreach ($sAsArray as $header)
                                                    @if (substr($header, 0, 3) == 'IMG')
                                                        <th>{{ substr($header, 4) }}</th>
                                                    @elseif (substr($header, 0, 3) == 'CDT')
                                                        <th>{{ substr($header, 6) . '-' . substr($header, 3, 2) }}</th>
                                                    @elseif (substr($header, 0, 3) == 'CST')
                                                        <th>{{ substr($header, 4) }}</th>
                                                    @else
                                                        <th>{{ $header }}</th>
                                                    @endif
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- retrieve table result --}}
                                            @foreach ($table_result as $detail)
                                                <tr
                                                    {{ @$detail->isactive == '0' || @$detail->ISACTIVE == '0' ? 'class=not style=background-color:#ffe9ed;' : '' }}>
                                                    <td>{{ $loop->iteration }}</td>
                                                    @foreach ($table_result as $result)
                                                        @php
                                                            $field = array_keys((array) $result);
                                                            $i = 0;
                                                        @endphp
                                                    @endforeach
                                                    @foreach ($field as $header)
                                                        @php
                                                            $string = $header;
                                                            $img_id = '';
                                                            $characters =
                                                                'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                                            for ($i = 0; $i < 10; $i++) {
                                                                $img_id .=
                                                                    $characters[mt_rand(0, strlen($characters) - 1)];
                                                            }
                                                        @endphp
                                                        @if (substr($string, 0, 3) == 'IMG')
                                                            <td class="text-sm font-weight-normal">
                                                                @php
                                                                    $rawImages = trim((string) ($detail->$string ?? ''));
                                                                    $imageItems = [];

                                                                    if ($rawImages !== '') {
                                                                        $decodedImages = null;
                                                                        if (str_starts_with($rawImages, '[')) {
                                                                            $decodedImages = json_decode($rawImages, true);
                                                                        }

                                                                        if (is_array($decodedImages)) {
                                                                            $imageItems = $decodedImages;
                                                                        } else {
                                                                            $imagePaths = preg_split('/\s*(?:\|\||\||,)\s*/', $rawImages, -1, PREG_SPLIT_NO_EMPTY);
                                                                            foreach ($imagePaths as $imagePath) {
                                                                                $parts = explode('@@', (string) $imagePath, 2);
                                                                                $imageItems[] = [
                                                                                    'date' => $parts[0] !== $imagePath ? trim($parts[0]) : '',
                                                                                    'path' => trim($parts[1] ?? $parts[0]),
                                                                                ];
                                                                            }
                                                                        }
                                                                    }

                                                                    $imageItems = array_values(array_filter(array_map(function ($item) {
                                                                        if (is_array($item)) {
                                                                            return [
                                                                                'date' => trim((string) ($item['date'] ?? '')),
                                                                                'path' => trim((string) ($item['path'] ?? '')),
                                                                            ];
                                                                        }

                                                                        $rawItem = trim((string) $item);
                                                                        if ($rawItem === '') {
                                                                            return null;
                                                                        }

                                                                        $parts = explode('@@', $rawItem, 2);
                                                                        return [
                                                                            'date' => trim($parts[0] ?? ''),
                                                                            'path' => trim($parts[1] ?? $parts[0]),
                                                                        ];
                                                                    }, $imageItems)));

                                                                    $uniqueImageItems = [];
                                                                    $seenImagePaths = [];
                                                                    foreach ($imageItems as $imageItem) {
                                                                        $pathKey = $imageItem['path'] ?? '';
                                                                        if ($pathKey === '' || in_array($pathKey, $seenImagePaths, true)) {
                                                                            continue;
                                                                        }
                                                                        $seenImagePaths[] = $pathKey;
                                                                        $uniqueImageItems[] = $imageItem;
                                                                    }
                                                                    $imageItems = $uniqueImageItems;

                                                                    $getImageUrl = function ($path) {
                                                                        $normalizedPath = str_replace('\\', '/', trim((string) $path));

                                                                        if ($normalizedPath === '') {
                                                                            return '';
                                                                        }

                                                                        if (preg_match('/^https?:\/\//i', $normalizedPath)) {
                                                                            return $normalizedPath;
                                                                        }

                                                                        $normalizedPath = preg_replace('#^/?public/#', '', $normalizedPath);
                                                                        $normalizedPath = preg_replace('#^/?storage/#', '', $normalizedPath);
                                                                        return asset('/storage/' . ltrim($normalizedPath, '/'));
                                                                    };

                                                                    $galleryId = 'gallery_' . $img_id;
                                                                    $carouselId = 'carousel_' . $img_id;
                                                                @endphp

                                                                @if (count($imageItems) > 0)
                                                                    <div class="d-flex align-items-center flex-wrap gap-1">
                                                                        @foreach ($imageItems as $imageIndex => $imageItem)
                                                                            @php
                                                                                $imageUrl = $getImageUrl($imageItem['path']);
                                                                            @endphp
                                                                            <img src="{{ $imageUrl }}" alt="image"
                                                                                style="height: 40px; width: 40px; object-fit: cover; cursor: pointer;"
                                                                                class="rounded border"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#{{ $galleryId }}"
                                                                                onclick="showReportImage('{{ $carouselId }}', {{ $imageIndex }})">
                                                                        @endforeach
                                                                    </div>

                                                                    <div class="modal fade" id="{{ $galleryId }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Preview Image</h5>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div id="{{ $carouselId }}" class="carousel slide" data-bs-ride="false">
                                                                                        <div class="carousel-inner">
                                                                                            @foreach ($imageItems as $imageIndex => $imageItem)
                                                                                                @php
                                                                                                    $imageUrl = $getImageUrl($imageItem['path']);
                                                                                                @endphp
                                                                                                <div class="carousel-item {{ $imageIndex === 0 ? 'active' : '' }}">
                                                                                                    <div class="text-center mb-2 text-xs text-secondary">
                                                                                                        {{ $imageItem['date'] !== '' ? 'Tanggal Realisasi: ' . $imageItem['date'] : '' }}
                                                                                                    </div>
                                                                                                    <img src="{{ $imageUrl }}" alt="image {{ $imageIndex + 1 }}"
                                                                                                        class="w-100 border-radius-lg shadow-sm"
                                                                                                        style="max-height: 70vh; object-fit: contain; background-color: #f8f9fa;">
                                                                                                </div>
                                                                                            @endforeach
                                                                                        </div>
                                                                                        @if (count($imageItems) > 1)
                                                                                                <button class="carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev"
                                                                                                    style="width: 42px; height: 42px; top: 50%; transform: translateY(-50%); left: 10px; background: rgba(31, 59, 92, 0.9); border-radius: 999px; opacity: 1; border: 2px solid rgba(255,255,255,0.7); box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                                                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: brightness(0) invert(1);"></span>
                                                                                                <span class="visually-hidden">Previous</span>
                                                                                            </button>
                                                                                                <button class="carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next"
                                                                                                    style="width: 42px; height: 42px; top: 50%; transform: translateY(-50%); right: 10px; background: rgba(31, 59, 92, 0.9); border-radius: 999px; opacity: 1; border: 2px solid rgba(255,255,255,0.7); box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                                                                    <span class="carousel-control-next-icon" aria-hidden="true" style="filter: brightness(0) invert(1);"></span>
                                                                                                <span class="visually-hidden">Next</span>
                                                                                            </button>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <span>-</span>
                                                                @endif
                                                            </td>
                                                        @elseif (substr($string, 0, 3) == 'CDT')
                                                            <td class="text-sm font-weight-normal {{ $detail->$string }}">
                                                                {{ $detail->$string }}
                                                            </td>
                                                            <script>
                                                                var inputDate = new Date('{{ $detail->$string }}');
                                                                var currentDate = new Date();
                                                                var futureDate = new Date(currentDate.setDate(currentDate.getDate() + parseInt('{{ substr($string, 3, 2) }}')));

                                                                if (inputDate <= futureDate) {
                                                                    $('.{{ $detail->$string }}').parents('tr').addClass('exp');
                                                                    $('.{{ $detail->$string }}').parents('tr').css('background-color', '#ffe768');
                                                                }
                                                            </script>
                                                        @elseif (substr($string, 0, 3) == 'CST')
                                                            <td class="text-sm font-weight-normal {{ $detail->$string }}">
                                                                {{ $detail->$string }}
                                                            </td>
                                                            <script>
                                                                if ('{{ $detail->$string }}' == 'FALSE') {
                                                                    $('.FALSE').parents('tr').addClass('stock');
                                                                    $('.FALSE').parents('tr').css('background-color', '#f93c3c');
                                                                    $('.FALSE').parents('tr').css('color', '#000');
                                                                }
                                                            </script>
                                                        @else
                                                            <td class="text-sm font-weight-normal">
                                                                {{ $detail->$string }}</td>
                                                        @endif
                                                        @php
                                                            $i++;
                                                        @endphp
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="row px-4 py-2">
                            <div class="col-lg">
                                @if ($table_result)
                                    <div class="nav-wrapper" id="noted"><code>Note : <i aria-hidden="true"
                                                style="color: #ffc2cd;" class="fas fa-circle"></i> Data not active</code>
                                    </div>
                                @else
                                    <div class="nav-wrapper"><code> Data not found!</code></div>
                                @endif
                            </div>
                        </div>
                    </div>
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
        let columnAbjad = '';
        const exportPreviewImages = {};
        const reportTitleMenu = @json($title_menu);

        function getFilterInputValue(name, fallbackValue = '') {
            const input = document.querySelector('input[name="' + name + '"]');
            return input ? (input.value || fallbackValue) : fallbackValue;
        }

        function parseFilterDate(dateString) {
            if (!dateString) {
                return null;
            }

            const parts = String(dateString).split('-');
            if (parts.length !== 3) {
                return null;
            }

            const year = parseInt(parts[0], 10);
            const month = parseInt(parts[1], 10);
            const day = parseInt(parts[2], 10);
            if (!year || !month || !day) {
                return null;
            }

            const date = new Date(year, month - 1, day);
            return Number.isNaN(date.getTime()) ? null : date;
        }

        function formatMonthYearIndo(date) {
            const monthNames = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];

            return monthNames[date.getMonth()] + ' ' + date.getFullYear();
        }

        function buildExportReportTitle() {
            const fromDate = parseFilterDate(getFilterInputValue('ffrdate', ''));
            const toDate = parseFilterDate(getFilterInputValue('ftodate', ''));
            const reportBaseTitle = String(reportTitleMenu || '').toLowerCase().includes('pemeliharaan')
                ? 'Laporan Pemeliharaan'
                : 'Laporan Dokumentasi Lapangan';

            if (!fromDate || !toDate) {
                return reportBaseTitle;
            }

            const sameMonthYear = fromDate.getMonth() === toDate.getMonth() && fromDate.getFullYear() === toDate.getFullYear();
            if (sameMonthYear) {
                return reportBaseTitle + ' ' + formatMonthYearIndo(toDate);
            }

            return reportBaseTitle + ' ' + formatMonthYearIndo(fromDate) + ' - ' + formatMonthYearIndo(toDate);
        }

        function buildDisplayTitle() {
            return 'MSJFramework | ' + buildExportReportTitle();
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function buildPrintableTableHtml(dataTable) {
            const visibleColumns = dataTable.columns(':visible').indexes().toArray();
            const headerHtml = visibleColumns.map((colIndex) => {
                const headerText = dataTable.column(colIndex).header().textContent || '';
                return '<th>' + escapeHtml(headerText.trim()) + '</th>';
            }).join('');

            const rowHtml = dataTable.rows({
                search: 'applied',
                order: 'applied'
            }).indexes().toArray().map((rowIndex) => {
                const cellHtml = visibleColumns.map((colIndex) => {
                    const node = dataTable.cell(rowIndex, colIndex).node();
                    const value = exportBodyFormatter('', node, 'print');
                    return '<td>' + (value || '-') + '</td>';
                }).join('');

                return '<tr>' + cellHtml + '</tr>';
            }).join('');

            return '<table><thead><tr>' + headerHtml + '</tr></thead><tbody>' + rowHtml + '</tbody></table>';
        }

        function printWithoutNewTab(dataTable, reportTitle) {
            const iframe = document.createElement('iframe');
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            document.body.appendChild(iframe);

            const printDocument = iframe.contentWindow.document;
            const printableTableHtml = buildPrintableTableHtml(dataTable);

            printDocument.open();
            printDocument.write(`
                <html>
                <head>
                    <title></title>
                    <style>
                        html, body { width: 100%; }
                        body { font-family: Arial, sans-serif; margin: 16px; color: #111827; }
                        .print-header { text-align: center; margin-bottom: 12px; }
                        .print-header h2 { margin: 0; font-size: 22px; font-weight: 700; }
                        table { border-collapse: collapse; width: 100%; table-layout: auto; font-size: 12px; }
                        th, td { border: 1px solid #cbd5e1; padding: 6px 6px; vertical-align: top; word-break: break-word; }
                        th { background: #1f3b5c !important; color: #ffffff !important; text-align: center; font-weight: 700; }
                        th:first-child, td:first-child { width: 34px; text-align: center; }
                        th:nth-child(3), td:nth-child(3) { white-space: nowrap; width: 1%; }
                        th:nth-child(5), td:nth-child(5), th:nth-child(6), td:nth-child(6) { white-space: nowrap; width: 1%; }
                        tbody tr:nth-child(even) { background: #f8fafc; }
                        td img { height: 44px; width: 44px; object-fit: cover; margin-right: 3px; border: 1px solid #e5e7eb; border-radius: 3px; }
                        @page { size: landscape; margin: 10mm; }
                        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h2>${escapeHtml(reportTitle)}</h2>
                    </div>
                    ${printableTableHtml}
                </body>
                </html>
            `);
            printDocument.close();

            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                setTimeout(() => {
                    if (iframe.parentNode) {
                        iframe.parentNode.removeChild(iframe);
                    }
                }, 1000);
            };
        }

        function collectExportPreviewImages() {
            const table = document.getElementById('list_{{ $dmenu }}');
            if (!table) {
                return;
            }

            const images = table.querySelectorAll('tbody td img');
            images.forEach((img) => {
                const src = img.getAttribute('src') || '';
                if (!src || exportPreviewImages[src]) {
                    return;
                }

                try {
                    const canvas = document.createElement('canvas');
                    const width = img.naturalWidth || 120;
                    const height = img.naturalHeight || 120;
                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    if (!ctx) {
                        return;
                    }

                    ctx.drawImage(img, 0, 0, width, height);
                    exportPreviewImages[src] = canvas.toDataURL('image/jpeg', 0.72);
                } catch (error) {
                    // Ignore conversion failures and fallback to text on export.
                }
            });
        }

        function extractImageSources(node) {
            if (!node) {
                return [];
            }

            const images = Array.from(node.querySelectorAll('img'))
                .filter((img) => !img.closest('.modal'));

            return Array.from(new Set(images
                .map((img) => img.getAttribute('src') || '')
                .filter((src) => src !== '')));
        }

        function exportBodyFormatter(data, node, mode) {
            if (!node) {
                return data;
            }

            const sources = extractImageSources(node);
            if (sources.length > 0) {
                if (mode === 'print') {
                    return sources
                        .map((src) => '<img src="' + src + '" style="height:45px;width:45px;object-fit:cover;margin-right:4px;border:1px solid #ddd;border-radius:4px;">')
                        .join('');
                }

                if (mode === 'pdf') {
                    return '__IMGSET__' + sources.join('||');
                }

                return sources.join('\n');
            }

            return node.textContent ? node.textContent.trim() : data;
        }

        $(document).ready(function() {
            collectExportPreviewImages();

            let table = $('#list_{{ $dmenu }}').DataTable();
            let indexStatus = 0;
            let numColumns = $('#list_{{ $dmenu }}').DataTable().columns().count();
            let columnNames = '';
            let filter = '{{ @$table_class->alias }}'.toLowerCase();
            for (let index = 0; index < numColumns; index++) {
                columnNames = $('#list_{{ $dmenu }}').DataTable().columns(index).header()[0].textContent;
                filtercolom = columnNames.toLowerCase();
                if (columnNames == 'Status' || columnNames == 'status' || columnNames == 'STATUS') {
                    columnAbjad = String.fromCharCode(65 + index);
                }
                if (filtercolom == filter) {
                    indexStatus = index;
                }
            }
            //redraw table where filter
            $('#{{ @$table_class->field }}').change(function() {
                table.column(indexStatus).search($(this).val()).draw();
            })
            //check note
            if ($('*').hasClass('exp')) {
                $('#noted').html(`<code>Note :( <i aria-hidden="true" style="color: #ffc2cd;"
                class="fas fa-circle"></i> Data not active ), ( <i aria-hidden="true"
                style="color: #ffe768;" class="fas fa-circle"></i> Data Expired )</code>`)
            }
            if ($('*').hasClass('stock')) {
                $('#noted').html(`<code>Note :( <i aria-hidden="true" style="color: #ffc2cd;"
                class="fas fa-circle"></i> Data not active ), ( <i aria-hidden="true"
                style="color: #f93c3c;" class="fas fa-circle"></i> Stock < Min Stock )</code>`)
            }
            if ($('*').hasClass('exp') && $('*').hasClass('stock')) {
                $('#noted').html(`<code>Note :( <i aria-hidden="true" style="color: #ffc2cd;"
                class="fas fa-circle"></i> Data not active ), ( <i aria-hidden="true"
                style="color: #ffe768;" class="fas fa-circle"></i> Data Expired ), ( <i aria-hidden="true"
                style="color: #f93c3c;" class="fas fa-circle"></i> Stock < Min Stock )</code>`)
            }
        });
        //set table into datatables
        const exportReportTitle = buildExportReportTitle();
        const displayExportTitle = buildDisplayTitle();
        $('#list_{{ $dmenu }}').DataTable({
            "language": {
                "search": "Cari :",
                "lengthMenu": "Tampilkan _MENU_ baris",
                "zeroRecords": "Maaf - Data tidak ada",
                "info": "Data _START_ - _END_ dari _TOTAL_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(pencarian dari _MAX_ data)"
            },
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
                    @if ($dmenu == 'rpnphd' || $dmenu == 'rpmelh')
                    text: '<i class="fas fa-file-excel me-1 text-lg text-success"> </i><span class="font-weight-bold"> Excel',
                    className: 'buttons-excel',
                    action: function() {
                        const params = new URLSearchParams();
                        document.querySelectorAll('input[name^="f"]').forEach((input) => {
                            params.set(input.name, input.value || '');
                        });

                        const exportUrl = '{{ route('report.excel-images', ['dmenu' => $dmenu]) }}';
                        window.location.href = exportUrl + '?' + params.toString();
                    }
                    @else
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

                        // Convert IMAGE formulas from inline string text to actual Excel formulas.
                        sheet.querySelectorAll('row c').forEach((cellNode) => {
                            let textNode = cellNode.querySelector('is t');
                            if (!textNode) {
                                return;
                            }

                            let rawCellText = (textNode.textContent || '').trim();
                            if (!/^=IMAGE\(".*"\)$/i.test(rawCellText)) {
                                return;
                            }

                            let formulaNode = sheet.createElement('f');
                            formulaNode.textContent = rawCellText.substring(1);

                            let valueNode = sheet.createElement('v');
                            valueNode.textContent = '0';

                            let inlineStringNode = cellNode.querySelector('is');
                            if (inlineStringNode) {
                                inlineStringNode.remove();
                            }

                            cellNode.removeAttribute('t');
                            cellNode.appendChild(formulaNode);
                            cellNode.appendChild(valueNode);
                        });
                    },
                    customizeData: function(data) {
                        const documentationIndex = data.header.findIndex((header) =>
                            String(header).toLowerCase().includes('dokumentasi')
                        );

                        if (documentationIndex === -1) {
                            return;
                        }

                        data.body.forEach((row) => {
                            const rawValue = String(row[documentationIndex] || '');
                            const urls = rawValue.split(/\n+/).map((value) => value.trim()).filter((value) => value !== '');
                            const imageUrl = urls.find((url) => /^https?:\/\//i.test(url));

                            if (!imageUrl) {
                                row[documentationIndex] = '-';
                                return;
                            }

                            // Excel IMAGE supports one image per formula; use the first documentation image.
                            row[documentationIndex] = '=IMAGE("' + imageUrl.replace(/"/g, '""') + '")';
                        });
                    },
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                return exportBodyFormatter(data, node, 'excel');
                            }
                        }
                    },
                    @endif
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf me-1 text-lg text-danger"> </i><span class="font-weight-bold"> PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function(doc) {
                        doc.pageMargins = [20, 28, 20, 24];
                        doc.defaultStyle = {
                            fontSize: 9,
                            color: '#1f2937'
                        };

                        if (doc.content && doc.content[0]) {
                            doc.content[0] = {
                                stack: [{
                                        text: displayExportTitle,
                                        fontSize: 15,
                                        bold: true,
                                        alignment: 'center'
                                    },
                                ],
                                margin: [0, 0, 0, 10]
                            };
                        }

                        const tableBlock = doc.content.find((item) => item.table);
                        if (!tableBlock || !tableBlock.table || !tableBlock.table.body) {
                            return;
                        }

                        const body = tableBlock.table.body;
                        const colCount = body[0] ? body[0].length : 0;

                        if (colCount > 0) {
                            body[0] = body[0].map((headerCell) => ({
                                text: typeof headerCell === 'string' ? headerCell : (headerCell && headerCell.text ? headerCell.text : ''),
                                alignment: 'center',
                                bold: true,
                                color: '#ffffff',
                                fillColor: '#1f3b5c',
                                margin: [0, 5, 0, 5]
                            }));

                            const widths = Array(colCount).fill('*');
                            widths[0] = 22;
                            widths[colCount - 1] = 86;

                            const normalizedHeaders = body[0].map((cell) => {
                                const text = typeof cell === 'string' ? cell : (cell && cell.text ? cell.text : '');
                                return String(text).trim().toLowerCase();
                            });

                            const indexTanggalPengajuan = normalizedHeaders.findIndex((h) => h === 'tanggal pengajuan');
                            const indexWaktuPengadaan = normalizedHeaders.findIndex((h) => h === 'waktu pengadaan');
                            const indexWaktuPengerjaan = normalizedHeaders.findIndex((h) => h === 'waktu pengerjaan');
                            const indexArea = normalizedHeaders.findIndex((h) => h === 'area');
                            const indexPekerjaan = normalizedHeaders.findIndex((h) => h === 'pekerjaan');
                            const indexPetugas = normalizedHeaders.findIndex((h) => h === 'petugas');

                            if (indexTanggalPengajuan >= 0) {
                                widths[indexTanggalPengajuan] = 'auto';
                            }

                            if (indexArea >= 0) {
                                widths[indexArea] = 'auto';
                            }

                            if (indexWaktuPengadaan >= 0) {
                                widths[indexWaktuPengadaan] = 'auto';
                            }
                            if (indexWaktuPengerjaan >= 0) {
                                widths[indexWaktuPengerjaan] = 'auto';
                            }

                            // Keep wide-text column flexible while others follow content.
                            if (indexPekerjaan >= 0) {
                                widths[indexPekerjaan] = '*';
                            }

                            if (indexPetugas >= 0) {
                                widths[indexPetugas] = 'auto';
                            }

                            tableBlock.table.widths = widths;
                        }

                        tableBlock.layout = {
                            hLineColor: function() {
                                return '#d1d5db';
                            },
                            vLineColor: function() {
                                return '#d1d5db';
                            },
                            hLineWidth: function() {
                                return 0.6;
                            },
                            vLineWidth: function() {
                                return 0.6;
                            },
                            paddingLeft: function() {
                                return 4;
                            },
                            paddingRight: function() {
                                return 4;
                            },
                            paddingTop: function() {
                                return 3;
                            },
                            paddingBottom: function() {
                                return 3;
                            },
                            fillColor: function(rowIndex) {
                                if (rowIndex === 0) {
                                    return null;
                                }
                                return rowIndex % 2 === 0 ? '#f8fafc' : null;
                            }
                        };

                        for (let rowIndex = 1; rowIndex < body.length; rowIndex++) {
                            const row = body[rowIndex];
                            for (let colIndex = 0; colIndex < row.length; colIndex++) {
                                const cell = row[colIndex];
                                const cellText = typeof cell === 'string' ? cell : (cell && cell.text ? cell.text : '');

                                if (!String(cellText).startsWith('__IMGSET__')) {
                                    continue;
                                }

                                const sources = String(cellText)
                                    .replace('__IMGSET__', '')
                                    .split('||')
                                    .map((value) => value.trim())
                                    .filter((value) => value !== '');

                                const uniqueSources = Array.from(new Set(sources)).slice(0, 4);

                                const imageNodes = uniqueSources
                                    .map((src) => exportPreviewImages[src])
                                    .filter((dataUrl) => !!dataUrl)
                                    .map((dataUrl) => ({
                                        image: dataUrl,
                                        fit: [44, 44],
                                        alignment: 'left',
                                        margin: [0, 0, 0, 0]
                                    }));

                                if (imageNodes.length > 0) {
                                    const imageRows = [];
                                    for (let i = 0; i < imageNodes.length; i += 2) {
                                        imageRows.push([
                                            imageNodes[i],
                                            imageNodes[i + 1] || {
                                                text: ''
                                            }
                                        ]);
                                    }

                                    row[colIndex] = {
                                        table: {
                                            widths: [43, 43],
                                            body: imageRows
                                        },
                                        layout: 'noBorders',
                                        margin: [0, 0, 0, 0]
                                    };
                                } else {
                                    row[colIndex] = '-';
                                }
                            }
                        }
                    },
                    exportOptions: {
                        columns: ':visible',
                        stripHtml: false,
                        format: {
                            body: function(data, row, column, node) {
                                return exportBodyFormatter(data, node, 'pdf');
                            }
                        }
                    },
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print me-1 text-lg text-info"> </i><span class="font-weight-bold"> Print',
                    action: function(e, dt) {
                        printWithoutNewTab(dt, exportReportTitle);
                    },
                },
            ]
        });
        //set color button datatables
        $('.dt-button').addClass('btn btn-secondary');
        $('.dt-button').removeClass('dt-button');
        //check authorize button datatables
        <?= $authorize->excel == '0' ? "$('.buttons-excel').remove();" : '' ?>
        <?= $authorize->pdf == '0' ? "$('.buttons-pdf').remove();" : '' ?>
        <?= $authorize->print == '0' ? "$('.buttons-print').remove();" : '' ?>
        //function delete
        function deleteData(name, msg) {
            pesan = confirm('Apakah Anda Yakin ' + msg + ' Data ' + name + ' ini ?');
            if (pesan) return true
            else return false
        }

        function showReportImage(carouselId, index) {
            setTimeout(function() {
                const carouselEl = document.getElementById(carouselId);
                if (!carouselEl || typeof bootstrap === 'undefined' || !bootstrap.Carousel) {
                    return;
                }
                const carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl, {
                    interval: false,
                    ride: false
                });
                carousel.to(index);
            }, 150);
        }
    </script>
@endpush
