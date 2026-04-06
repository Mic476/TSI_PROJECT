<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_master_daily extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('ms_daily')->insert([
            [
                'id' => 1,
                'worker_id' => 5,
                'job_description' => 'Membersihkan Meja',
                'assigned_role' => 'ptgoff',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'worker_id' => 5,
                'job_description' => 'Angkat Air Galon',
                'assigned_role' => 'ptgoff',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'worker_id' => 6,
                'job_description' => 'Mendistribusikan air minum',
                'assigned_role' => 'ptgspu',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
