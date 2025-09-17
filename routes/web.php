<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\KpiNilaiController;
 

Route::get('/', function () {
    return view('Dash');
});

Route::get('/Alternatif', function () {
    return view('Alternatif.Index');
});

Route::resource('data-karyawan', DataKaryawanController::class);


route ::get('/Kriteria-Operator' , [KriteriaController::class,'index']);
route ::post('/Kriteria-Operator/store' , [KriteriaController::class,'store']);
route ::get('/Kriteria-Operator/{id}/edit' , [KriteriaController::class,'edit']);
route ::put('/Kriteria-Operator/{id}' , [KriteriaController::class,'update']);
route ::delete('/Kriteria-Operator/{id}' , [KriteriaController::class,'destroy']);

// route ::get('/Kriteria-Mekanik' , [KriteriaController::class,'index1']);
// route ::get('/Kriteria-HSSE' , [KriteriaController::class,'index2']);

route ::get('/DataKaryawan' , [DataKaryawanController::class,'index']);
route ::post('/DataKaryawan/store' , [DataKaryawanController::class,'store']);
route ::get('/DataKaryawan/{id}/edit' , [DataKaryawanController::class,'edit']);
route ::put('/DataKaryawan/{id}' , [DataKaryawanController::class,'update']);
route ::delete('/DataKaryawan/{id}' , [DataKaryawanController::class,'destroy']);

Route::get('/KPI/{id}', [KpiNilaiController::class, 'index'])->name('kpi.index');
Route::post('/KPI/store', [KpiNilaiController::class, 'store'])->name('kpi.store');