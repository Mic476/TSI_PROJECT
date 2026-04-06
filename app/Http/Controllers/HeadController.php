<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HeadController extends Controller
{
    public function update(Request $request)
    {
        $attributes = $request->validate(
            [
                'id' => 'required',
                'request_status' => 'required|string|in:review,rejected',
                'head_note' => 'nullable|string|max:500',
            ],
            [
                'required' => ':attribute tidak boleh kosong',
                'max' => ':attribute maksimal :max karakter',
            ]
        );

        $id = $this->resolveId($attributes['id']);
        $current = DB::table('pl_non_periodic')->where('id', $id)->first();

        if (!$current) {
            Session::flash('class', 'danger');
            Session::flash('message', 'Data pengajuan tidak ditemukan.');
            return redirect()->back();
        }

        $currentStatus = strtolower($current->request_status ?? 'review');
        if ($currentStatus === 'reject') {
            $currentStatus = 'rejected';
        }
        if (!in_array($currentStatus, ['review', 'pending'], true)) {
            Session::flash('class', 'danger');
            Session::flash('message', 'Pengajuan tidak bisa diproses ulang.');
            return redirect()->back();
        }

        $approver = User::where('username', session('username'))->first();
        $approverId = $approver ? $approver->id : null;

        $updated = DB::table('pl_non_periodic')
            ->where('id', $id)
            ->update([
                'request_status' => $attributes['request_status'],
                'head_note' => $attributes['head_note'] ?? null,
                'head_approval_date' => now()->toDateString(),
                'head_approver_id' => $approverId,
                'updated_at' => now(),
            ]);

        if (!$updated) {
            Session::flash('class', 'danger');
            Session::flash('message', 'Gagal memperbarui approval head.');
            return redirect()->back();
        }

        Session::flash('class', 'success');
        Session::flash('message', 'Approval head berhasil diperbarui.');

        return redirect()->back();
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
