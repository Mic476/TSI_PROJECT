<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tabel_master_daily extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sys_table')->where(['gmenu' => 'master', 'dmenu' => 'msdail'])->delete();

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
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
            'list' => '0',
            'show' => '0',
            'query' => '',
            'class' => '',
            'note' => '',
            'position' => '1'
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => '2',
            'field' => 'worker_id',
            'alias' => 'Worker Name',
            'type' => 'enum',
            'length' => '20',
            'decimals' => '0',
            'default' => '',
            'validate' => '',
            'primary' => '0',
            'filter' => '1',
            'list' => '0',
            'show' => '0',
            'query' => "select id, case when firstname is null and lastname is null then username else concat_ws(' ', firstname, lastname) end as name from users where isactive = '1'",
            'class' => '',
            'note' => 'Nama pekerja',
            'position' => '2'
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => '3',
            'field' => 'job_description',
            'alias' => 'Job Description',
            'type' => 'text',
            'length' => '255',
            'decimals' => '0',
            'default' => '',
            'validate' => 'required|max:255|min:3',
            'primary' => '0',
            'filter' => '1',
            'list' => '1',
            'show' => '1',
            'query' => '',
            'class' => '',
            'note' => 'Deskripsi pekerjaan',
            'position' => '2'
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => '5',
            'field' => 'is_active',
            'alias' => 'Status',
            'type' => 'enum',
            'length' => '1',
            'decimals' => '0',
            'default' => '',
            'validate' => '',
            'primary' => '0',
            'filter' => '1',
            'list' => '1',
            'show' => '0',
            'query' => "select value, name from sys_enum where idenum = 'isactive' and isactive = '1'",
            'class' => '',
            'note' => '',
            'position' => '2'
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => '4',
            'field' => 'assigned_role',
            'alias' => 'Petugas',
            'type' => 'enum',
            'length' => '20',
            'decimals' => '0',
            'default' => '',
            'validate' => 'required',
            'primary' => '0',
            'filter' => '1',
            'list' => '1',
            'show' => '1',
            'query' => "select idroles as value, name from sys_roles where idroles like 'ptg%' and isactive = '1'",
            'class' => '',
            'note' => 'Penugasan peran petugas (prefix role: ptg)',
            'position' => '2'
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => '7',
            'field' => 'created_at',
            'alias' => 'Waktu Pembuatan',
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
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => '8',
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
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => '9',
            'field' => 'user_create',
            'alias' => 'User Create',
            'type' => 'string',
            'length' => '50',
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
        ]);

        DB::table('sys_table')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => '10',
            'field' => 'user_update',
            'alias' => 'User Update',
            'type' => 'string',
            'length' => '50',
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
        ]);
    }
}
