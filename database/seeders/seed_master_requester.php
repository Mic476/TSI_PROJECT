<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class seed_master_requester extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!DB::getSchemaBuilder()->hasTable('ms_requesters')) {
            return;
        }

        $now = now();
        $rows = [
            ['requester_name' => 'Budi Santoso', 'is_active' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['requester_name' => 'Siti Aminah', 'is_active' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['requester_name' => 'Andi Pratama', 'is_active' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['requester_name' => 'Rina Wulandari', 'is_active' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['requester_name' => 'Fajar Nugroho', 'is_active' => '1', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('ms_requesters')->upsert(
            $rows,
            ['requester_name'],
            ['is_active', 'updated_at']
        );
    }
}
