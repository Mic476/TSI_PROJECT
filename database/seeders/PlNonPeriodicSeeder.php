<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlNonPeriodicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = DB::table('users')->where('username', 'user')->value('id') ?? 1;
        $headId = DB::table('users')->where('username', 'head')->value('id');
        $hrdId = DB::table('users')->where('username', 'hrd')->value('id');
        $workerId = DB::table('users')->where('username', 'endang')->value('id');

        $now = Carbon::now();

        $defaultRow = [
            'user_id' => $userId,
            'area_id' => null,
            'job_description' => null,
            'work_type' => null,
            'vendor_name' => null,
            'worker_id' => null,
            'realization_date' => null,
            'request_status' => 'review',
            'head_approver_id' => null,
            'head_note' => null,
            'head_approval_date' => null,
            'hrd_approval_id' => null,
            'hrd_note' => null,
            'hrd_approval_date' => null,
            'pengadaan_started_at' => null,
            'pengadaan_ended_at' => null,
            'pengerjaan_started_at' => null,
            'pengerjaan_ended_at' => null,
            'petugas_confirmed_at' => null,
            'attachment' => null,
            'is_active' => '1',
            'created_at' => $now,
            'updated_at' => $now,
            'user_create' => 'admin',
            'user_update' => 'admin',
        ];

        $rows = [
            [
                'user_id' => $userId,
                'area_id' => 'AREA-01',
                'job_description' => 'Perbaikan lampu area loading dock.',
                'request_status' => 'review',
                'head_approver_id' => $headId,
                'head_note' => 'Segera diproses oleh HRD.',
                'head_approval_date' => $now->copy()->subDays(2)->toDateString(),
                'is_active' => '1',
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(2),
                'user_create' => 'admin',
            ],
            [
                'area_id' => 'AREA-02',
                'job_description' => 'Pengadaan panel kontrol mesin pendingin.',
                'request_status' => 'pengadaan',
                'head_approver_id' => $headId,
                'head_note' => 'Approved head.',
                'head_approval_date' => $now->copy()->subDays(8)->toDateString(),
                'hrd_approval_id' => $hrdId,
                'hrd_note' => 'Vendor sedang konfirmasi stok.',
                'hrd_approval_date' => $now->copy()->subDays(7)->toDateString(),
                'pengadaan_started_at' => $now->copy()->subDays(7),
                'pengadaan_ended_at' => null, // Masih dalam proses pengadaan
                'is_active' => '1',
                'created_at' => $now->copy()->subDays(9),
                'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'area_id' => 'AREA-03',
                'job_description' => 'Perbaikan kebocoran pipa hydrant.',
                'work_type' => 'internal',
                'worker_id' => $workerId,
                'request_status' => 'completed',
                'head_approver_id' => $headId,
                'head_note' => 'Approved head.',
                'head_approval_date' => $now->copy()->subDays(10)->toDateString(),
                'hrd_approval_id' => $hrdId,
                'hrd_note' => 'Selesai dan terverifikasi.',
                'hrd_approval_date' => $now->copy()->subDays(9)->toDateString(),
                'pengadaan_started_at' => $now->copy()->subDays(9),
                'pengadaan_ended_at' => $now->copy()->subDays(6)->setTime(8, 0), // Selesai saat pindah ke pengerjaan
                'pengerjaan_started_at' => $now->copy()->subDays(6),
                'pengerjaan_ended_at' => $now->copy()->subDays(2)->setTime(15, 30), // Selesai saat verifikasi
                'petugas_confirmed_at' => $now->copy()->subDays(2)->setTime(15, 10),
                'realization_date' => $now->copy()->subDays(1)->toDateString(),
                'is_active' => '1',
                'created_at' => $now->copy()->subDays(12),
                'updated_at' => $now->copy()->subDay(),
            ],
            [
                'area_id' => 'AREA-04',
                'job_description' => 'Penggantian kabel panel lama.',
                'work_type' => 'internal',
                'worker_id' => $workerId,
                'request_status' => 'rejected',
                'head_approver_id' => $headId,
                'head_note' => 'Masih relevan, cek HRD.',
                'head_approval_date' => $now->copy()->subDays(11)->toDateString(),
                'hrd_approval_id' => $hrdId,
                'hrd_note' => 'Ditolak karena anggaran belum tersedia.',
                'hrd_approval_date' => $now->copy()->subDays(10)->toDateString(),
                'is_active' => '1',
                'created_at' => $now->copy()->subDays(12),
                'updated_at' => $now->copy()->subDays(10),
            ],
            [
                'area_id' => 'AREA-05',
                'job_description' => 'Penggantian pompa air taman belakang.',
                'work_type' => 'vendor',
                'vendor_name' => 'CV Tirta Solusi',
                'request_status' => 'cancel',
                'head_approver_id' => $headId,
                'head_note' => 'Approved head.',
                'head_approval_date' => $now->copy()->subDays(13)->toDateString(),
                'hrd_approval_id' => $hrdId,
                'hrd_note' => 'Dibatalkan sesuai permintaan user.',
                'hrd_approval_date' => $now->copy()->subDays(12)->toDateString(),
                'pengadaan_started_at' => $now->copy()->subDays(12),
                'pengadaan_ended_at' => $now->copy()->subDays(11)->setTime(14, 0), // Selesai saat dibatalkan
                'created_at' => $now->copy()->subDays(14),
                'updated_at' => $now->copy()->subDays(11),
            ],
        ];

        $payload = [];
        foreach ($rows as $row) {
            $payload[] = array_merge($defaultRow, $row);
        }

        DB::table('pl_non_periodic')->insert($payload);
    }
}
