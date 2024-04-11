<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PoliceStationController;
use App\Http\Controllers\Api\PoliceController;
use App\Models\PoliceStation;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|registerPolice
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users' ,[ApiController::class ,'index'])->name('users.store');
Route::get('/polices' ,[PoliceController::class ,'index'])->name('polices.store');

Route::get('/policesform' ,[PoliceStationController::class ,'sigla'])->name('policesform.store');

Route::get('/stations' ,[PoliceStationController::class ,'index'])->name('stations.store');

Route::post('/create' ,[ApiController::class ,'register'])->name('user.register');
Route::post('/stationcreat' ,[PoliceStationController::class ,'registerPost'])->name('station.register');
Route::post('/Policecreate' ,[PoliceController::class ,'registerPolice'])->name('police.register');
Route::delete('/users/{user}' , [ApiController::class , 'destroy'])->name("user.destroy");
Route::delete('/police/{police}' , [PoliceController::class , 'destroy'])->name("police.destroy");
Route::delete('/policestation/{station}' , [PoliceStationController::class , 'destroy'])->name("policestation.destroy");


Route::get('/usersform',function(){
    return view('usersform');
});



Route::get('/stationsform',function(){
    return view('stationsform');
});

Route::get('/',function(){
    return view('chooseaccounttype');
});


