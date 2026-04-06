<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_master_periodic extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('ms_area')->insert([
            [
                'id' => 1,
                'nama_area' => 'Toilet',
                'description' => 'Toilet Kantor',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'nama_area' => 'Outdoor',
                'description' => 'Area luar gedung',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'nama_area' => 'Area Produksi',
                'description' => 'Lingkungan produksi',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('ms_periodic')->insert([
            [
                'id' => 1,
                'job_description' => 'Cuci Mobil',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'job_description' => 'Pembersihan Rumput',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'job_description' => 'Pembersihan Selokan',
                'is_active' => '1',
                'user_create' => 'msjit',
                'user_update' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
