<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KpiInputController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\BulkImportController;
use App\Http\Controllers\HasilAkhirController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\KriteriaImportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilAkhirExportController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\Auth\ForgotPasswordController;


Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

Route::controller(LoginController::class)->group(function() {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.store');
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.store');
    Route::post('/logout', 'logout')->name('logout');
});


Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot.password');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot.password.send');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('reset.password');
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset.password.update');


Route::middleware('auth')->group(function () {

    route::get('/home1', [AtasanController::class,'index'])->name('dashboard1');
    Route::get('/kriteria1', [KriteriaController::class, 'index'])->name('kriteria1.index');
    Route::get('/kriteria1/{kriteria}', [KriteriaController::class, 'show1'])->name('kriteria1.show');
    route::get('/karyawan1', [KaryawanController::class,'index'])->name('karyawan1.index');
    Route::get('/karyawan1/{karyawan}/nilai', [KpiInputController::class, 'form'])->name('karyawan1.nilai.form'); 
    Route::get('/alternatif1', [AlternatifController::class, 'index'])->name('alternatif.index');
    Route::get('/hasil-akhir1', [HasilAkhirController::class, 'index'])->name('hasil1.index');


    Route::middleware('role:1')->group(function () {

        Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif.index');
        Route::post('/import/master', [BulkImportController::class, 'importMaster'])->name('import.master');

        Route::resource('kriteria', KriteriaController::class);

        Route::post('/kriteria/import', [KriteriaImportController::class, 'import'])->name('kriteria.import.run');
        Route::get('/kriteria/template', [KriteriaImportController::class, 'templates'])->name('kriteria.template');

        route ::get('/Sub-Kriteria-Operator' , [SubKriteriaController::class,'index']);
        route ::post('/Sub-Kriteria-Operator/store' , [SubKriteriaController::class,'store']);
        route ::get('/Sub-Kriteria-Operator/{id}/edit' , [SubKriteriaController::class,'edit']);
        route ::put('/Sub-Kriteria-Operator/{id}' , [SubKriteriaController::class,'update']);
        route ::delete('/Sub-Kriteria-Operator/{id}' , [SubKriteriaController::class,'destroy']);


        Route::resource('karyawan', KaryawanController::class); // CRUD karyawan

        // input KPI tahunan per karyawan
        Route::get('/karyawan/{karyawan}/nilai', [KpiInputController::class,'form'])->name('karyawan.nilai.form');
        Route::post('/karyawan/{karyawan}/nilai', [KpiInputController::class,'save'])->name('karyawan.nilai.save');

        Route::get('/hasil-akhir', [HasilAkhirController::class, 'index'])->name('hasil.index');
        Route::get('/hasil-akhir/{karyawan}/pdf', [HasilAkhirExportController::class, 'export'])->name('hasil.pdf');
    });



});


