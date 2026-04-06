<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tabel_rpt_pemeliharaan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sys_table')->where(['gmenu' => 'report', 'dmenu' => 'rpmelh'])->delete();

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpmelh',
            'urut' => '1',
            'field' => 'query',
            'type' => 'report',
            'query' => "SELECT
                a.nama_area AS Area,
                p.job_description AS Pekerjaan,
                CONCAT(d.periode, ' / ', d.cycle, 'x') AS Periodik,
                DATE_FORMAT(MIN(i.planned_date), '%M %Y') AS Bulan,
                GROUP_CONCAT(DISTINCT DAY(i.planned_date) ORDER BY i.planned_date SEPARATOR ', ') AS Rencana,
                COALESCE(GROUP_CONCAT(DISTINCT DAY(i.realization_date) ORDER BY i.realization_date SEPARATOR ', '), '-') AS Realisasi,
                COALESCE(
                    SUBSTRING_INDEX(
                        GROUP_CONCAT(
                            DISTINCT CASE
                                WHEN LOWER(doc.file) REGEXP '\\\\.(jpg|jpeg|png)$' THEN doc.file
                                ELSE NULL
                            END
                            ORDER BY doc.created_at DESC SEPARATOR ','
                        ),
                        ',',
                        1
                    ),
                    ''
                ) AS `IMG Dokumentasi`,
                COALESCE(u.firstname, '-') AS Petugas
            FROM pl_periodic_items i
            INNER JOIN pl_periodic_detail d ON d.id = i.detail_id
            INNER JOIN ms_periodic p ON p.id = d.periodic_id
            LEFT JOIN ms_area a ON a.id = d.area_id
            LEFT JOIN users u ON u.id = d.worker_id
            LEFT JOIN pl_documentation doc ON doc.periodic_item_id = i.id AND doc.is_active = '1'
            WHERE i.is_active = '1'
                AND d.is_active = '1'
                AND i.planned_date BETWEEN :frdate AND :todate
                AND COALESCE(a.nama_area, '') LIKE :area
                AND COALESCE(u.firstname, '') LIKE :petugas
            GROUP BY d.id, DATE_FORMAT(i.planned_date, '%Y-%m'), a.nama_area, p.job_description, d.periode, d.cycle, u.firstname
            ORDER BY a.nama_area ASC, p.job_description ASC, MIN(i.planned_date) ASC"
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpmelh',
            'urut' => '2',
            'field' => 'date',
            'alias' => 'Periode',
            'type' => 'date2',
            'validate' => 'nullable',
            'filter' => '1',
            'query' => ""
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpmelh',
            'urut' => '3',
            'field' => 'area',
            'alias' => 'Area',
            'type' => 'search',
            'length' => '100',
            'validate' => 'max:100',
            'filter' => '1',
            'query' => "SELECT nama_area AS value, nama_area AS name FROM ms_area WHERE is_active = '1' ORDER BY nama_area"
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpmelh',
            'urut' => '4',
            'field' => 'petugas',
            'alias' => 'Petugas',
            'type' => 'search',
            'length' => '100',
            'validate' => 'max:100',
            'filter' => '1',
            'query' => "SELECT firstname AS value, firstname AS name
                FROM users
                WHERE isactive = '1'
                    AND (FIND_IN_SET('ptgoff', REPLACE(idroles, ' ', '')) OR FIND_IN_SET('ptgspu', REPLACE(idroles, ' ', '')))
                ORDER BY firstname"
        ]);
    }
}
