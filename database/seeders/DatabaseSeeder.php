<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Prophecy\Call\Call;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //insert tabel sys_roles
        DB::table('sys_roles')->insert([
            'idroles' => 'admins',
            'name' => 'Admin',
            'description' => 'Administrator'
        ]);
        DB::table('sys_roles')->insert([
            'idroles' => 'ptgoff',
            'name' => 'Petugas Office',
            'description' => 'Petugas Area Office'
        ]);
        DB::table('sys_roles')->insert([
            'idroles' => 'ptgspu',
            'name' => 'Petugas PU',
            'description' => 'Petugas Area PU'
        ]);
        DB::table('sys_roles')->insert([
            'idroles' => 'usersx',
            'name' => 'User',
            'description' => 'User Pengaju'
        ]);
        DB::table('sys_roles')->insert([
            'idroles' => 'headxx',
            'name' => 'Head',
            'description' => 'Head Approval'
        ]);
        DB::table('sys_roles')->insert([
            'idroles' => 'hrdxxx',
            'name' => 'HRD',
            'description' => 'HRD Approval'
        ]);

        //insert tabel users
        DB::table('users')->insert([
            'username' => 'msjit',
            'firstname' => 'Admin',
            'lastname' => 'MIS',
            'email' => 'msjit@spunindo.com',
            'password' => bcrypt('tsipu'),
            'idroles' => 'admins'
        ]);
        DB::table('users')->insert([
            'username' => 'user',
            'firstname' => 'USER',
            'lastname' => 'Name',
            'email' => 'user@spunindo.com',
            'password' => bcrypt('user123'),
            'idroles' => 'usersx'
        ]);
        DB::table('users')->insert([
            'username' => 'head',
            'firstname' => 'HEAD',
            'lastname' => 'Name',
            'email' => 'head@spunindo.com',
            'password' => bcrypt('head123'),
            'idroles' => 'headxx'
        ]);
        DB::table('users')->insert([
            'username' => 'hrd',
            'firstname' => 'HRD',
            'lastname' => 'Name',
            'email' => 'hrd@spunindo.com',
            'password' => bcrypt('hrd123'),
            'idroles' => 'hrdxxx'
        ]);
        DB::table('users')->insert([
            'username' => 'endang',
            'firstname' => 'Endang',
            'lastname' => 'Petugas',
            'email' => 'endang@spunindo.com',
            'password' => bcrypt('petugas123'),
            'idroles' => 'ptgoff'
        ]);
        DB::table('users')->insert([
            'username' => 'fredy',
            'firstname' => 'Fredy',
            'lastname' => 'Petugas',
            'email' => 'fredy@spunindo.com',
            'password' => bcrypt('petugas123'),
            'idroles' => 'ptgspu'
        ]);
        DB::table('users')->insert([
            'username' => 'yunus',
            'firstname' => 'Yunus',
            'lastname' => 'Petugas',
            'email' => 'yunus@spunindo.com',
            'password' => bcrypt('petugas123'),
            'idroles' => 'ptgspu'
        ]);
        DB::table('users')->insert([
            'username' => 'adrian',
            'firstname' => 'Adrian',
            'lastname' => 'Petugas',
            'email' => 'adrian@spunindo.com',
            'password' => bcrypt('petugas123'),
            'idroles' => 'ptgspu'
        ]);
        DB::table('users')->insert([
            'username' => 'rico',
            'firstname' => 'Rico',
            'lastname' => 'Petugas',
            'email' => 'rico@spunindo.com',
            'password' => bcrypt('petugas123'),
            'idroles' => 'ptgspu'
        ]);

        //insert tabel sys_gmenu
        DB::table('sys_gmenu')->insert([
            'gmenu' => 'blankx',
            'urut' => 1,
            'name' => '-',
            'icon' => '-'
        ]);
        DB::table('sys_gmenu')->insert([
            'gmenu' => 'master',
            'urut' => 2,
            'name' => 'Master',
            'icon' => 'ni-collection'
        ]);
        DB::table('sys_gmenu')->insert([
            'gmenu' => 'transc',
            'urut' => 3,
            'name' => 'Transactions',
            'icon' => 'ni-collection'
        ]);
        DB::table('sys_gmenu')->insert([
            'gmenu' => 'report',
            'urut' => 4,
            'name' => 'Report',
            'icon' => 'ni-single-copy-04'
        ]);
        DB::table('sys_gmenu')->insert([
            'gmenu' => 'system',
            'urut' => 5,
            'name' => 'System',
            'icon' => 'ni-mobile-button'
        ]);

        //insert tabel sys_dmenu
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'blankx',
            'dmenu' => 'dashbr',
            'urut' => 1,
            'name' => 'Dashboard',
            'url' => 'dashboard',
            'icon' => 'ni-tv-2',
            'tabel' => '-',
            'layout' => 'manual'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'blankx',
            'dmenu' => 'dashhd',
            'urut' => 2,
            'name' => 'Dashboard HRD',
            'url' => 'dashboard-hrd',
            'icon' => 'ni-tv-2',
            'tabel' => '-',
            'layout' => 'manual'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'gmenux',
            'urut' => 1,
            'name' => 'Group Menu',
            'url' => 'sysgmenu',
            'icon' => 'ni-collection',
            'tabel' => 'sys_gmenu',
            'layout' => 'standr'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'dmenux',
            'urut' => 2,
            'name' => 'List Menu',
            'url' => 'sysdmenu',
            'icon' => 'ni-collection',
            'tabel' => 'sys_dmenu',
            'layout' => 'master'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'rolesx',
            'urut' => 3,
            'name' => 'Roles',
            'url' => 'sysroles',
            'icon' => 'ni-collection',
            'tabel' => 'sys_roles',
            'layout' => 'standr'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'authxx',
            'urut' => 4,
            'name' => 'Authorize',
            'url' => 'sysauth',
            'icon' => 'ni-single-copy-04',
            'tabel' => 'sys_auth',
            'layout' => 'system'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'tablex',
            'urut' => 5,
            'name' => 'Tabel Menu',
            'url' => 'systbl',
            'icon' => 'ni-single-copy-04',
            'tabel' => 'sys_table',
            'layout' => 'system'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'usersx',
            'urut' => 6,
            'name' => 'Users',
            'url' => 'sysuser',
            'icon' => 'ni-single-02',
            'tabel' => 'users',
            'layout' => 'master'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'sysidx',
            'urut' => 7,
            'name' => 'Generate ID',
            'url' => 'sysid',
            'icon' => 'ni-ui-04',
            'tabel' => 'sys_id',
            'layout' => 'master',
            'js' => '1'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'syscnt',
            'urut' => 8,
            'name' => 'ID Counter',
            'url' => 'syscnt',
            'icon' => 'ni-ui-04',
            'tabel' => 'sys_counter',
            'layout' => 'standr'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'system',
            'dmenu' => 'setupx',
            'urut' => 9,
            'name' => 'Setup',
            'url' => 'sysapp',
            'icon' => 'ni-ui-04',
            'tabel' => 'sys_app',
            'layout' => 'master'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'transc',
            'dmenu' => 'truser',
            'urut' => 1,
            'name' => 'Pengajuan PU',
            'url' => 'user',
            'icon' => 'ni-send',
            'tabel' => 'pl_non_periodic',
            'layout' => 'manual'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'transc',
            'dmenu' => 'trreq',
            'urut' => 2,
            'name' => 'Daftar Pengajuan',
            'url' => 'pengajuan',
            'icon' => 'ni-bullet-list-67',
            'tabel' => 'pl_non_periodic',
            'layout' => 'transc'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'transc',
            'dmenu' => 'trhrd',
            'urut' => 3,
            'name' => 'Daftar Pengajuan HRD',
            'url' => 'pengajuan-hrd',
            'icon' => 'ni-badge',
            'tabel' => 'pl_non_periodic',
            'layout' => 'transc'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'transc',
            'dmenu' => 'trdper',
            'urut' => 4,
            'name' => 'Daftar Periodic HRD',
            'url' => 'daftar-periodic-hrd',
            'icon' => 'ni-calendar-grid-58',
            'tabel' => 'ms_periodic',
            'layout' => 'transc',
            'isactive' => '1'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'transc',
            'dmenu' => 'trpetp',
            'urut' => 5,
            'name' => 'Pekerjaan Periodic',
            'url' => 'pekerjaan-periodic',
            'icon' => 'ni-calendar-grid-58',
            'tabel' => 'ms_periodic',
            'layout' => 'transc'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'transc',
            'dmenu' => 'trpetn',
            'urut' => 6,
            'name' => 'Pekerjaan Non-Periodic',
            'url' => 'pekerjaan-nonperiodic',
            'icon' => 'ni-bullet-list-67',
            'tabel' => 'pl_non_periodic',
            'layout' => 'transc'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'transc',
            'dmenu' => 'trpetd',
            'urut' => 7,
            'name' => 'Pekerjaan Daily',
            'url' => 'pekerjaan-daily',
            'icon' => 'ni-check-bold',
            'tabel' => 'ms_daily',
            'layout' => 'transc'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msenum',
            'urut' => 1,
            'name' => 'Default Value',
            'url' => 'msenum',
            'icon' => 'ni-ui-04',
            'tabel' => 'sys_enum',
            'layout' => 'master'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msarea',
            'urut' => 2,
            'name' => 'Master Area',
            'url' => 'area',
            'icon' => 'ni-pin-3',
            'tabel' => 'ms_area',
            'layout' => 'master'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msprdc',
            'urut' => 3,
            'name' => 'Master Periodic',
            'url' => 'periodic',
            'icon' => 'ni-calendar-grid-58',
            'tabel' => 'ms_periodic',
            'layout' => 'master'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'urut' => 4,
            'name' => 'Master Daily',
            'url' => 'daily',
            'icon' => 'ni-calendar-grid-58',
            'tabel' => 'ms_daily',
            'layout' => 'master'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rsyslg',
            'urut' => 1,
            'name' => 'Log User',
            'url' => 'rsyslg',
            'icon' => 'ni-ui-04',
            'tabel' => '-',
            'layout' => 'report'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpmelh',
            'urut' => 2,
            'name' => 'Laporan Pemeliharaan',
            'url' => 'report-pemeliharaan',
            'icon' => 'ni-calendar-grid-58',
            'tabel' => '-',
            'layout' => 'report'
        ]);
        DB::table('sys_dmenu')->insert([
            'gmenu' => 'report',
            'dmenu' => 'rpnphd',
            'urut' => 3,
            'name' => 'Laporan Non Periodic',
            'url' => 'report-non-periodic-hrd',
            'icon' => 'ni-single-copy-04',
            'tabel' => '-',
            'notif' => '',
            'layout' => 'report'
        ]);

        //insert tabel sys_auth        
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'blankx',
            'dmenu' => 'dashbr',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'usersx',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'rolesx',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'authxx',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'tablex',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'setupx',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'gmenux',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'dmenux',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'sysidx',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'system',
            'dmenu' => 'syscnt',
            'add' => '0',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'transc',
            'dmenu' => 'truser',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'transc',
            'dmenu' => 'trreq',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'transc',
            'dmenu' => 'trhrd',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'transc',
            'dmenu' => 'trpetp',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'transc',
            'dmenu' => 'trpetn',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'transc',
            'dmenu' => 'trpetd',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'transc',
            'dmenu' => 'trdper',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'report',
            'dmenu' => 'rpmelh',
            'add' => '1',
            'edit' => '0',
            'delete' => '0'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'usersx',
            'gmenu' => 'blankx',
            'dmenu' => 'dashbr',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'usersx',
            'gmenu' => 'transc',
            'dmenu' => 'truser',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'headxx',
            'gmenu' => 'transc',
            'dmenu' => 'trreq',
            'add' => '0',
            'edit' => '1',
            'delete' => '0'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'transc',
            'dmenu' => 'truser',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'transc',
            'dmenu' => 'trhrd',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'blankx',
            'dmenu' => 'dashhd',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'transc',
            'dmenu' => 'trdper',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'master',
            'dmenu' => 'msarea',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'master',
            'dmenu' => 'msprdc',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'report',
            'dmenu' => 'rpmelh',
            'add' => '1',
            'edit' => '0',
            'delete' => '0'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'hrdxxx',
            'gmenu' => 'report',
            'dmenu' => 'rpnphd',
            'add' => '1',
            'edit' => '0',
            'delete' => '0',
            'approval' => '0',
            'value' => '0',
            'print' => '1',
            'excel' => '1',
            'pdf' => '1',
            'rules' => '0',
            'isactive' => '1',
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'ptgoff',
            'gmenu' => 'transc',
            'dmenu' => 'trpetp',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'ptgspu',
            'gmenu' => 'transc',
            'dmenu' => 'trpetp',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        // Non-Periodic: Hanya Petugas PU yang bisa akses
        DB::table('sys_auth')->insert([
            'idroles' => 'ptgspu',
            'gmenu' => 'transc',
            'dmenu' => 'trpetn',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'ptgoff',
            'gmenu' => 'transc',
            'dmenu' => 'trpetd',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'ptgspu',
            'gmenu' => 'transc',
            'dmenu' => 'trpetd',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'master',
            'dmenu' => 'msenum',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'master',
            'dmenu' => 'msarea',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'master',
            'dmenu' => 'msprdc',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'master',
            'dmenu' => 'msdail',
            'add' => '1',
            'edit' => '1',
            'delete' => '1'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'report',
            'dmenu' => 'rsyslg',
            'add' => '1',
            'edit' => '0',
            'delete' => '0'
        ]);
        DB::table('sys_auth')->insert([
            'idroles' => 'admins',
            'gmenu' => 'report',
            'dmenu' => 'rpnphd',
            'add' => '1',
            'edit' => '0',
            'delete' => '0',
            'approval' => '0',
            'value' => '0',
            'print' => '1',
            'excel' => '1',
            'pdf' => '1',
            'rules' => '0',
            'isactive' => '1',
        ]);

        //sys_enum
        DB::table('sys_enum')->insert([
            'idenum' => 'isactive',
            'value' => '1',
            'name' => 'Active'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'isactive',
            'value' => '0',
            'name' => 'Not Active'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'status',
            'value' => '1',
            'name' => 'Sukses'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'status',
            'value' => '0',
            'name' => 'Gagal'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'questions',
            'value' => '1',
            'name' => 'YA'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'questions',
            'value' => '0',
            'name' => 'TIDAK'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'period',
            'value' => 'mingguan',
            'name' => 'Mingguan'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'period',
            'value' => 'bulanan',
            'name' => 'Bulanan'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'layout',
            'value' => 'manual',
            'name' => 'Manual'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'layout',
            'value' => 'master',
            'name' => 'Master'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'layout',
            'value' => 'system',
            'name' => 'System'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'layout',
            'value' => 'report',
            'name' => 'Report'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'layout',
            'value' => 'transc',
            'name' => 'Transaction'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'layout',
            'value' => 'standr',
            'name' => 'Standard'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'layout',
            'value' => 'sublnk',
            'name' => 'Sub Link'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'source',
            'value' => 'int',
            'name' => 'Internal'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'source',
            'value' => 'ext',
            'name' => 'External'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'source',
            'value' => 'th4',
            'name' => 'Tahun 4 digit'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'source',
            'value' => 'th2',
            'name' => 'Tahun 2 Digit'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'source',
            'value' => 'bln',
            'name' => 'Bulan'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'source',
            'value' => 'tgl',
            'name' => 'Tanggal'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'source',
            'value' => 'cnt',
            'name' => 'Counter'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'position',
            'value' => '0',
            'name' => 'Standard'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'position',
            'value' => '1',
            'name' => 'Header'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'position',
            'value' => '2',
            'name' => 'Detail'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'position',
            'value' => '3',
            'name' => 'Left'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'position',
            'value' => '4',
            'name' => 'Right'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'char',
            'name' => 'Char'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'string',
            'name' => 'String'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'email',
            'name' => 'Email'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'enum',
            'name' => 'Select Option'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'image',
            'name' => 'Image'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'join',
            'name' => 'Join'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'number',
            'name' => 'Number'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'password',
            'name' => 'Password'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'report',
            'name' => 'Report'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'text',
            'name' => 'Text'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'hidden',
            'name' => 'Hidden'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'date',
            'name' => 'Date'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'date2',
            'name' => 'Date Between'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'file',
            'name' => 'File Upload'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'search',
            'name' => 'Modal Search'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'currency',
            'name' => 'Currency'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'type',
            'value' => 'sublink',
            'name' => 'Sub Link'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'primary',
            'value' => '1',
            'name' => 'YA'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'primary',
            'value' => '0',
            'name' => 'TIDAK'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'primary',
            'value' => '2',
            'name' => 'UNIQUE'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'tahun',
            'value' => '2024',
            'name' => '2024'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'tahun',
            'value' => '2025',
            'name' => '2025'
        ]);
        DB::table('sys_enum')->insert([
            'idenum' => 'tahun',
            'value' => '2026',
            'name' => '2026'
        ]);

        //insert tabel sys_app        
        DB::table('sys_app')->insert([
            'appid' => 'msjframework',
            'appname' => 'MSJFramework',
            'description' => 'Framework Pembuatan Aplikasi Baru',
            'company' => 'PT MULTI SPUNINDO JAYA Tbk',
            'address' => 'Desa Jabaran, Balongbendo 61263.',
            'city' => 'SIDOARJO',
            'province' => 'JAWA TIMUR',
            'country' => 'INDONESIA',
            'telephone' => '+62-31-897 1301, 897 5555',
            'fax' => '+62-31-897 6666'
        ]);

        //other seeder
        $this->call([
            tabel_users::class,
            tabel_tabel_menu::class,
            tabel_transc_pu::class,
            tabel_master_area::class,
            tabel_master_periodic::class,
            tabel_master_daily::class,
            tabel_sys_gmenu::class,
            tabel_sys_dmenu::class,
            tabel_sys_enum::class,
            tabel_sys_id::class,
            tabel_sys_counter::class,
            tabel_sys_app::class,
            tabel_sys_role::class,
            tabel_sys_auth::class,
            tabel_rpt_syslog::class,
            tabel_rpt_pemeliharaan::class,
            menu_rpt_seeder::class,
            tabel_rpt_seeder::class,
            tabel_rpt_non_periodic_hrd::class,
            example_call_seed::class,
            seed_master_periodic::class,
            seed_master_daily::class,
            seed_master_requester::class,
            PlPeriodicReportSampleSeeder::class,
            PlNonPeriodicSeeder::class,
        ]);
    }
}
