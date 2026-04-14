<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\PageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HrdController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\DailyLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Controllers\UserController;
use App\Services\Auth\HomeRedirectService;

Route::get('/', function () {
	$target = app(HomeRedirectService::class)->resolveHomePath(auth()->user());

	return redirect('/' . $target);
})->middleware('auth');
Route::get('/auth/{token}', [LoginController::class, 'auth'])->name('auth');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/dashboard-hrd', [PageController::class, 'dashboardHrd'])->name('dashboard.hrd'); //HRD dashboard
	Route::get('/dashboard-petugas', [PageController::class, 'dashboardPetugas'])->name('dashboard.petugas'); //Petugas dashboard
	Route::get('/profile', [PageController::class, 'profile'])->name('profile'); //view profile
	Route::post('/profile/update', [PageController::class, 'update'])->name('profile.update'); //update profle
	Route::get('/changepass', [PageController::class, 'changepass'])->name('changepass'); //view change password
	Route::post('/changepass/update', [PageController::class, 'changepass_update'])->name('changepass.update'); //update password
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
	Route::post('/pengajuan-head/update', [HeadController::class, 'update'])->name('pengajuan-head.update');
	Route::post('/pengajuan-hrd/update', [HrdController::class, 'update'])->name('pengajuan-hrd.update');
	Route::post('/daily-log', [DailyLogController::class, 'store'])->name('daily-log.store');
	Route::post('/work-schedule/complete', [WorkScheduleController::class, 'complete'])->name('work-schedule.complete');
	Route::post('/work-schedule/verify', [WorkScheduleController::class, 'verify'])->name('work-schedule.verify');
	Route::post('/plan-schedule/update', [WorkScheduleController::class, 'updateSchedule'])->name('plan-schedule.update');
	Route::post('/periodic-schedule/save-plan-dates', [WorkScheduleController::class, 'savePlanDates'])->name('periodic-schedule.save-plan-dates');
	Route::post('/periodic-schedule/assign-worker', [WorkScheduleController::class, 'assignWorker'])->name('periodic-schedule.assign-worker');
	Route::post('/periodic-schedule/store', [WorkScheduleController::class, 'store'])->name('periodic-schedule.store');
	Route::post('/periodic-schedule/destroy', [WorkScheduleController::class, 'destroy'])->name('periodic-schedule.destroy');
	Route::post('/periodic-schedule/bulk-save', [WorkScheduleController::class, 'bulkSavePeriodic'])->name('periodic-schedule.bulk-save');
	Route::post('/periodic-schedule/detail-delete', [WorkScheduleController::class, 'deletePeriodicDetail'])->name('periodic-schedule.detail-delete');
	Route::post('/user/draft/confirm', [UserController::class, 'confirmDraft'])->name('user.draft.confirm');
	Route::post('/user/draft/delete', [UserController::class, 'deleteDraft'])->name('user.draft.delete');
	Route::get('/report/{dmenu}/excel-images', [ReportController::class, 'exportExcelImages'])->name('report.excel-images');
	Route::get('/{page}', [PageController::class, 'index'])->name(''); //route list
	Route::post('/{page}', [PageController::class, 'index'])->name(''); //route store
	Route::get('/{page}/{action}', [PageController::class, 'index'])->name(''); //route show, add, edit
	Route::put('/{page}/{action}', [PageController::class, 'index'])->name(''); //route update
	Route::delete('/{page}/{action}', [PageController::class, 'index'])->name(''); //route delete(destroy)
	Route::get('/{page}/{action}/{id}', [PageController::class, 'index'])->name(''); //route CRUD
});
