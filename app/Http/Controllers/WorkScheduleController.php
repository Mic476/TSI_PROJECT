<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WorkScheduleController extends Controller
{
    public function complete(Request $request)
    {
        $attributes = $request->validate(
            [
                'non_periodic_id' => 'nullable|integer|exists:pl_non_periodic,id',
                'periodic_item_id' => 'nullable|integer|exists:pl_periodic_items,id',
                'realization_date' => 'nullable|date',
                'documentation' => 'required|array|min:1',
                'documentation.*' => 'file|mimes:jpg,jpeg,png,webp,jfif,heic,heif,pdf|max:10240',
                'description' => 'nullable|string|max:500',
            ],
            [
                'required' => ':attribute tidak boleh kosong',
                'mimes' => ':attribute harus berupa file jpg, jpeg, png, webp, jfif, heic, heif, atau pdf',
                'max' => ':attribute maksimal :max',
            ]
        );

        // Determine which type based on which ID is provided
        if (empty($attributes['non_periodic_id']) && empty($attributes['periodic_item_id'])) {
            Session::flash('class', 'danger');
            Session::flash('message', 'ID pekerjaan harus diisi.');
            return redirect()->back();
        }

        $itemType = !empty($attributes['periodic_item_id']) ? 'periodic' : 'non_periodic';
        $itemId = $itemType === 'periodic' ? $attributes['periodic_item_id'] : $attributes['non_periodic_id'];
        $username = session('username');

        $documentationInput = $request->file('documentation');
        $documentationFiles = [];

        if ($documentationInput instanceof UploadedFile) {
            $documentationFiles = [$documentationInput];
        } elseif (is_array($documentationInput)) {
            $documentationFiles = array_filter($documentationInput, fn($file) => $file instanceof UploadedFile);
        }

        if (empty($documentationFiles)) {
            Session::flash('class', 'danger');
            Session::flash('message', 'Dokumentasi wajib diisi.');
            return redirect()->back()->withInput();
        }

        Validator::make(
            ['documentation' => $documentationFiles],
            [
                'documentation' => 'required|array|min:1',
                'documentation.*' => 'file|mimes:jpg,jpeg,png,webp,jfif,heic,heif,pdf|max:10240',
            ],
            [
                'required' => ':attribute tidak boleh kosong',
                'mimes' => ':attribute harus berupa file jpg, jpeg, png, webp, jfif, heic, heif, atau pdf',
                'max' => ':attribute maksimal :max',
            ]
        )->validate();

        DB::beginTransaction();
        try {
            foreach ($documentationFiles as $documentationFile) {
                $filePath = $documentationFile->store('pl_documentation', 'public');

                DB::table('pl_documentation')->insert([
                    'non_periodic_id' => $itemType === 'non_periodic' ? $itemId : null,
                    'periodic_item_id' => $itemType === 'periodic' ? $itemId : null,
                    'file' => $filePath,
                    'description' => $attributes['description'] ?? null,
                    'is_active' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'user_create' => $username,
                    'user_update' => $username,
                ]);
            }

            if ($itemType === 'periodic') {
                // Update pl_periodic_items
                DB::table('pl_periodic_items')
                    ->where('id', $itemId)
                    ->update([
                        'realization_date' => $attributes['realization_date'] ?? now()->toDateString(),
                        'updated_at' => now(),
                        'user_update' => $username,
                    ]);
            } else {
                // Update pl_non_periodic - petugas konfirmasi selesai
                $currentNonPeriodic = DB::table('pl_non_periodic')
                    ->where('id', $itemId)
                    ->first();

                $nonPeriodicUpdate = [
                    'request_status' => 'verifikasi',
                    'petugas_confirmed_at' => now(),
                    'updated_at' => now(),
                    'user_update' => $username,
                ];

                // Isi waktu akhir pengerjaan dengan waktu petugas konfirmasi
                if ($currentNonPeriodic && !empty($currentNonPeriodic->pengerjaan_started_at) && empty($currentNonPeriodic->pengerjaan_ended_at)) {
                    $nonPeriodicUpdate['pengerjaan_ended_at'] = $nonPeriodicUpdate['petugas_confirmed_at'];
                }

                DB::table('pl_non_periodic')
                    ->where('id', $itemId)
                    ->update($nonPeriodicUpdate);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Session::flash('class', 'danger');
            Session::flash('message', 'Gagal menyimpan dokumentasi: ' . $e->getMessage());
            return redirect()->back();
        }

        Session::flash('class', 'success');
        Session::flash('message', 'Pekerjaan berhasil dikonfirmasi dan dokumentasi tersimpan.');
        return redirect()->back();
    }

    public function verify(Request $request)
    {
        $attributes = $request->validate(
            [
                'non_periodic_id' => 'nullable|integer|exists:pl_non_periodic,id',
                'periodic_item_id' => 'nullable|integer|exists:pl_periodic_items,id',
                'decision' => 'required|in:approve,reject',
                'notes' => ($request->input('decision') === 'reject' ? 'required' : 'nullable') . '|string|max:500',
                'revision_attachments' => 'nullable|array',
                'revision_attachments.*' => 'file|mimes:jpg,jpeg,png,webp,jfif,heic,heif,pdf|max:10240',
            ],
            [
                'required' => ':attribute tidak boleh kosong',
                'in' => ':attribute tidak valid',
                'mimes' => ':attribute harus berupa file jpg, jpeg, png, webp, jfif, heic, heif, atau pdf',
                'max' => ':attribute maksimal :max',
            ]
        );

        // Determine which type based on which ID is provided
        if (empty($attributes['non_periodic_id']) && empty($attributes['periodic_item_id'])) {
            Session::flash('class', 'danger');
            Session::flash('message', 'ID pekerjaan harus diisi.');
            return redirect()->back();
        }

        $itemType = !empty($attributes['periodic_item_id']) ? 'periodic' : 'non_periodic';
        $itemId = $itemType === 'periodic' ? $attributes['periodic_item_id'] : $attributes['non_periodic_id'];
        $username = session('username');

        DB::beginTransaction();
        try {
            if ($attributes['decision'] === 'approve') {
                // Approve - set to completed/approved
                if ($itemType === 'periodic') {
                    DB::table('pl_periodic_items')
                        ->where('id', $itemId)
                        ->update([
                            'updated_at' => now(),
                            'user_update' => $username,
                        ]);
                } else {
                    // Non-periodic: User verifikasi approve
                    $currentNonPeriodic = DB::table('pl_non_periodic')
                        ->where('id', $itemId)
                        ->first();

                    $nonPeriodicUpdate = [
                        'request_status' => 'completed',
                        'realization_date' => now()->toDateString(),
                        'updated_at' => now(),
                        'user_update' => $username,
                    ];

                    // Saat user approve, selalu update pengerjaan_ended_at dengan waktu petugas_confirmed_at terbaru
                    if ($currentNonPeriodic && !empty($currentNonPeriodic->petugas_confirmed_at)) {
                        $nonPeriodicUpdate['pengerjaan_ended_at'] = $currentNonPeriodic->petugas_confirmed_at;
                    } elseif ($currentNonPeriodic && !empty($currentNonPeriodic->pengerjaan_started_at) && empty($currentNonPeriodic->pengerjaan_ended_at)) {
                        // Fallback jika petugas_confirmed_at kosong
                        $nonPeriodicUpdate['pengerjaan_ended_at'] = now();
                    }

                    if (!empty($attributes['notes'])) {
                        $nonPeriodicUpdate['requester_note'] = $attributes['notes'];
                    }

                    DB::table('pl_non_periodic')
                        ->where('id', $itemId)
                        ->update($nonPeriodicUpdate);
                }
                $message = 'Verifikasi pekerjaan berhasil. Status diubah menjadi selesai.';
            } else {
                // Reject - set to revisi
                if ($itemType === 'periodic') {
                    // For periodic items, reset realization_date if rejected
                    DB::table('pl_periodic_items')
                        ->where('id', $itemId)
                        ->update([
                            'realization_date' => null,
                            'updated_at' => now(),
                            'user_update' => $username,
                        ]);
                } else {
                    // Non-periodic: User reject - kembali ke petugas untuk revisi
                    // Bersihkan petugas_confirmed_at agar petugas bisa konfirmasi ulang
                    $revisionAttachments = [];
                    $revisionAttachmentInput = $request->file('revision_attachments', []);
                    if ($revisionAttachmentInput instanceof UploadedFile) {
                        $revisionAttachmentInput = [$revisionAttachmentInput];
                    }
                    if (is_array($revisionAttachmentInput)) {
                        foreach ($revisionAttachmentInput as $revisionAttachmentFile) {
                            if ($revisionAttachmentFile instanceof UploadedFile) {
                                $revisionAttachments[] = $revisionAttachmentFile->store('pl_revision_attachment', 'public');
                            }
                        }
                    }

                    DB::table('pl_non_periodic')
                        ->where('id', $itemId)
                        ->update([
                            'request_status' => 'revisi',
                            'requester_note' => $attributes['notes'] ?? null,
                            'revision_attachment' => !empty($revisionAttachments) ? json_encode($revisionAttachments) : null,
                            'petugas_confirmed_at' => null,
                            'pengerjaan_ended_at' => null,
                            'updated_at' => now(),
                            'user_update' => $username,
                        ]);
                }
                $message = 'Pekerjaan dikembalikan untuk revisi.';
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Session::flash('class', 'danger');
            Session::flash('message', 'Gagal memproses verifikasi: ' . $e->getMessage());
            return redirect()->back();
        }

        Session::flash('class', 'success');
        Session::flash('message', $message);
        return redirect()->back();
    }

    public function updateSchedule(Request $request)
    {
        $attributes = $request->validate(
            [
                'schedule_id' => 'required|integer',
                'plan_date' => 'required|date',
                'realization_date' => 'nullable|date',
            ],
            [
                'required' => ':attribute tidak boleh kosong',
                'date' => ':attribute harus berupa tanggal yang valid',
            ]
        );

        $schedule = DB::table('pl_work_schedule')->where('id', $attributes['schedule_id'])->first();
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal pekerjaan tidak ditemukan.'
            ], 404);
        }

        $username = session('username');

        try {
            DB::table('pl_work_schedule')
                ->where('id', $attributes['schedule_id'])
                ->update([
                    'plan_date' => $attributes['plan_date'],
                    'realization_date' => $attributes['realization_date'] ?? null,
                    'updated_at' => now(),
                    'user_update' => $username,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal kerja berhasil diperbarui.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jadwal kerja: ' . $e->getMessage()
            ], 500);
        }
    }

    public function savePlanDates(Request $request)
    {
        $attributes = $request->validate(
            [
                'periodic_id' => 'required|integer',
                'plan_dates' => 'required|array|min:1',
                'plan_dates.*.cycle' => 'required|integer|min:1',
                'plan_dates.*.plan_date' => 'required|date',
            ],
            [
                'required' => ':attribute tidak boleh kosong',
                'date' => ':attribute harus berupa tanggal yang valid',
                'array' => ':attribute harus berupa array',
                'min' => ':attribute minimal 1 data',
            ]
        );

        $periodicId = $attributes['periodic_id'];
        $planDates = $attributes['plan_dates'];
        $username = session('username');

        DB::beginTransaction();
        try {
            // Get periodic info
            $periodic = DB::table('ms_periodic')->where('id', $periodicId)->first();
            if (!$periodic) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periodic tidak ditemukan.'
                ], 404);
            }

            // Clear existing schedules for this periodic
            DB::table('pl_work_schedule')
                ->where('periodic_id', $periodicId)
                ->delete();

            // Create new schedules based on plan dates
            foreach ($planDates as $pd) {
                DB::table('pl_work_schedule')->insert([
                    'periodic_id' => $periodicId,
                    'plan_date' => $pd['plan_date'],
                    'job_status' => 'scheduled',
                    'is_active' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'user_create' => $username,
                    'user_update' => $username,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Plan dates berhasil disimpan. ' . count($planDates) . ' jadwal telah dibuat.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan plan dates: ' . $e->getMessage()
            ], 500);
        }
    }

    public function assignWorker(Request $request)
    {
        $attributes = $request->validate(
            [
                'schedule_id' => 'required|integer',
                'worker_id' => 'required|integer',
            ],
            [
                'required' => ':attribute tidak boleh kosong',
            ]
        );

        $schedule = DB::table('pl_work_schedule')->where('id', $attributes['schedule_id'])->first();
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal pekerjaan tidak ditemukan.'
            ], 404);
        }

        $worker = DB::table('users')->where('id', $attributes['worker_id'])->first();
        if (!$worker) {
            return response()->json([
                'success' => false,
                'message' => 'Petugas tidak ditemukan.'
            ], 404);
        }

        $username = session('username');

        try {
            DB::table('pl_work_schedule')
                ->where('id', $attributes['schedule_id'])
                ->update([
                    'worker_id' => $attributes['worker_id'],
                    'updated_at' => now(),
                    'user_update' => $username,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Petugas berhasil diassign ke jadwal kerja.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengassign petugas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'periodic_id' => 'required|integer|exists:ms_periodic,id',
            'plan_dates' => 'required|array|min:1',
            'plan_dates.*' => 'required|date',
            'worker_id' => 'required|integer|exists:users,id',
        ]);

        $periodic = DB::table('ms_periodic')->where('id', $attributes['periodic_id'])->first();
        if (!$periodic) {
            return response()->json([
                'success' => false,
                'message' => 'Pekerjaan periodic tidak ditemukan.'
            ], 404);
        }

        $username = session('username') ?? 'system';

        try {
            DB::beginTransaction();

            $planDates = array_values($attributes['plan_dates']); // Ensure sequential index
            $cycleCount = count($planDates);

            // Create entries for each cycle
            for ($i = 0; $i < $cycleCount; $i++) {
                DB::table('pl_work_schedule')->insert([
                    'periodic_id' => $attributes['periodic_id'],
                    'cycle_number' => $i + 1, // cycle_number starts from 1
                    'plan_date' => $planDates[$i] ?? null,
                    'worker_id' => $attributes['worker_id'],
                    'job_status' => 'scheduled',
                    'is_active' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'user_create' => $username,
                    'user_update' => $username,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal kerja ' . $cycleCount . ' cycle berhasil ditambahkan.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jadwal kerja: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $attributes = $request->validate([
            'schedule_id' => 'required|integer|exists:pl_work_schedule,id',
        ]);

        $username = session('username') ?? 'system';

        try {
            DB::table('pl_work_schedule')
                ->where('id', $attributes['schedule_id'])
                ->update([
                    'is_active' => '0',
                    'updated_at' => now(),
                    'user_update' => $username,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal kerja berhasil dihapus.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal kerja: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkSavePeriodic(Request $request)
    {
        $attributes = $request->validate([
            'tahun' => 'required|integer',
            'keterangan' => 'nullable|string|max:255',
            'details' => 'required|array|min:1',
            'details.*.id' => 'nullable|integer|exists:pl_periodic_detail,id',
            'details.*.periodic_id' => 'required|integer|exists:ms_periodic,id',
            'details.*.area_id' => 'required|integer|exists:ms_area,id',
            'details.*.worker_id' => 'required|integer|exists:users,id',
            'details.*.periode' => ['required', Rule::in(['mingguan', 'bulanan', 'tahunan'])],
            'details.*.cycle' => 'required|integer|min:1',
            'details.*.start_plan_date' => 'nullable|date',
            'details.*.plan_dates' => 'nullable|array',
            'details.*.plan_dates.*' => 'nullable|date',
        ]);

        $username = session('username') ?? 'system';

        DB::beginTransaction();
        try {
            $header = DB::table('pl_periodic_header')
                ->where('tahun', $attributes['tahun'])
                ->where('is_active', '1')
                ->first();

            if ($header) {
                DB::table('pl_periodic_header')
                    ->where('id', $header->id)
                    ->update([
                        'keterangan' => $attributes['keterangan'],
                        'updated_at' => now(),
                        'user_update' => $username,
                    ]);
                $headerId = $header->id;
            } else {
                $headerId = DB::table('pl_periodic_header')->insertGetId([
                    'tahun' => $attributes['tahun'],
                    'keterangan' => $attributes['keterangan'],
                    'is_active' => '1',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'user_create' => $username,
                    'user_update' => $username,
                ]);
            }

            foreach ($attributes['details'] as $detail) {
                $startPlanDate = $this->resolveStartPlanDate($detail, (int) $attributes['tahun']);

                $payload = [
                    'header_id' => $headerId,
                    'periodic_id' => $detail['periodic_id'],
                    'area_id' => $detail['area_id'],
                    'worker_id' => $detail['worker_id'] ?? null,
                    'periode' => $detail['periode'],
                    'cycle' => $detail['cycle'],
                    'start_plan_date' => $startPlanDate,
                    'is_active' => '1',
                    'updated_at' => now(),
                    'user_update' => $username,
                ];

                if (!empty($detail['id'])) {
                    DB::table('pl_periodic_detail')
                        ->where('id', $detail['id'])
                        ->update($payload);
                    $detailId = $detail['id'];
                } else {
                    $payload['created_at'] = now();
                    $payload['user_create'] = $username;
                    $detailId = DB::table('pl_periodic_detail')->insertGetId($payload);
                }

                if (!empty($startPlanDate)) {
                    $yearEnd = Carbon::create((int) $attributes['tahun'], 12, 31)->startOfDay();
                    $plannedDates = $this->generatePlannedDatesUntilYearEnd(
                        Carbon::parse($startPlanDate),
                        $detail['periode'],
                        (int) $detail['cycle'],
                        $yearEnd
                    );

                    DB::table('pl_periodic_items')
                        ->where('detail_id', $detailId)
                        ->whereDate('planned_date', '>=', $startPlanDate)
                        ->whereNull('realization_date')
                        ->delete();

                    foreach ($plannedDates as $plannedDate) {
                        DB::table('pl_periodic_items')->insertOrIgnore([
                            'detail_id' => $detailId,
                            'planned_date' => $plannedDate,
                            'realization_date' => null,
                            'is_active' => '1',
                            'created_at' => now(),
                            'updated_at' => now(),
                            'user_create' => $username,
                            'user_update' => $username,
                        ]);
                    }

                    DB::table('pl_periodic_detail')
                        ->where('id', $detailId)
                        ->update([
                            'generated_until' => !empty($plannedDates) ? end($plannedDates) : null,
                            'updated_at' => now(),
                            'user_update' => $username,
                        ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data periodic berhasil disimpan.',
                'header_id' => $headerId,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function resolveStartPlanDate(array $detail, int $tahun): ?string
    {
        if (!empty($detail['start_plan_date'])) {
            $candidate = Carbon::parse($detail['start_plan_date']);
            return $candidate->year === $tahun ? $candidate->toDateString() : null;
        }

        $planDates = collect($detail['plan_dates'] ?? [])
            ->filter()
            ->map(fn($date) => Carbon::parse($date))
            ->filter(fn(Carbon $date) => $date->year === $tahun)
            ->sortBy(fn(Carbon $date) => $date->timestamp)
            ->values();

        return $planDates->isNotEmpty() ? $planDates->first()->toDateString() : null;
    }

    private function generatePlannedDatesUntilYearEnd(Carbon $startDate, string $periode, int $cycle, Carbon $yearEnd): array
    {
        $dates = [];
        $seenDates = [];
        $currentBlockStart = $startDate->copy()->startOfDay();
        $frequency = max(1, $cycle);

        while ($currentBlockStart->lte($yearEnd)) {
            $nextBlockStart = $this->advanceOnePeriod($currentBlockStart, $periode);
            $blockEnd = $nextBlockStart->copy()->subDay();

            if ($blockEnd->gt($yearEnd)) {
                $blockEnd = $yearEnd->copy();
            }

            $blockLengthInDays = max(1, $currentBlockStart->diffInDays($blockEnd) + 1);

            // cycle is treated as frequency within one period window.
            for ($index = 0; $index < $frequency; $index++) {
                $offset = (int) floor(($index * $blockLengthInDays) / $frequency);
                if ($offset >= $blockLengthInDays) {
                    $offset = $blockLengthInDays - 1;
                }

                $candidate = $currentBlockStart->copy()->addDays($offset);
                if ($candidate->gt($yearEnd)) {
                    continue;
                }

                $dateKey = $candidate->toDateString();
                if (isset($seenDates[$dateKey])) {
                    continue;
                }

                $seenDates[$dateKey] = true;
                $dates[] = $dateKey;
            }

            $currentBlockStart = $nextBlockStart;
        }

        sort($dates);
        return $dates;
    }

    private function advanceOnePeriod(Carbon $current, string $periode): Carbon
    {
        $next = $current->copy();

        if ($periode === 'mingguan') {
            return $next->addWeek();
        }

        if ($periode === 'bulanan') {
            return $next->addMonthNoOverflow();
        }

        return $next->addYearNoOverflow();
    }

    public function deletePeriodicDetail(Request $request)
    {
        $attributes = $request->validate([
            'detail_id' => 'required|integer|exists:pl_periodic_detail,id',
        ]);

        try {
            DB::table('pl_periodic_detail')
                ->where('id', $attributes['detail_id'])
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Detail berhasil dihapus permanen.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus detail: ' . $e->getMessage(),
            ], 500);
        }
    }
}