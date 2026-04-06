<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tabel_rpt_non_periodic_hrd extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sys_table')->where(['gmenu' => 'report', 'dmenu' => 'rpnphd'])->delete();

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpnphd',
            'urut' => '1',
            'field' => 'query',
            'type' => 'report',
            'query' => "SELECT
                DATE_FORMAT(np.created_at, '%d-%m-%Y') AS `Tanggal Pengajuan`,
                COALESCE(np.area_id, '-') AS Area,
                COALESCE(np.job_description, '-') AS Pekerjaan,
                CASE
                    WHEN np.pengadaan_started_at IS NOT NULL AND np.pengadaan_ended_at IS NOT NULL THEN
                        CONCAT(
                            DATE_FORMAT(np.pengadaan_started_at, '%d-%m-%Y'),
                            ' s/d ',
                            DATE_FORMAT(np.pengadaan_ended_at, '%d-%m-%Y')
                        )
                    WHEN np.pengadaan_started_at IS NOT NULL THEN
                        CONCAT(DATE_FORMAT(np.pengadaan_started_at, '%d-%m-%Y'), ' s/d -')
                    ELSE '-'
                END AS `Waktu Pengadaan`,
                CASE
                    WHEN np.pengerjaan_started_at IS NOT NULL AND np.pengerjaan_ended_at IS NOT NULL THEN
                        CONCAT(
                            DATE_FORMAT(np.pengerjaan_started_at, '%d-%m-%Y'),
                            ' s/d ',
                            DATE_FORMAT(np.pengerjaan_ended_at, '%d-%m-%Y')
                        )
                    WHEN np.pengerjaan_started_at IS NOT NULL THEN
                        CONCAT(DATE_FORMAT(np.pengerjaan_started_at, '%d-%m-%Y'), ' s/d -')
                    ELSE '-'
                END AS `Waktu Pengerjaan`,
                COALESCE(CONCAT(worker.firstname, ' ', worker.lastname), '-') AS Petugas,
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
                ) AS `IMG Dokumentasi`
            FROM pl_non_periodic np
            LEFT JOIN users worker ON worker.id = np.worker_id
            LEFT JOIN pl_documentation doc ON doc.non_periodic_id = np.id AND doc.is_active = '1'
            WHERE (np.is_active = '1' OR np.is_active IS NULL)
                AND DATE(np.created_at) BETWEEN :frdate AND :todate
                AND COALESCE(np.area_id, '') LIKE :area
                AND LOWER(COALESCE(np.request_status, '')) LIKE :status
                AND LOWER(COALESCE(worker.firstname, '')) LIKE :petugas
            GROUP BY np.id, np.created_at, np.area_id, np.job_description, np.request_status,
                np.pengadaan_started_at, np.pengadaan_ended_at, np.pengerjaan_started_at, np.pengerjaan_ended_at,
                worker.firstname, worker.lastname, np.hrd_note
            ORDER BY np.created_at DESC"
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpnphd',
            'urut' => '2',
            'field' => 'date',
            'alias' => 'Periode',
            'type' => 'date2',
            'validate' => 'nullable',
            'filter' => '1',
            'query' => ''
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpnphd',
            'urut' => '3',
            'field' => 'area',
            'alias' => 'Area',
            'type' => 'search',
            'length' => '100',
            'validate' => 'max:100',
            'filter' => '1',
            'query' => "SELECT DISTINCT area_id AS value, area_id AS name
                FROM pl_non_periodic
                WHERE (is_active = '1' OR is_active IS NULL)
                    AND area_id IS NOT NULL
                    AND area_id <> ''
                ORDER BY area_id"
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpnphd',
            'urut' => '4',
            'field' => 'status',
            'alias' => 'Status',
            'type' => 'search',
            'length' => '50',
            'validate' => 'max:50',
            'filter' => '1',
            'query' => "SELECT DISTINCT LOWER(request_status) AS value, LOWER(request_status) AS name
                FROM pl_non_periodic
                WHERE (is_active = '1' OR is_active IS NULL)
                    AND request_status IS NOT NULL
                    AND request_status <> ''
                ORDER BY request_status"
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpnphd',
            'urut' => '5',
            'field' => 'petugas',
            'alias' => 'Petugas',
            'type' => 'search',
            'length' => '100',
            'validate' => 'max:100',
            'filter' => '1',
            'query' => "SELECT DISTINCT LOWER(firstname) AS value, firstname AS name
                FROM users
                WHERE isactive = '1'
                    AND (FIND_IN_SET('ptgoff', REPLACE(idroles, ' ', '')) OR FIND_IN_SET('ptgspu', REPLACE(idroles, ' ', '')))
                ORDER BY firstname"
        ]);
    }
}
