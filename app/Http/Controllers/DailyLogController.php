<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyLogController extends Controller
{
    public function store(Request $request)
    {
        $username = session('username');
        $defaultWorkDate = $request->input('work_date', now()->toDateString());
        $statusesByDate = $request->input('daily_status_by_date', []);

        if (!empty($statusesByDate) && is_array($statusesByDate)) {
            foreach ($statusesByDate as $workDate => $statuses) {
                if (!is_array($statuses)) {
                    continue;
                }

                foreach ($statuses as $taskId => $status) {
                    $this->upsertDailyLog($taskId, $workDate, $status, $username);
                }
            }

            return back()->with('message', 'Daily checklist tersimpan.')->with('class', 'success');
        }

        // Backward compatibility: old payload format daily_status[task_id] + single work_date
        $statuses = $request->input('daily_status', []);
        foreach ($statuses as $taskId => $status) {
            $this->upsertDailyLog($taskId, $defaultWorkDate, $status, $username);
        }

        return back()->with('message', 'Daily checklist tersimpan.')->with('class', 'success');
    }

    private function upsertDailyLog($taskId, $workDate, $status, $username): void
    {
        if (!$taskId || !$workDate) {
            return;
        }

        $normalizedStatus = $status === 'completed' ? 'completed' : 'pending';
        $existing = DB::table('rp_daily_log')
            ->where('daily_task_id', $taskId)
            ->where('work_date', $workDate)
            ->first();

        $payload = [
            'daily_task_id' => $taskId,
            'work_date' => $workDate,
            'job_status' => $normalizedStatus,
            'is_active' => '1',
            'user_update' => $username,
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('rp_daily_log')->where('id', $existing->id)->update($payload);
            return;
        }

        DB::table('rp_daily_log')->insert($payload + [
            'user_create' => $username,
            'created_at' => now(),
        ]);
    }
}
