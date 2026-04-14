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
                q.Area,
                q.Pekerjaan,
                q.Periodik,
                q.Bulan,
                CONCAT('Week ', q.week_number) AS `Week`,
                q.Rencana,
                q.Realisasi,
                COALESCE(q.`IMG Dokumentasi`, '') AS `IMG Dokumentasi`,
                q.Petugas
            FROM (
                SELECT
                    t.detail_id,
                    t.period_key,
                    t.week_number,
                    t.Area,
                    t.Pekerjaan,
                    t.Periodik,
                    DATE_FORMAT(MIN(t.planned_date), '%M %Y') AS Bulan,
                    GROUP_CONCAT(DISTINCT DAY(t.planned_date) ORDER BY t.planned_date SEPARATOR ', ') AS Rencana,
                    COALESCE(GROUP_CONCAT(DISTINCT DAY(t.realization_date) ORDER BY t.realization_date SEPARATOR ', '), '-') AS Realisasi,
                    COALESCE(GROUP_CONCAT(DISTINCT t.doc_bundle SEPARATOR '||'), '') AS `IMG Dokumentasi`,
                    COALESCE(t.Petugas, '-') AS Petugas
                FROM (
                    SELECT
                        d.id AS detail_id,
                        DATE_FORMAT(i.planned_date, '%Y-%m') AS period_key,
                        CEIL(
                            ROW_NUMBER() OVER (
                                PARTITION BY d.id, DATE_FORMAT(i.planned_date, '%Y-%m')
                                ORDER BY i.planned_date, i.id
                            ) / NULLIF(d.cycle, 0)
                        ) AS week_number,
                        a.nama_area AS Area,
                        p.job_description AS Pekerjaan,
                        CONCAT(d.periode, ' / ', d.cycle, 'x') AS Periodik,
                        i.planned_date,
                        i.realization_date,
                        COALESCE(u.firstname, '-') AS Petugas,
                        (
                            SELECT GROUP_CONCAT(
                                CONCAT(COALESCE(DATE_FORMAT(i.realization_date, '%d-%m-%Y'), '-'), '@@', doc2.file)
                                ORDER BY doc2.created_at ASC SEPARATOR '||'
                            )
                            FROM pl_documentation doc2
                            WHERE doc2.periodic_item_id = i.id
                                AND doc2.is_active = '1'
                                AND LOWER(doc2.file) REGEXP '\\.(jpg|jpeg|png|webp|jfif|heic|heif)$'
                        ) AS doc_bundle
                    FROM pl_periodic_items i
                    INNER JOIN pl_periodic_detail d ON d.id = i.detail_id
                    INNER JOIN ms_periodic p ON p.id = d.periodic_id
                    LEFT JOIN ms_area a ON a.id = d.area_id
                    LEFT JOIN users u ON u.id = d.worker_id
                    WHERE i.is_active = '1'
                        AND d.is_active = '1'
                        AND i.planned_date BETWEEN :frdate AND :todate
                        AND COALESCE(a.nama_area, '') LIKE :area
                        AND COALESCE(u.firstname, '') LIKE :petugas
                ) t
                GROUP BY t.detail_id, t.period_key, t.week_number, t.Area, t.Pekerjaan, t.Periodik, t.Petugas
            ) q
            ORDER BY q.Area ASC, q.Pekerjaan ASC, q.Bulan ASC, q.week_number ASC"
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
