<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class UserController extends TranscController
{
	public function add($data)
	{
		$data['requesterOptions'] = $this->getRequesterOptions();
		$data['areaOptions'] = $this->getAreaOptions();
		return view('transc.user.add', $data);
	}

	public function show($data)
	{
		$id = $this->resolveId($data['idencrypt'] ?? null);
		$data['list'] = DB::table('pl_non_periodic')->where('id', $id)->first();

		if (!$data['list']) {
			Session::flash('class', 'danger');
			Session::flash('message', 'Data pengajuan tidak ditemukan.');
			return redirect($data['url_menu']);
		}

		$data['attachment_files'] = $this->parseAttachmentPaths($data['list']->attachment ?? null);

		return view('transc.user.show', $data);
	}

	public function edit($data)
	{
		$id = $this->resolveId($data['idencrypt'] ?? null);
		$data['list'] = DB::table('pl_non_periodic')->where('id', $id)->first();

		if (!$data['list']) {
			Session::flash('class', 'danger');
			Session::flash('message', 'Data pengajuan tidak ditemukan.');
			return redirect($data['url_menu']);
		}

		$data['attachment_files'] = $this->parseAttachmentPaths($data['list']->attachment ?? null);
		$data['requesterOptions'] = $this->getRequesterOptions();
		$data['areaOptions'] = $this->getAreaOptions();

		return view('transc.user.edit', $data);
	}

	public function store($data)
	{
		$sessionIdentity = trim((string) (session('username') ?? ''));
		$authUser = auth()->user();
		$authId = $authUser->id ?? null;
		$authUsername = trim((string) ($authUser->username ?? ''));
		$currentUser = null;

		if ($sessionIdentity !== '' && !is_numeric($sessionIdentity)) {
			$currentUser = DB::table('users')
				->whereRaw('LOWER(username) = ?', [strtolower($sessionIdentity)])
				->first();
		}

		if (!$currentUser && $authId) {
			$currentUser = DB::table('users')->where('id', $authId)->first();
		}

		if (!$currentUser && $authUsername !== '' && !is_numeric($authUsername)) {
			$currentUser = DB::table('users')
				->whereRaw('LOWER(username) = ?', [strtolower($authUsername)])
				->first();
		}

		if (!$currentUser && $sessionIdentity !== '' && is_numeric($sessionIdentity)) {
			$currentUser = DB::table('users')->where('id', (int) $sessionIdentity)->first();
		}

		$currentUsername = $currentUser->username
			?? ($sessionIdentity !== '' && !is_numeric($sessionIdentity)
				? $sessionIdentity
				: ($authUsername !== '' && !is_numeric($authUsername) ? $authUsername : null));

		$currentRoles = $currentUser ? array_map('trim', explode(',', strtolower((string) ($currentUser->idroles ?? '')))) : [];
		$isHrdSubmitter = in_array('hrdxxx', $currentRoles, true) || in_array('hrd', $currentRoles, true);

		$attributes = request()->validate(
			[
				'area_id' => 'required|string|max:50',
				'requester_name' => 'required|string|max:100',
				'job_description' => 'required|string',
				'attachment' => 'nullable',
				'attachment.*' => 'file|mimes:jpeg,png,jpg,gif,webp,jfif,heic,heif,pdf|max:10240',
				'request_status' => 'nullable|string|max:20',
			],
			[
				'required' => ':attribute tidak boleh kosong',
				'max' => ':attribute maksimal :max karakter',
				'attachment.*.mimes' => 'Format lampiran tidak didukung. Gunakan JPG, PNG, GIF, WEBP, JFIF, HEIC, HEIF, atau PDF.',
			]
		);

		unset($attributes['attachment']);
		$uploadedAttachments = $this->normalizeUploadedFiles(request()->file('attachment'));
		if (!empty($uploadedAttachments)) {
			$storedPaths = [];
			foreach ($uploadedAttachments as $uploadedAttachment) {
				$storedPaths[] = $uploadedAttachment->store('pl_non_periodic', 'public');
			}
			$attributes['attachment'] = json_encode($storedPaths, JSON_UNESCAPED_SLASHES);
		}

		$attributes['request_status'] = $isHrdSubmitter ? 'review' : ($attributes['request_status'] ?? 'pending');
		$attributes['user_id'] = $currentUser->id ?? null;
		$attributes['user_create'] = $currentUsername;

		if ($isHrdSubmitter) {
			$attributes['head_approval_date'] = now()->toDateString();
			$attributes['head_note'] = 'Auto skip head approval: submitted by HRD';
			$attributes['head_approver_id'] = $currentUser->id ?? null;
		}

		$attributes['created_at'] = now();
		$attributes['updated_at'] = now();

		try {
			DB::table('pl_non_periodic')->insert($attributes);
		} catch (\Throwable $e) {
			Log::error('UserController store failed', [
				'user' => $currentUsername,
				'roles' => $currentRoles,
				'message' => $e->getMessage(),
			]);

			Session::flash('class', 'danger');
			Session::flash('message', 'Pengajuan gagal disimpan. Silakan coba lagi atau hubungi admin.');

			return redirect()->back()->withInput();
		}

		Session::flash('class', 'success');
		Session::flash('message', 'Pengajuan berhasil disimpan.');

		return redirect($data['url_menu']);
	}

	public function update($data)
	{
		$id = $this->resolveId($data['idencrypt'] ?? null);
		$current = DB::table('pl_non_periodic')->where('id', $id)->first();
		if (!$current) {
			Session::flash('class', 'danger');
			Session::flash('message', 'Data pengajuan tidak ditemukan.');
			return redirect($data['url_menu']);
		}
		$attributes = request()->validate(
			[
				'area_id' => 'required|string|max:50',
				'requester_name' => 'required|string|max:100',
				'work_category' => 'required|string|max:50',
				'work_type' => 'required|string|max:20',
				'priority' => 'required|string|max:20',
				'vendor_name' => 'nullable|string|max:100',
				'location_detail' => 'nullable|string|max:150',
				'target_date' => 'nullable|date',
				'job_description' => 'required|string',
				'contact_name' => 'nullable|string|max:100',
				'contact_phone' => 'nullable|string|max:30',
				'attachment' => 'nullable',
				'attachment.*' => 'file|mimes:jpeg,png,jpg,gif,webp,jfif,heic,heif,pdf|max:10240',
				'request_status' => 'nullable|string|max:20',
			],
			[
				'required' => ':attribute tidak boleh kosong',
				'max' => ':attribute maksimal :max karakter',
				'attachment.*.mimes' => 'Format lampiran tidak didukung. Gunakan JPG, PNG, GIF, WEBP, JFIF, HEIC, HEIF, atau PDF.',
			]
		);

		unset($attributes['attachment']);
		$existingAttachments = $this->parseAttachmentPaths($current->attachment ?? null);
		$uploadedAttachments = $this->normalizeUploadedFiles(request()->file('attachment'));
		if (!empty($uploadedAttachments)) {
			$newPaths = [];
			foreach ($uploadedAttachments as $uploadedAttachment) {
				$newPaths[] = $uploadedAttachment->store('pl_non_periodic', 'public');
			}

			$allPaths = array_values(array_unique(array_merge($existingAttachments, $newPaths)));
			$attributes['attachment'] = json_encode($allPaths, JSON_UNESCAPED_SLASHES);
		}

		$attributes['request_status'] = $attributes['request_status'] ?? ($current->request_status ?? 'pending');
		$attributes['updated_at'] = now();

		$updated = DB::table('pl_non_periodic')->where('id', $id)->update($attributes);

		if (!$updated) {
			Session::flash('class', 'danger');
			Session::flash('message', 'Gagal memperbarui pengajuan.');
			return redirect($data['url_menu']);
		}

		Session::flash('class', 'success');
		Session::flash('message', 'Pengajuan berhasil diperbarui.');

		return redirect($data['url_menu']);
	}

	private function resolveId($encryptedId)
	{
		try {
			return decrypt($encryptedId);
		} catch (DecryptException $e) {
			return $encryptedId;
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

	private function getRequesterOptions(): array
	{
		if (!Schema::hasTable('ms_requesters')) {
			return [];
		}

		return DB::table('ms_requesters')
			->where('is_active', '1')
			->orderBy('requester_name')
			->pluck('requester_name')
			->filter(fn($name) => is_string($name) && trim($name) !== '')
			->values()
			->all();
	}

	private function getAreaOptions(): array
	{
		if (!Schema::hasTable('ms_area')) {
			return [];
		}

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
}
