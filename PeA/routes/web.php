<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Api\ApiController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users' ,[ApiController::class ,'index'])->name('users.store');
Route::post('/create' ,[ApiController::class ,'register'])->name('user.register');
Route::delete('/users/{user}' , [ApiController::class , 'destroy'])->name("user.destroy");

Route::get('/usersform',function(){
    return view('usersform');
});

Route::get('/policesform',function(){
    return view('policesform');
});

Route::get('/stationsform',function(){
    return view('stationsform');
});

Route::get('/chooseaccounttype',function(){
    return view('chooseaccounttype');
});


