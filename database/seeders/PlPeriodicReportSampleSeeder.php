<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PlPeriodicReportSampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workerId = DB::table('users')
            ->whereRaw("FIND_IN_SET('ptgoff', REPLACE(idroles, ' ', '')) OR FIND_IN_SET('ptgspu', REPLACE(idroles, ' ', ''))")
            ->value('id');

        $periodicId = DB::table('ms_periodic')->value('id');
        $areaId = DB::table('ms_area')->value('id');

        if (!$workerId || !$periodicId || !$areaId) {
            return;
        }

        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();

        // Cleanup old sample rows to avoid duplicates when this seeder is re-run.
        $sampleHeaderIds = DB::table('pl_periodic_header')
            ->where('keterangan', 'Sample laporan pemeliharaan')
            ->pluck('id');

        if ($sampleHeaderIds->isNotEmpty()) {
            $sampleDetailIds = DB::table('pl_periodic_detail')
                ->whereIn('header_id', $sampleHeaderIds)
                ->pluck('id');

            if ($sampleDetailIds->isNotEmpty()) {
                $sampleItemIds = DB::table('pl_periodic_items')
                    ->whereIn('detail_id', $sampleDetailIds)
                    ->pluck('id');

                if ($sampleItemIds->isNotEmpty()) {
                    DB::table('pl_documentation')->whereIn('periodic_item_id', $sampleItemIds)->delete();
                }

                DB::table('pl_periodic_items')->whereIn('detail_id', $sampleDetailIds)->delete();
            }

            DB::table('pl_periodic_detail')->whereIn('header_id', $sampleHeaderIds)->delete();
            DB::table('pl_periodic_header')->whereIn('id', $sampleHeaderIds)->delete();
        }

        $headerId = DB::table('pl_periodic_header')->insertGetId([
            'tahun' => (int) $now->format('Y'),
            'keterangan' => 'Sample laporan pemeliharaan',
            'is_active' => '1',
            'created_at' => $now,
            'updated_at' => $now,
            'user_create' => 'system',
            'user_update' => 'system',
        ]);

        $detailMingguanAId = DB::table('pl_periodic_detail')->insertGetId([
            'header_id' => $headerId,
            'periodic_id' => $periodicId,
            'area_id' => $areaId,
            'worker_id' => $workerId,
            'periode' => 'mingguan',
            'cycle' => 4,
            'start_plan_date' => $monthStart->copy()->subMonthsNoOverflow(2)->toDateString(),
            'generated_until' => $monthStart->copy()->endOfMonth()->toDateString(),
            'is_active' => '1',
            'created_at' => $now,
            'updated_at' => $now,
            'user_create' => 'system',
            'user_update' => 'system',
        ]);

        $detailMingguanBId = DB::table('pl_periodic_detail')->insertGetId([
            'header_id' => $headerId,
            'periodic_id' => $periodicId,
            'area_id' => $areaId,
            'worker_id' => $workerId,
            'periode' => 'mingguan',
            'cycle' => 4,
            'start_plan_date' => $monthStart->copy()->subMonthNoOverflow()->toDateString(),
            'generated_until' => $monthStart->copy()->addMonthNoOverflow()->endOfMonth()->toDateString(),
            'is_active' => '1',
            'created_at' => $now,
            'updated_at' => $now,
            'user_create' => 'system',
            'user_update' => 'system',
        ]);

        $detailBulananId = DB::table('pl_periodic_detail')->insertGetId([
            'header_id' => $headerId,
            'periodic_id' => $periodicId,
            'area_id' => $areaId,
            'worker_id' => $workerId,
            'periode' => 'bulanan',
            'cycle' => 1,
            'start_plan_date' => $monthStart->copy()->subMonthsNoOverflow(3)->toDateString(),
            'generated_until' => $monthStart->copy()->addMonthNoOverflow()->endOfMonth()->toDateString(),
            'is_active' => '1',
            'created_at' => $now,
            'updated_at' => $now,
            'user_create' => 'system',
            'user_update' => 'system',
        ]);

        $items = [];

        // Weekly detail A: 3 months x 4 dates each month.
        for ($m = 2; $m >= 0; $m--) {
            $baseMonth = $monthStart->copy()->subMonthsNoOverflow($m);
            foreach ([2, 9, 16, 23] as $offsetDay) {
                $planned = $baseMonth->copy()->addDays($offsetDay);
                $items[] = [
                    'detail_id' => $detailMingguanAId,
                    'planned_date' => $planned->toDateString(),
                    'realization_date' => $planned->copy()->addDay()->toDateString(),
                    'is_active' => '1',
                    'created_at' => $now,
                    'updated_at' => $now,
                    'user_create' => 'system',
                    'user_update' => 'system',
                ];
            }
        }

        // Weekly detail B: current month with mixed realization status.
        foreach ([4, 11, 18, 25] as $index => $offsetDay) {
            $planned = $now->copy()->startOfMonth()->addDays($offsetDay);
            $items[] = [
                'detail_id' => $detailMingguanBId,
                'planned_date' => $planned->toDateString(),
                'realization_date' => $index < 2 ? $planned->copy()->addDays(2)->toDateString() : null,
                'is_active' => '1',
                'created_at' => $now,
                'updated_at' => $now,
                'user_create' => 'system',
                'user_update' => 'system',
            ];
        }

        // Monthly detail: 5 months of schedule.
        for ($m = 3; $m >= -1; $m--) {
            $baseMonth = $m >= 0
                ? $monthStart->copy()->subMonthsNoOverflow($m)
                : $monthStart->copy()->addMonthsNoOverflow(abs($m));
            $planned = $baseMonth->copy()->addDays(5);
            $items[] = [
                'detail_id' => $detailBulananId,
                'planned_date' => $planned->toDateString(),
                'realization_date' => $m >= 0 ? $planned->copy()->addDay()->toDateString() : null,
                'is_active' => '1',
                'created_at' => $now,
                'updated_at' => $now,
                'user_create' => 'system',
                'user_update' => 'system',
            ];
        }

        DB::table('pl_periodic_items')->insert($items);

        $samplePeriodicItemIds = DB::table('pl_periodic_items')
            ->whereIn('detail_id', [$detailMingguanAId, $detailMingguanBId, $detailBulananId])
            ->orderBy('planned_date')
            ->limit(3)
            ->pluck('id')
            ->values();

        if ($samplePeriodicItemIds->isNotEmpty()) {
            // 1x1 transparent PNG used as sample documentation image.
            $pngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Yx8xGQAAAAASUVORK5CYII=');
            Storage::disk('public')->put('pl_documentation/sample-doc-1.png', $pngBytes);
            Storage::disk('public')->put('pl_documentation/sample-doc-2.png', $pngBytes);
            Storage::disk('public')->put('pl_documentation/sample-doc-3.png', $pngBytes);

            $docFiles = [
                'pl_documentation/sample-doc-1.png',
                'pl_documentation/sample-doc-2.png',
                'pl_documentation/sample-doc-3.png',
            ];

            $docPayload = [];
            foreach ($samplePeriodicItemIds as $idx => $itemId) {
                $docPayload[] = [
                    'non_periodic_id' => null,
                    'periodic_item_id' => $itemId,
                    'file' => $docFiles[$idx] ?? $docFiles[0],
                    'description' => 'Dokumentasi sample item #' . $itemId,
                    'is_active' => '1',
                    'created_at' => $now->copy()->addSeconds($idx),
                    'updated_at' => $now->copy()->addSeconds($idx),
                    'user_create' => 'system',
                    'user_update' => 'system',
                ];
            }

            DB::table('pl_documentation')->insert($docPayload);
        }
    }
}
