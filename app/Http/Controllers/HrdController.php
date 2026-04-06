<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class HrdController extends Controller
{
    /**
     * Define valid status transitions (one-way flow)
     */
    private static $allowedTransitions = [
        'review' => ['pengadaan', 'pengerjaan', 'rejected'],
        'pengadaan' => ['pengerjaan', 'cancel'],
        'pengerjaan' => ['cancel'],
        'verifikasi' => [],                     // Waiting user verification
        'completed' => [],                      // Final status
        'cancel' => [],                         // Final status
    ];

    public function update(Request $request)
    {
        $id = $this->resolveId($request->input('id'));
        $current = DB::table('pl_non_periodic')
            ->where('id', $id)
            ->first();

        if (!$current) {
            Session::flash('class', 'danger');
            Session::flash('message', 'Data pengajuan tidak ditemukan.');
            return redirect()->back();
        }

        $currentStatus = strtolower($current->request_status ?? 'review');
        $newStatus = strtolower($request->input('request_status', ''));

        // Normalize old status names
        if ($currentStatus === 'reject') {
            $currentStatus = 'rejected';
        }
        if ($currentStatus === 'approved') {
            $currentStatus = 'review';
        }

        // Check if current status is terminal
        if (in_array($currentStatus, ['rejected', 'verifikasi', 'completed', 'cancel'], true)) {
            Session::flash('class', 'danger');
            Session::flash('message', 'Pengajuan sudah ditutup dan tidak dapat diperbarui.');
            return redirect()->back();
        }

        // Validate status transition
        if (!$this->isValidTransition($currentStatus, $newStatus)) {
            Session::flash('class', 'danger');
            Session::flash('message', "Transisi dari {$currentStatus} ke {$newStatus} tidak diizinkan.");
            return redirect()->back();
        }

        $attributes = $request->validate(
            [
                'id' => 'required',
                'request_status' => 'required|string|in:pengadaan,pengerjaan,rejected,cancel',
                'work_type' => 'nullable|string|in:internal,vendor',
                'vendor_name' => 'nullable|string|max:100',
                'worker_id' => 'nullable|integer|exists:users,id',
                'hrd_note' => 'nullable|string|max:500',
            ],
            [
                'required' => ':attribute tidak boleh kosong',
                'max' => ':attribute maksimal :max karakter',
            ]
        );

        // Validate conditional requirements based on status
        if ($newStatus === 'pengerjaan') {
            if (empty($attributes['work_type'])) {
                Session::flash('class', 'danger');
                Session::flash('message', 'Jenis pelaksana (internal/vendor) harus dipilih.');
                return redirect()->back();
            }
            if ($attributes['work_type'] === 'vendor' && empty($attributes['vendor_name'])) {
                Session::flash('class', 'danger');
                Session::flash('message', 'Nama vendor harus diisi untuk pelaksana vendor.');
                return redirect()->back();
            }
            if ($attributes['work_type'] === 'internal' && empty($attributes['worker_id'])) {
                Session::flash('class', 'danger');
                Session::flash('message', 'Petugas harus dipilih untuk pelaksana internal.');
                return redirect()->back();
            }
        }

        // Clean vendor_name if not vendor type
        if (isset($attributes['work_type']) && $attributes['work_type'] !== 'vendor') {
            $attributes['vendor_name'] = null;
        }

        $approver = User::where('username', session('username'))->first();
        $approverId = $approver ? $approver->id : null;

        $updateData = [
            'request_status' => $newStatus,
            'hrd_note' => $attributes['hrd_note'] ?? null,
            'updated_at' => now(),
        ];

        // Set hrd_approval_date on first HRD action
        if (empty($current->hrd_approval_date)) {
            $updateData['hrd_approval_date'] = now()->toDateString();
            $updateData['hrd_approval_id'] = $approverId;
        }

        // Add status-specific fields
        if ($newStatus === 'pengadaan') {
            // Set timestamp saat pertama kali masuk ke status pengadaan
            if (empty($current->pengadaan_started_at)) {
                $updateData['pengadaan_started_at'] = now();
            }
        }

        if ($newStatus === 'pengerjaan') {
            // Waktu mulai pengerjaan diisi otomatis dengan waktu saat ini
            $updateData['pengerjaan_started_at'] = now();
            if (isset($attributes['work_type'])) {
                $updateData['work_type'] = $attributes['work_type'];
            }
            if (isset($attributes['vendor_name'])) {
                $updateData['vendor_name'] = $attributes['vendor_name'];
            }
            if (isset($attributes['worker_id'])) {
                $updateData['worker_id'] = $attributes['worker_id'];
            }
            // Set pengadaan end timestamp jika sebelumnya ada pengadaan
            if (!empty($current->pengadaan_started_at) && empty($current->pengadaan_ended_at)) {
                $updateData['pengadaan_ended_at'] = now();
            }
        }

        if ($newStatus === 'verifikasi') {
            // Set pengerjaan end timestamp jika sebelumnya ada pengerjaan
            if (!empty($current->pengerjaan_started_at) && empty($current->pengerjaan_ended_at)) {
                $updateData['pengerjaan_ended_at'] = now();
            }
        }

        $updated = DB::table('pl_non_periodic')
            ->where('id', $id)
            ->update($updateData);

        if (!$updated) {
            Session::flash('class', 'danger');
            Session::flash('message', 'Gagal memperbarui pengajuan HRD.');
            return redirect()->back();
        }

        Session::flash('class', 'success');
        Session::flash('message', 'Pengajuan HRD berhasil diperbarui.');

        return redirect()->back();
    }

    /**
     * Check if transition from currentStatus to newStatus is allowed
     */
    private function isValidTransition($currentStatus, $newStatus)
    {
        if (!isset(self::$allowedTransitions[$currentStatus])) {
            return false;
        }

        return in_array($newStatus, self::$allowedTransitions[$currentStatus]);
    }

    private function resolveId($encryptedId)
    {
        try {
            return decrypt($encryptedId);
        } catch (DecryptException $e) {
            return $encryptedId;
        }
    }
}
