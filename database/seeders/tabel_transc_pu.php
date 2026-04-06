<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tabel_transc_pu extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = ['truser', 'trreq', 'trhrd', 'trpetp', 'trpetn', 'trpetd'];

        foreach ($menus as $dmenu) {
            DB::table('sys_table')->where(['gmenu' => 'transc', 'dmenu' => $dmenu])->delete();

            $rows = [
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '1',
                    'field' => 'id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'length' => '20',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => '',
                    'primary' => '1',
                    'filter' => '1',
                    'list' => '1',
                    'show' => '0',
                    'query' => '',
                    'class' => '',
                    'note' => '',
                    'position' => '1'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '2',
                    'field' => 'area_id',
                    'alias' => 'Area',
                    'type' => 'enum',
                    'length' => '50',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => 'required|max:50',
                    'primary' => '0',
                    'filter' => '1',
                    'list' => '1',
                    'show' => '1',
                    'query' => "SELECT id, nama_area FROM ms_area WHERE is_active = '1' ORDER BY nama_area ASC",
                    'class' => '',
                    'note' => 'Area pekerjaan',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '3',
                    'field' => 'job_description',
                    'alias' => 'Deskripsi Pekerjaan',
                    'type' => 'text',
                    'length' => '255',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => 'required|max:255',
                    'primary' => '0',
                    'filter' => '1',
                    'list' => '1',
                    'show' => '1',
                    'query' => '',
                    'class' => '',
                    'note' => 'Deskripsi pekerjaan',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '4',
                    'field' => 'work_type',
                    'alias' => 'Tipe Pekerjaan',
                    'type' => 'string',
                    'length' => '20',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => 'nullable|max:20',
                    'primary' => '0',
                    'filter' => '1',
                    'list' => '1',
                    'show' => '1',
                    'query' => '',
                    'class' => '',
                    'note' => 'Internal atau Vendor',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '5',
                    'field' => 'vendor_name',
                    'alias' => 'Nama Vendor',
                    'type' => 'string',
                    'length' => '100',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => 'nullable|max:100',
                    'primary' => '0',
                    'filter' => '1',
                    'list' => '1',
                    'show' => '1',
                    'query' => '',
                    'class' => '',
                    'note' => 'Nama vendor',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '8',
                    'field' => 'request_status',
                    'alias' => 'Status',
                    'type' => 'string',
                    'length' => '20',
                    'decimals' => '0',
                    'default' => 'pending',
                    'validate' => 'nullable|max:20',
                    'primary' => '0',
                    'filter' => '1',
                    'list' => '1',
                    'show' => '1',
                    'query' => '',
                    'class' => '',
                    'note' => 'Status pengajuan',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '9',
                    'field' => 'attachment',
                    'alias' => 'Lampiran Gambar',
                    'type' => 'image',
                    'length' => '4096',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
                    'primary' => '0',
                    'filter' => '1',
                    'list' => '1',
                    'show' => '1',
                    'query' => '',
                    'class' => '',
                    'note' => 'Gambar lampiran (JPG, PNG, GIF, max 4MB)',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '10',
                    'field' => 'head_note',
                    'alias' => 'Catatan Head',
                    'type' => 'text',
                    'length' => '500',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => 'nullable|max:500',
                    'primary' => '0',
                    'filter' => '0',
                    'list' => '1',
                    'show' => '1',
                    'query' => '',
                    'class' => '',
                    'note' => 'Catatan head',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '11',
                    'field' => 'hrd_note',
                    'alias' => 'Catatan HRD',
                    'type' => 'text',
                    'length' => '500',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => 'nullable|max:500',
                    'primary' => '0',
                    'filter' => '0',
                    'list' => '1',
                    'show' => '1',
                    'query' => '',
                    'class' => '',
                    'note' => 'Catatan HRD',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '12',
                    'field' => 'created_at',
                    'alias' => 'Waktu Pembuatan',
                    'type' => 'date',
                    'length' => '20',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => '',
                    'primary' => '0',
                    'filter' => '0',
                    'list' => '1',
                    'show' => '0',
                    'query' => '',
                    'class' => '',
                    'note' => '',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '13',
                    'field' => 'updated_at',
                    'alias' => 'Waktu Perubahan',
                    'type' => 'date',
                    'length' => '20',
                    'decimals' => '0',
                    'default' => '',
                    'validate' => '',
                    'primary' => '0',
                    'filter' => '0',
                    'list' => '0',
                    'show' => '0',
                    'query' => '',
                    'class' => '',
                    'note' => '',
                    'position' => '2'
                ],
                [
                    'gmenu' => 'transc',
                    'dmenu' => $dmenu,
                    'urut' => '14',
                    'field' => 'is_active',
                    'alias' => 'Status Aktif',
                    'type' => 'enum',
                    'length' => '1',
                    'decimals' => '0',
                    'default' => '1',
                    'validate' => '',
                    'primary' => '0',
                    'filter' => '1',
                    'list' => '0',
                    'show' => '0',
                    'query' => "select value, name from sys_enum where idenum = 'isactive' and isactive = '1'",
                    'class' => '',
                    'note' => '',
                    'position' => '2'
                ],
            ];

            if ($dmenu === 'trreq') {
                foreach ($rows as &$row) {
                    if ($row['position'] === '1' && $row['field'] === 'id') {
                        $row['query'] = "select id from pl_non_periodic where is_active = '1' and request_status in ('pending','review') order by id desc";
                        break;
                    }
                }
                unset($row);
            }

            DB::table('sys_table')->insert($rows);
        }
    }
}
