<?php

namespace App\Http\Controllers;

use App\Helpers\Format_Helper;
use App\Helpers\Function_Helper;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class TranscController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index($data)
    {
        // function helper
        $data['format'] = new Format_Helper;
        //list data table
        $data['table_header_h'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'list' => '1', 'position' => '1'])->orderBy('urut')->get();
        $data['table_header_d'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'list' => '1', 'position' => '2'])->orderBy('urut')->get();
        $data['table_detail_h'] = collect();
        //set default data
        (Session::has('idtrans')) ? $primaryArray = explode(':', Session::get('idtrans')) : $primaryArray = ['-', '-', '-', '-', '-'];
        $i = 0;
        $wherekey_h = [];
        foreach ($data['table_header_h'] as $header_h) {
            ($header_h->query != '') ? $data['table_detail_h'] = DB::select($header_h->query) : $data['table_detail_h'] = $data['table_detail_h'];
            $wherekey_h[$header_h->field] = $primaryArray[$i];
            $i++;
        }
        //list data table
        $data['colomh'] = $i;
        $data['table_detail_d'] = DB::table($data['tabel'])->where($wherekey_h)->get();
        $data['table_primary_h'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'position' => '1', 'primary' => '1'])->orderBy('urut')->get();
        $data['table_primary_d'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'position' => '2', 'primary' => '1'])->orderBy('urut')->get();
        // check data table primary
        if ($data['table_primary_h']) {
            // return page menu
            return view($data['url'], $data);
        } else {
            //if not exist
            $data['url_menu'] = 'error';
            $data['title_group'] = 'Error';
            $data['title_menu'] = 'Error';
            $data['errorpages'] = 'Not Found!';
            //return error page
            return view("pages.errorpages", $data);
        }
    }
    /**
     * Display the specified resource with ajax.
     */
    public function ajax($data)
    {
        //check decrypt
        try {
            $id = decrypt($_GET['id']);
        } catch (DecryptException $e) {
            $id = "";
        }
        // data primary key
        $primaryArray = explode(':', $id);
        //list data table
        $data['table_header_h'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'list' => '1', 'position' => '1'])->orderBy('urut')->get();
        $i = 0;
        $wherekey_h = [];
        foreach ($data['table_header_h'] as $key_h) {
            $wherekey_h[$key_h->field] = $primaryArray[$i];
            $i++;
        }
        // ajax id
        $data['ajaxid'] = $id;
        //list data table
        $data['table_detail_d_ajax'] = DB::table($data['tabel'])->where($wherekey_h)->get();
        $data['table_primary_d_ajax'] = DB::table('sys_table')->where(['gmenu' => $_GET['gmenu'], 'dmenu' => $_GET['dmenu'], 'primary' => '1'])->orderBy('urut')->get();
        $data['table_header_d'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'list' => '1', 'position' => '2'])->orderBy('urut')->get();
        $data['table_header_d_ajax'] = DB::table('sys_table')->where(['gmenu' => $_GET['gmenu'], 'dmenu' => $_GET['dmenu'], 'list' => '1', 'position' => '2'])->orderBy('urut')->get();
        // set encrypt primery key
        $data['encrypt_primary'] = array();
        $data['data_join'] = array();
        $query_join = DB::table('sys_table')->where(['gmenu' => $_GET['gmenu'], 'dmenu' => $_GET['dmenu'], 'position' => '2', 'type' => 'join'])->whereNot('query', '')->orderBy('urut')->first();
        foreach ($data['table_detail_d_ajax'] as $detail) {
            $data_primary = '';
            foreach ($data['table_primary_d_ajax'] as $primary) {
                ($data_primary == '') ? $data_primary = $detail->{$primary->field} : $data_primary = $data_primary . ':' . $detail->{$primary->field};
            }
            if ($query_join) {
                $val_join =  DB::select($query_join->query . " '" . $detail->{$query_join->field} . "'");
                array_push($data['data_join'], $val_join);
            }
            array_push($data['encrypt_primary'], encrypt($data_primary));
        }
        // data query
        $data['table_query_ajax'] = DB::table('sys_table')->where(['gmenu' => $_GET['gmenu'], 'dmenu' => $_GET['dmenu'], 'position' => '2'])->whereNot('query', '')->whereNot('type', 'join')->orderBy('urut')->get();
        foreach ($data['table_query_ajax'] as $query) {
            $data[$query->field] = DB::select($query->query);
        }
        // }
        return json_encode($data);
    }
    /**
     * Display the specified resource.
     */
    public function add($data)
    {
        // function helper
        $syslog = new Function_Helper;
        //list data table
        $data['table_primary'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1', 'position' => '1'])->orderBy('urut')->get();
        $data['table_header'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'show' => '1'])->orderBy('urut')->get();
        //check decrypt
        try {
            $id = decrypt($data['idencrypt']);
        } catch (DecryptException $e) {
            $id = "";
        }
        // data primary key
        $primaryArray = explode(':', $id);
        $wherekey = [];
        $i = 0;
        if ($id != "") {
            foreach ($data['table_primary'] as $key) {
                $wherekey[$key->field] = $primaryArray[$i];
                $i++;
            }
        }
        $data['wherekey'] = $wherekey;
        
        // Load area and requester options for PU forms
        if ($data['dmenu'] == 'truser') {
            $data['areaOptions'] = $this->getAreaOptions();
            $data['requesterOptions'] = $this->getRequesterOptions();
        }
        
        //check athorization access add
        if ($data['authorize']->add == '1') {
            // return page menu
            return view($data['url'], $data);
        } else {
            //if not athorize
            $data['url_menu'] = $data['url_menu'];
            $data['title_group'] = 'Error';
            $data['title_menu'] = 'Error';
            $data['errorpages'] = 'Not Authorized!';
            //insert log
            $syslog->log_insert('E', $data['url_menu'], 'Not Authorized!' . ' - Add -' . $data['url_menu'], '0');
            //return error page
            return view("pages.errorpages", $data);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store($data)
    {
        // function helper
        $data['format'] = new Format_Helper;
        $syslog = new Function_Helper;
        $sessionIdentity = session('username');
        $authUser = auth()->user();
        $currentUser = $authUser;

        if (!$currentUser && $sessionIdentity !== null && $sessionIdentity !== '') {
            $sessionIdentity = (string) $sessionIdentity;

            if (is_numeric($sessionIdentity)) {
                $currentUser = User::where('id', (int) $sessionIdentity)->first();
            }

            if (!$currentUser) {
                $currentUser = User::whereRaw('LOWER(username) = ?', [strtolower($sessionIdentity)])->first();
            }
        }

        $currentUsername = (string) ($currentUser->username ?? '');
        if ($currentUsername === '' && $sessionIdentity !== null && $sessionIdentity !== '' && !is_numeric((string) $sessionIdentity)) {
            $currentUsername = (string) $sessionIdentity;
        }
        $currentRoles = $currentUser ? array_map('trim', explode(',', strtolower((string) $currentUser->idroles))) : [];
        $isHrdSubmitter = in_array('hrdxxx', $currentRoles, true) || in_array('hrd', $currentRoles, true);
        
        // Special handling for dmenu trdper (Daftar Periodic HRD)
        if ($data['dmenu'] == 'trdper') {
            // Validate input
            $attributes = request()->validate(
                [
                    'tahun' => 'required|numeric|min:' . date('Y') . '|unique:pl_periodic_header,tahun',
                    'keterangan' => 'required|string|max:255'
                ],
                [
                    'required' => ':attribute tidak boleh kosong',
                    'unique' => 'Tahun :input sudah ada',
                    'numeric' => ':attribute harus berupa angka',
                    'min' => ':attribute minimal :min',
                    'max' => ':attribute maksimal :max karakter'
                ]
            );
            
            // Insert data to pl_periodic_header
            $insert_data = DB::table('pl_periodic_header')->insert([
                'tahun' => $attributes['tahun'],
                'keterangan' => $attributes['keterangan'],
                'is_active' => '1',
                'user_create' => session('username'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            if ($insert_data) {
                // Insert log
                $syslog->log_insert('C', $data['dmenu'], 'Created Periodic HRD: ' . $attributes['tahun'], '1');
                // Set session message
                Session::flash('message', 'Data Periodic HRD berhasil ditambahkan!');
                Session::flash('class', 'success');
                // Redirect to list
                return redirect($data['url_menu'])->with($data);
            } else {
                // Insert log error
                $syslog->log_insert('E', $data['dmenu'], 'Create Periodic HRD Error', '0');
                // Set session message
                Session::flash('message', 'Gagal menambahkan data!');
                Session::flash('class', 'danger');
                // Redirect back
                return redirect()->back()->withInput();
            }
        }
        
        //list data table
        $data['table_header'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'show' => '1'])->orderBy('urut')->get();
        $data['table_primary'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1'])->orderBy('urut')->get();
        $data['table_primary_h'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1', 'position' => '1'])->orderBy('urut')->get();
        $sys_id = DB::table('sys_id')->where('dmenu', $data['dmenu'])->orderBy('urut', 'ASC')->first();
        //cek data primary key
        $wherekey = [];
        $idtrans = '';
        foreach ($data['table_primary'] as $key) {
            $wherekey[$key->field] = request()->{$key->field};
            $idtrans = ($idtrans == '') ? $idtrans = request()->{$key->field} : $idtrans . ',' . request()->{$key->field};
        }
        $idtrans_h = '';
        foreach ($data['table_primary_h'] as $key) {
            $idtrans_h = ($idtrans_h == '') ? $idtrans_h = request()->{$key->field} : $idtrans_h . ':' . request()->{$key->field};
        }
        $data_key = DB::table($data['tabel'])->where($wherekey)->first();
        //get data validate
        foreach ($data['table_header']->map(function ($item) {
            return (array) $item;
        }) as $item) {
            $primary = false;
            $generateid = false;
            foreach ($data['table_primary'] as $p) {
                $primary == false
                    ? ($p->field == $item['field']
                        ? ($primary = true)
                        : ($primary = false))
                    : '';
                $generateid == false
                    ? ($p->generateid != ''
                        ? ($generateid = true)
                        : ($generateid = false))
                    : '';
            }
            if ($primary  && $sys_id) {
                $validate[$item['field']] = '';
            } elseif ($primary && !$data_key) {
                $validate[$item['field']] = '';
            } else {
                $validate[$item['field']] = $item['validate'];
            }
        }
        if (isset($validate['attachment']) && is_array(request()->file('attachment'))) {
            $validate['attachment'] = 'nullable';
            $validate['attachment.*'] = 'file|mimes:jpeg,png,jpg,gif,pdf|max:4096';
        }

        //validasi data
        $attributes = request()->validate(
            $validate,
            [
                'required' => ':attribute tidak boleh kosong',
                'unique' => ':attribute sudah ada',
                'min' => ':attribute minimal :min karakter',
                'max' => ':attribute maksimal :max karakter',
                'email' => 'format :attribute salah',
                'mimes' => ':attribute format harus :values',
                'between' => ':attribute diisi antara :min sampai :max'
            ]
        );
        //check password
        if (isset($attributes['password'])) {
            //encrypt password
            $new_password = bcrypt($attributes['password']);
            $attributes['password'] = $new_password;
        }
        // check data image and file
        $data['image'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu']])->whereIn('type', ['image', 'file'])->get();
        foreach ($data['image'] as $img) {
            $uploadedFiles = $this->normalizeUploadedFiles(request()->file($img->field));
            if (empty($uploadedFiles)) {
                continue;
            }

            if ($img->field === 'attachment' && count($uploadedFiles) > 1) {
                $storedPaths = [];
                foreach ($uploadedFiles as $uploadedFile) {
                    $storedPaths[] = $uploadedFile->store($data['tabel'], 'public');
                }
                $attributes[$img->field] = json_encode($storedPaths, JSON_UNESCAPED_SLASHES);
            } else {
                $attributes[$img->field] = $uploadedFiles[0]->store($data['tabel'], 'public');
            }
        }
        //list data
        $data['table_header'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'],  'list' => '1'])->orderBy('urut')->get();
        $data['table_detail'] = DB::table($data['tabel'])->get();
        $data['table_primary_generate'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1'])->orderBy('urut')->first();
        //check data Generate ID
        if ($sys_id) {
            $insertPayload = [$data['table_primary_generate']->field => $data['format']->IDFormat($data['dmenu'])] + $attributes + ['user_create' => $currentUsername];

            if ($data['dmenu'] === 'truser' && $currentUser) {
                $insertPayload['user_id'] = $currentUser->id;
            }

            // HRD self-submission uses user flow but skips Head approval stage.
            if ($data['dmenu'] === 'truser' && $isHrdSubmitter) {
                $insertPayload['request_status'] = 'review';
                $insertPayload['head_approval_date'] = now()->toDateString();
                $insertPayload['head_note'] = 'Auto skip head approval: submitted by HRD';
                if ($currentUser) {
                    $insertPayload['head_approver_id'] = $currentUser->id;
                    $insertPayload['user_id'] = $currentUser->id;
                }
            }

            //set ID from generate id
            $insert_data = DB::table($data['tabel'])->insert($insertPayload);
        } else {
            $insertPayload = $attributes + ['user_create' => $currentUsername];

            if ($data['dmenu'] === 'truser' && $currentUser) {
                $insertPayload['user_id'] = $currentUser->id;
            }

            // HRD self-submission uses user flow but skips Head approval stage.
            if ($data['dmenu'] === 'truser' && $isHrdSubmitter) {
                $insertPayload['request_status'] = 'review';
                $insertPayload['head_approval_date'] = now()->toDateString();
                $insertPayload['head_note'] = 'Auto skip head approval: submitted by HRD';
                if ($currentUser) {
                    $insertPayload['head_approver_id'] = $currentUser->id;
                    $insertPayload['user_id'] = $currentUser->id;
                }
            }

            //set ID manual
            $insert_data = DB::table($data['tabel'])->insert($insertPayload);
        }
        //check insert
        if ($insert_data) {
            //insert sys_log
            $syslog->log_insert('C', $data['dmenu'], 'Created : ' . $idtrans, '1');
            // Set a session message
            Session::flash('message', 'Tambah Data Berhasil!');
            Session::flash('class', 'success');
            Session::flash('idtrans', $idtrans_h);
            // return page menu
            return redirect($data['url_menu'])->with($data);
        } else {
            //insert sys_log
            $syslog->log_insert('E', $data['dmenu'], 'Create Error', '0');
            // Set a session message
            Session::flash('message', 'Tambah Data Gagal!');
            Session::flash('class', 'danger');
            Session::flash('idtrans', $idtrans_h);
            // return page menu
            return redirect($data['url_menu'])->with($data);
        };
    }
    /**
     * Display the specified resource.
     */
    public function show($data)
    {
        if ($data['dmenu'] == 'trdper') {
            $tahun = $data['idencrypt'];
            $header = DB::table('pl_periodic_header')
                ->where('tahun', $tahun)
                ->where('is_active', '1')
                ->first();

            if (!$header) {
                $data['url_menu'] = 'error';
                $data['title_group'] = 'Error';
                $data['title_menu'] = 'Error';
                $data['errorpages'] = 'Not Found!';
                return view("pages.errorpages", $data);
            }

            return view($data['url'], $data);
        }
        //list data table
        $data['table_header'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'],  'filter' => '1', 'show' => '1'])->orderBy('urut')->get();
        $data['table_primary'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1'])->orderBy('urut')->get();
        //check decrypt
        try {
            $id = decrypt($data['idencrypt']);
        } catch (DecryptException $e) {
            $id = "";
        }
        // data primary key
        $primaryArray = explode(':', $id);
        $wherekey = [];
        $i = 0;
        foreach ($data['table_primary'] as $key) {
            $wherekey[$key->field] = $primaryArray[$i];
            $i++;
        }
        $list = DB::table($data['tabel'])->where($wherekey)->first();
        // check data list
        if ($list) {
            $data['list'] = $list;
            // return page menu
            return view($data['url'], $data);
        } else {
            //if not exist
            $data['url_menu'] = 'error';
            $data['title_group'] = 'Error';
            $data['title_menu'] = 'Error';
            $data['errorpages'] = 'Not Found!';
            //return error page
            return view("pages.errorpages", $data);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($data)
    {
        // function helper
        $syslog = new Function_Helper;
        if ($data['dmenu'] == 'trdper') {
            $tahun = $data['idencrypt'];
            $header = DB::table('pl_periodic_header')
                ->where('tahun', $tahun)
                ->where('is_active', '1')
                ->first();

            if (!$header) {
                $data['url_menu'] = 'error';
                $data['title_group'] = 'Error';
                $data['title_menu'] = 'Error';
                $data['errorpages'] = 'Not Found!';
                return view("pages.errorpages", $data);
            }

            if ($data['authorize']->edit == '1') {
                return view('transc.auto.show', $data);
            }

            $data['url_menu'] = $data['url_menu'];
            $data['title_group'] = 'Error';
            $data['title_menu'] = 'Error';
            $data['errorpages'] = 'Not Authorized!';
            $syslog->log_insert('E', $data['url_menu'], 'Not Authorized!' . ' - Edit -' . $data['url_menu'], '0');
            return view("pages.errorpages", $data);
        }
        //list data table
        $data['table_header'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'],  'filter' => '1', 'show' => '1'])->orderBy('urut')->get();
        $data['table_primary'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1'])->orderBy('urut')->get();
        //check decrypt
        try {
            $id = decrypt($data['idencrypt']);
        } catch (DecryptException $e) {
            $id = "";
        }
        // data primary key
        $primaryArray = explode(':', $id);
        $wherekey = [];
        $i = 0;
        foreach ($data['table_primary'] as $key) {
            $wherekey[$key->field] = $primaryArray[$i];
            $i++;
        }
        $list = DB::table($data['tabel'])->where($wherekey)->first();
        // check data list
        if ($list) {
            //check athorization access edit
            if ($data['authorize']->edit == '1') {
                $data['list'] = $list;
                
                // Load area and requester options for PU forms
                if ($data['dmenu'] == 'truser') {
                    $data['areaOptions'] = $this->getAreaOptions();
                    $data['requesterOptions'] = $this->getRequesterOptions();
                }
                
                // return page menu
                return view($data['url'], $data);
            } else {
                //if not athorize
                $data['url_menu'] = $data['url_menu'];
                $data['title_group'] = 'Error';
                $data['title_menu'] = 'Error';
                $data['errorpages'] = 'Not Authorized!';
                //insert log
                $syslog->log_insert('E', $data['url_menu'], 'Not Authorized!' . ' - Edit -' . $data['url_menu'], '0');
                //return error page
                return view("pages.errorpages", $data);
            }
        } else {
            //if not exist
            $data['url_menu'] = 'error';
            $data['title_group'] = 'Error';
            $data['title_menu'] = 'Error';
            $data['errorpages'] = 'Not Found!';
            //return error page
            return view("pages.errorpages", $data);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update($data)
    {
        // function helper
        $syslog = new Function_Helper;
        //check decrypt
        try {
            $id = decrypt($data['idencrypt']);
        } catch (DecryptException $e) {
            $id = "";
        }
        //list data table
        $data['table_primary'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1'])->orderBy('urut')->get();
        $data['table_primary_h'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1', 'position' => '1'])->orderBy('urut')->get();
        // data primary key
        $primaryArray = explode(':', $id);
        $wherekey = [];
        $wherenotkey = [];
        $i = 0;
        foreach ($data['table_primary'] as $key) {
            $wherekey[$key->field] = $primaryArray[$i];
            $wherenotkey[] = $key->field;
            $i++;
        }
        $idtrans_h = '';
        $i = 0;
        foreach ($data['table_primary_h'] as $key) {
            $idtrans_h = ($idtrans_h == '') ? $idtrans_h = $primaryArray[$i] : $idtrans_h . ':' . $primaryArray[$i];
            $i++;
        }
        //list data
        $data['table_header'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'filter' => '1', 'show' => '1'])->whereNotIn('field', $wherenotkey)->orderBy('urut')->get();
        //get data validate
        foreach ($data['table_header']->map(function ($item) {
            return (array) $item;
        }) as $item) {
            if ($item['field'] == 'email') {
                $validate[$item['field']] = ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id, 'username')];
            } else if ($item['field'] == 'password' && request()->email && empty(request()->password)) {
                unset($validate[$item['field']]);
            } else {
                $validate[$item['field']] = $item['validate'];
            }
        }
        if (isset($validate['attachment']) && is_array(request()->file('attachment'))) {
            $validate['attachment'] = 'nullable';
            $validate['attachment.*'] = 'file|mimes:jpeg,png,jpg,gif,pdf|max:4096';
        }

        //validasi data
        $attributes = request()->validate(
            $validate,
            [
                'required' => ':attribute tidak boleh kosong',
                'unique' => ':attribute sudah ada',
                'min' => ':attribute minimal :min karakter',
                'max' => ':attribute maksimal :max karakter',
                'email' => 'format :attribute salah',
                'mimes' => ':attribute rormat harus :values',
                'between' => ':attribute diisi antara :min sampai :max'
            ]
        );
        //data password
        if (isset($attributes['password'])) {
            //encryp password
            $new_password = bcrypt($attributes['password']);
            $attributes['password'] = $new_password;
        }
        // check data image and file
        $data['image'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu']])->whereIn('type', ['image', 'file'])->get();
        $currentRecord = DB::table($data['tabel'])->where($wherekey)->first();
        foreach ($data['image'] as $img) {
            $uploadedFiles = $this->normalizeUploadedFiles(request()->file($img->field));
            if (empty($uploadedFiles)) {
                continue;
            }

            if ($img->field === 'attachment' && count($uploadedFiles) > 1) {
                $existing = $this->parseAttachmentPaths($currentRecord->{$img->field} ?? null);
                $newPaths = [];
                foreach ($uploadedFiles as $uploadedFile) {
                    $newPaths[] = $uploadedFile->store($data['tabel'], 'public');
                }

                $allPaths = array_values(array_unique(array_merge($existing, $newPaths)));
                $attributes[$img->field] = json_encode($allPaths, JSON_UNESCAPED_SLASHES);
            } else {
                $attributes[$img->field] = $uploadedFiles[0]->store($data['tabel'], 'public');
            }
        }
        //list data 
        $data['table_header'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'],  'list' => '1'])->orderBy('urut')->get();
        $data['table_detail'] = DB::table($data['tabel'])->get();
        // Update data by id
        $updateData = DB::table($data['tabel'])->where($wherekey)->update($attributes + ['user_update' => session('username')]);
        //check update
        if ($updateData) {
            //insert sys_log
            $syslog->log_insert('U', $data['dmenu'], 'Updated : ' . $id, '1');
            // Set a session message
            Session::flash('message', 'Edit User Berhasil!');
            Session::flash('class', 'success');
            Session::flash('idtrans', $idtrans_h);
            // return page menu
            return redirect($data['url_menu'])->with($data);
        } else {
            //insert sys_log
            $syslog->log_insert('E', $data['dmenu'], 'Update Error', '0');
            // Set a session message
            Session::flash('message', 'Edit User Gagal!');
            Session::flash('class', 'danger');
            Session::flash('idtrans', $idtrans_h);
            //return error page
            return redirect($data['url_menu'])->with($data);
        };
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($data)
    {
        // function helper
        $syslog = new Function_Helper;
        
        // Special handling for dmenu trdper (Daftar Periodic HRD)
        if ($data['dmenu'] == 'trdper') {
            $tahun = $data['idencrypt']; // tahun passed as parameter
            
            // Check if data exists
            $header = DB::table('pl_periodic_header')->where('tahun', $tahun)->first();
            
            if (!$header) {
                Session::flash('message', 'Data tidak ditemukan!');
                Session::flash('class', 'danger');
                return redirect($data['url_menu'])->with($data);
            }
            
            // Delete related details first
            DB::table('pl_periodic_detail')->where('header_id', $header->id)->delete();
            
            // Delete header permanently (hard delete)
            $deleteData = DB::table('pl_periodic_header')
                ->where('tahun', $tahun)
                ->delete();
            
            if ($deleteData) {
                $syslog->log_insert('D', $data['dmenu'], 'Deleted Periodic HRD: ' . $tahun, '1');
                Session::flash('message', 'Data berhasil dihapus!');
                Session::flash('class', 'success');
                return redirect($data['url_menu'])->with($data);
            } else {
                $syslog->log_insert('D', $data['dmenu'], 'Deleted Error: ' . $tahun, '0');
                Session::flash('message', 'Gagal menghapus data!');
                Session::flash('class', 'danger');
                return redirect($data['url_menu'])->with($data);
            }
        }
        
        // get field primary
        $data['table_primary'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1'])->orderBy('urut')->get();
        //list data
        $data['table_primary_h'] = DB::table('sys_table')->where(['gmenu' => $data['gmenuid'], 'dmenu' => $data['dmenu'], 'primary' => '1', 'position' => '1'])->orderBy('urut')->get();
        //check decrypt
        try {
            $id = decrypt($data['idencrypt']);
        } catch (DecryptException $e) {
            $id = "";
        }
        // data primary key
        $primaryArray = explode(':', $id);
        $wherekey = [];
        $i = 0;
        foreach ($data['table_primary'] as $key) {
            $wherekey[$key->field] = $primaryArray[$i];
            $i++;
        }
        $idtrans_h = '';
        $i = 0;
        foreach ($data['table_primary_h'] as $key) {
            $idtrans_h = ($idtrans_h == '') ? $idtrans_h = $primaryArray[$i] : $idtrans_h . ':' . $primaryArray[$i];
            $i++;
        }
        $deleteData = DB::table($data['tabel'])->where($wherekey)->delete();
        // check delete
        if ($deleteData) {
            //insert sys_log
            $syslog->log_insert('D', $data['dmenu'], 'Deleted : ' . $id, '1');
            // Set a session message
            Session::flash('message', 'Hapus Data Berhasil!');
            Session::flash('class', 'success');
            Session::flash('idtrans', $idtrans_h);
            return redirect($data['url_menu'])->with($data);
        } else {
            //insert sys_log
            $syslog->log_insert('D', $data['dmenu'], 'Deleted Error : ' . $id, '0');
            // Set a session message
            Session::flash('message', 'Hapus Data Gagal!');
            Session::flash('class', 'danger');
            Session::flash('idtrans', $idtrans_h);
            return redirect($data['url_menu'])->with($data);
        }
    }

    private function normalizeUploadedFiles($uploaded): array
    {
        if ($uploaded instanceof UploadedFile) {
            return [$uploaded];
        }

        if (is_array($uploaded)) {
            return array_values(array_filter($uploaded, fn($file) => $file instanceof UploadedFile));
        }

        return [];
    }

    private function parseAttachmentPaths($rawAttachment): array
    {
        if (empty($rawAttachment) || !is_string($rawAttachment)) {
            return [];
        }

        $decoded = json_decode($rawAttachment, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded, fn($path) => is_string($path) && trim($path) !== ''));
        }

        $parts = array_map('trim', explode(',', $rawAttachment));
        $parts = array_values(array_filter($parts, fn($path) => $path !== ''));

        if (!empty($parts)) {
            return $parts;
        }

        return [trim($rawAttachment)];
    }

    private function getAreaOptions(): array
    {
        $areas = DB::table('ms_area')
            ->where('is_active', '1')
            ->orderBy('nama_area')
            ->get();

        $result = [];
        foreach ($areas as $area) {
            if (!empty($area->nama_area)) {
                $result[$area->nama_area] = $area->id;
            }
        }
        return $result;
    }

    private function getRequesterOptions(): array
    {
        $requesters = DB::table('ms_requesters')
            ->where('is_active', 1)
            ->orderBy('requester_name')
            ->get();

        $result = [];
        foreach ($requesters as $requester) {
            if (!empty($requester->requester_name)) {
                $result[] = $requester->requester_name;
            }
        }
        return $result;
    }
}
