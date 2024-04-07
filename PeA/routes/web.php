<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
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

Route::get('/users' ,[OwnerController::class ,'index'])->name('users.store');

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


