<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index($data)
    {
        //list data table
        $data['table_filter'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'filter' => '1'])->get();
        //check data filter
        if ($data['table_filter']) {
            return view($data['url'], $data);
        } else {
            //if not exist
            $data['url_menu'] = 'error';
            $data['title_group'] = 'Error';
            $data['title_menu'] = 'Error';
            $data['errorpages'] = 'Not Found!';
            //return error page
            return view("pages.errorpages", $data);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store($data)
    {
        //list data
        $data['table_filter'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'filter' => '1'])->get();
        $data['table_query'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'field' => 'query'])->first();
        //get data validate
        foreach ($data['table_filter']->map(function ($item) {
            return (array) $item;
        }) as $item) {
            if ($item['type'] == 'date2') {
                if ($item['validate'] == null) {
                    $validate['fr' . $item['field']] = '';
                    $validate['to' . $item['field']] = '';
                } else {
                    $validate['fr' . $item['field']] = $item['validate'];
                    $validate['to' . $item['field']] = $item['validate'];
                }
            } else {
                if ($item['validate'] == null) {
                    $validate[$item['field']] = '';
                } else {
                    $validate[$item['field']] = $item['validate'];
                }
            }
        }
        //validasi data
        $attributes = request()->validate(
            $validate,
            [
                'required' => ':attribute tidak boleh kosong',
                'unique' => ':attribute sudah ada',
                'min' => ':attribute minimal :min karakter',
                'max' => ':attribute maksimal :max karakter',
                'email' => 'format :attribute salah',
                'mimes' => ':attribute file harus format png,jpg,jpeg',
                'between' => ':attribute diisi antara :min sampai :max'
            ]
        );
        $where = [];
        foreach ($attributes as  $column => $value) {
            if ($value == null) {
                if (substr($column, 0, 2) == 'fr') {
                    $where[$column] = "2000-01-01";
                } else if (substr($column, 0, 2) == 'to') {
                    $where[$column] = "2100-01-01";
                } else {
                    $where[$column] = "%";
                }
            } else {
                $where[$column] = "{$value}";
            }
        }
        //check authorization
        if (@$where['rules']) {
            //check athorization access rules
            if ($data['authorize']->rules == '1') {
                $where['rules'] = session('user')->idroles;
            } else {
                $where['rules'] = '%';
            }
        }
        //list data
        $data['table_result'] = DB::select($data['table_query']->query, $where);
        $data['table_class'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'class' => 'filter'])->first();
        $data['filter'] = $where;
        // check data result
        if ($data['table_result']) {
            // return page menu
            return view($data['url'], $data);
        } else {
            // return page menu
            return view($data['url'], $data);
        }
    }
    /**
     * Display the specified resource.
     */
    public function result($data)
    {
        if ($data['table_result']) {
            // return page menu
            return view($data['url'], $data);
        } else {
            //if not exist
            $data['url_menu'] = 'error';
            $data['title_group'] = 'Error';
            $data['title_menu'] = 'Error';
            $data['errorpages'] = 'Not Found!';
            //return error page
            return view("pages.errorpages", $data);
        }
    }

    public function exportExcelImages(Request $request, string $dmenu)
    {
        $queryRow = DB::table('sys_table')
            ->where(['gmenu' => 'report', 'dmenu' => $dmenu, 'field' => 'query'])
            ->first();

        if (!$queryRow || empty($queryRow->query)) {
            abort(404, 'Query report tidak ditemukan.');
        }

        $menuRow = DB::table('sys_dmenu')
            ->where('dmenu', $dmenu)
            ->first();

        $where = [
            'frdate' => $request->query('ffrdate', '2000-01-01'),
            'todate' => $request->query('ftodate', '2100-01-01'),
            'area' => $request->query('farea', '%'),
            'petugas' => $request->query('fpetugas', '%'),
        ];

        if ($dmenu === 'rpnphd') {
            $where['status'] = $request->query('fstatus', '%');
        }

        $tableResult = DB::select($queryRow->query, $where);

        $reportTitle = $this->buildReportTitleByMenu($dmenu, $where['frdate'], $where['todate']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(Str::limit($menuRow->name ?? 'Report', 31, ''));

        $titleRow = 1;
        $headerRow = 2;
        $dataStartRow = 3;
        $headers = !empty($tableResult) ? array_keys((array) $tableResult[0]) : [];
        $lastHeaderColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers) + 1);

        $sheet->mergeCells('A' . $titleRow . ':' . $lastHeaderColumn . $titleRow);
        $sheet->setCellValue('A' . $titleRow, $reportTitle);
        $sheet->getStyle('A' . $titleRow)->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A' . $titleRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $titleRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $titleRow . ':' . $lastHeaderColumn . $titleRow)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF2F5597');
        $sheet->getStyle('A' . $titleRow . ':' . $lastHeaderColumn . $titleRow)->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getRowDimension($titleRow)->setRowHeight(30);

        if (empty($tableResult)) {
            $sheet->setCellValue('A' . $headerRow, 'Data tidak ditemukan.');
        } else {
            $sheet->setCellValue('A' . $headerRow, 'No');
            foreach ($headers as $index => $header) {
                $sheet->setCellValueByColumnAndRow($index + 2, $headerRow, $this->normalizeHeaderLabel($header));
            }

            $headerStyleRange = 'A' . $headerRow . ':' . $sheet->getHighestColumn() . $headerRow;
            $sheet->getStyle($headerStyleRange)->getFont()->setBold(true);
            $sheet->getStyle($headerStyleRange)->getFont()->setSize(13);
            $sheet->getStyle($headerStyleRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($headerStyleRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($headerStyleRange)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FF1F3B5C');
            $sheet->getStyle($headerStyleRange)->getFont()->getColor()->setARGB('FFFFFFFF');
            $sheet->getRowDimension($headerRow)->setRowHeight(24);

            $imageColumnWidths = [];

            $rowNumber = $dataStartRow;
            foreach ($tableResult as $iteration => $detail) {
                $detailArray = (array) $detail;
                $sheet->setCellValue('A' . $rowNumber, $iteration + 1);

                foreach ($headers as $index => $header) {
                    $cellColumn = $index + 2;
                    $cellCoordinate = $sheet->getCellByColumnAndRow($cellColumn, $rowNumber)->getCoordinate();
                    $value = isset($detailArray[$header]) ? (string) $detailArray[$header] : '';

                    if (Str::startsWith($header, 'IMG')) {
                        $imagePaths = $this->extractImagePaths($value);
                        $layout = $this->embedImagesToCell($sheet, $cellCoordinate, $imagePaths);

                        if (($layout['requiredWidthPixels'] ?? 0) > 0) {
                            $imageColumnWidths[$cellColumn] = max(
                                $imageColumnWidths[$cellColumn] ?? 0,
                                $layout['requiredWidthPixels']
                            );
                        }

                        if ($layout['count'] > 0) {
                            // Sesuaikan tinggi baris agar semua gambar dalam sel terlihat rapi.
                            $sheet->getRowDimension($rowNumber)->setRowHeight($layout['rowHeight']);
                            $sheet->setCellValue($cellCoordinate, '');
                        } else {
                            $sheet->setCellValue($cellCoordinate, '-');
                        }
                    } else {
                        $sheet->setCellValue($cellCoordinate, $value === '' ? '-' : $value);
                    }
                }

                $rowNumber++;
            }

            foreach (range(1, count($headers) + 1) as $columnIndex) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }

            // AutoSize tidak menghitung drawing image, jadi kolom IMG disesuaikan manual dari pixel layout.
            foreach ($imageColumnWidths as $columnIndex => $pixelWidth) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(false);
                $sheet->getColumnDimension($columnLetter)->setWidth($this->pixelsToColumnWidth($pixelWidth));
            }

            $lastDataRow = max($dataStartRow, $rowNumber - 1);
            $dataRange = 'A' . $dataStartRow . ':' . $sheet->getHighestColumn() . $lastDataRow;
            $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
            $sheet->getStyle($dataRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            // Zebra rows to improve readability.
            for ($row = $dataStartRow; $row <= $lastDataRow; $row++) {
                if (($row - $dataStartRow) % 2 === 1) {
                    $sheet->getStyle('A' . $row . ':' . $sheet->getHighestColumn() . $row)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFF7F9FC');
                }
            }

            // Clean grid border around header and body.
            $tableRange = 'A' . $headerRow . ':' . $sheet->getHighestColumn() . $lastDataRow;
            $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->getColor()->setARGB('FFD9D9D9');

            // Keep title + header visible while scrolling.
            $sheet->freezePane('A' . $dataStartRow);
        }

        $fileName = Str::slug($reportTitle, '_') . '_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function normalizeHeaderLabel(string $header): string
    {
        if (Str::startsWith($header, 'IMG')) {
            return trim(substr($header, 4));
        }

        if (Str::startsWith($header, 'CDT')) {
            return substr($header, 6) . '-' . substr($header, 3, 2);
        }

        if (Str::startsWith($header, 'CST')) {
            return trim(substr($header, 4));
        }

        return $header;
    }

    private function extractImagePaths(string $rawValue): array
    {
        $rawValue = trim($rawValue);
        if ($rawValue === '' || $rawValue === '-') {
            return [];
        }

        $paths = preg_split('/\s*(?:\|\||\||,)\s*/', $rawValue, -1, PREG_SPLIT_NO_EMPTY);
        $paths = array_values(array_filter(array_map(function ($path) {
            $path = trim((string) $path);
            if ($path === '') {
                return null;
            }

            if (str_contains($path, '@@')) {
                $parts = explode('@@', $path, 2);
                $path = trim($parts[1] ?? '');
            }

            return $path !== '' ? $path : null;
        }, $paths)));

        return array_values(array_unique($paths));
    }

    private function embedImagesToCell($sheet, string $cellCoordinate, array $imagePaths): array
    {
        $embeddedCount = 0;
        $maxImages = 4;
        $columnsPerRow = 2;
        $imageWidth = 92;
        $imageHeight = 92;
        $horizontalSpacing = 20;
        $verticalSpacing = 10;
        $paddingX = 6;
        $paddingY = 6;

        foreach (array_slice($imagePaths, 0, $maxImages) as $index => $path) {
            $imageResource = $this->loadImageResource($path);
            if (!$imageResource) {
                continue;
            }

            $gridColumn = $index % $columnsPerRow;
            $gridRow = intdiv($index, $columnsPerRow);

            $drawing = new MemoryDrawing();
            $drawing->setName('Dokumentasi');
            $drawing->setDescription('Dokumentasi');
            $drawing->setImageResource($imageResource);
            $drawing->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
            $drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
            $drawing->setCoordinates($cellCoordinate);
            $drawing->setResizeProportional(false);
            $drawing->setOffsetX($paddingX + ($gridColumn * ($imageWidth + $horizontalSpacing)));
            $drawing->setOffsetY($paddingY + ($gridRow * ($imageHeight + $verticalSpacing)));
            $drawing->setWidth($imageWidth);
            $drawing->setHeight($imageHeight);
            $drawing->setWorksheet($sheet);

            $embeddedCount++;
        }

        if ($embeddedCount === 0) {
            return [
                'count' => 0,
                'rowHeight' => -1,
                'requiredWidthPixels' => 0,
            ];
        }

        $rowsUsed = (int) ceil($embeddedCount / $columnsPerRow);
        $columnsUsed = min($columnsPerRow, $embeddedCount);
        $rowHeight = ($rowsUsed * $imageHeight) + (($rowsUsed - 1) * $verticalSpacing) + ($paddingY * 2);
        $requiredWidthPixels = ($columnsUsed * $imageWidth) + (($columnsUsed - 1) * $horizontalSpacing) + ($paddingX * 2);

        return [
            'count' => $embeddedCount,
            'rowHeight' => $rowHeight,
            'requiredWidthPixels' => $requiredWidthPixels,
        ];
    }

    private function pixelsToColumnWidth(int $pixels): float
    {
        if ($pixels <= 12) {
            return 2;
        }

        // Approximation used by Excel column width conversion.
        return round(($pixels - 5) / 7, 2);
    }

    private function buildReportTitleByMenu(string $dmenu, string $fromDate, string $toDate): string
    {
        $baseTitle = match ($dmenu) {
            'rpmelh' => 'Laporan Pemeliharaan',
            'rpnphd' => 'Laporan Dokumentasi Lapangan',
            default => 'Laporan',
        };

        try {
            $from = Carbon::parse($fromDate);
            $to = Carbon::parse($toDate);

            if ($from->format('m-Y') === $to->format('m-Y')) {
                return $baseTitle . ' ' . $this->formatMonthYearIndo($to);
            }

            return $baseTitle . ' ' . $this->formatMonthYearIndo($from) . ' - ' . $this->formatMonthYearIndo($to);
        } catch (\Throwable $e) {
            return $baseTitle;
        }
    }

    private function formatMonthYearIndo(Carbon $date): string
    {
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return ($monthNames[(int) $date->format('n')] ?? $date->format('F')) . ' ' . $date->format('Y');
    }

    private function loadImageResource(string $path)
    {
        $normalizedPath = str_replace('\\', '/', trim($path));
        $normalizedPath = preg_replace('#^/?public/#', '', $normalizedPath);
        $normalizedPath = preg_replace('#^/?storage/#', '', $normalizedPath);
        $normalizedPath = ltrim($normalizedPath, '/');

        if ($normalizedPath === '') {
            return null;
        }

        $absolutePath = storage_path('app/public/' . $normalizedPath);
        if (!is_file($absolutePath)) {
            return null;
        }

        try {
            $content = file_get_contents($absolutePath);
            if ($content === false) {
                return null;
            }

            $imageResource = @imagecreatefromstring($content);
            return $imageResource ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
