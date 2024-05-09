<?php
//----------------------------------------------------------------------- CENAS DO BREEZE ^ -----------------------------------------------------------------------
//use App\Http\Controllers\ProfileController;
//use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';


//----------------------------------------------------------------------- AS NOSSAS CENAS  -----------------------------------------------------------------------


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PoliceStationController;
use App\Http\Controllers\Api\PoliceController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\emailVerificationCodeController;
use App\Models\PoliceStation;
use App\Http\Controllers\Emails\SendMailController;
use App\Http\Controllers\verificationCodeController;
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
        return view('home');
    });
    
//User Routes

// User Routes
Route::get('/users', [ApiController::class, 'index'])->name('users.store');
Route::post('/create', [ApiController::class, 'register'])->name('user.register');
Route::delete('/users/{user}', [ApiController::class, 'destroy'])->name("user.destroy");
Route::get('/user{user}/edit', [ApiController::class, 'edit'])->name('user.edit');
Route::put('/user/{user}', [ApiController::class, 'update'])->name('user.update');
Route::post('/users/{user}/confirm-delete', [ApiController::class, 'confirmDelete'])->name('user.confirm-delete');

// Police Routes
Route::get('/polices', [PoliceController::class, 'index'])->name('polices.store');
Route::get('/police{user}/edit', [PoliceController::class, 'edit'])->name('police.edit');
Route::delete('/police/{police}', [PoliceController::class, 'destroy'])->name("police.destroy");
Route::put('/police/{police}', [PoliceController::class, 'update'])->name('police.update');
Route::post('/Policecreate', [PoliceController::class, 'registerPolice'])->name('police.register');
Route::get('/policesform', [PoliceStationController::class, 'sigla'])->name('policesform.store');

// Station routes
Route::get('/stations', [PoliceStationController::class, 'index'])->name('stations.store');
Route::post('/stationcreat', [PoliceStationController::class, 'registerPost'])->name('station.register');
Route::delete('/policestation/{station}', [PoliceStationController::class, 'destroy'])->name("policestation.destroy");
Route::get('/station{user}/edit', [PoliceStationController::class, 'edit'])->name('station.edit');
Route::put('/station/{station}', [PoliceStationController::class, 'update'])->name('station.update');

Route::get('/usersform', function () {
    return view('usersform');
});

Route::get('/stationsform', function () {
    return view('stationsform');
});

Route::get('/', function () {
    return view('home');
});
    
    Route::get('/login', function(){
        return view('login');
    });

    Route::post('/loginuser' ,[ApiController::class, 'login'])->name('user.login');

    Route::get('/logout' ,[ApiController::class, 'logout'])->name('user.logout');


    Route::get('/userhome', function(){
        return view('userhome');
    });

// // Login routes
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);

//Police Routes
    Route::get('/polices' ,[PoliceController::class ,'index'])->name('polices.store');
    Route::get('/police{user}/edit' ,[PoliceController::class ,'edit' ] )->name('police.edit');
    Route::delete('/police/{police}' , [PoliceController::class , 'destroy'])->name("police.destroy");
    Route::put('/police/{police}' ,[PoliceController::class ,'update'])->name('police.update');
    Route::post('/Policecreate' ,[PoliceController::class ,'registerPolice'])->name('police.register');
    Route::get('/policesform' ,[PoliceStationController::class ,'sigla'])->name('policesform.store');

//Station routs
    
    Route::get('/stations' ,[PoliceStationController::class ,'index'])->name('stations.store');
    Route::post('/stationcreat' ,[PoliceStationController::class ,'registerPost'])->name('station.register');
    Route::delete('/policestation/{station}' , [PoliceStationController::class , 'destroy'])->name("policestation.destroy");
    Route::get('/station{user}/edit' ,[PoliceStationController::class ,'edit' ] )->name('station.edit');
    Route::put('/station/{station}' ,[PoliceStationController::class ,'update'])->name('station.update');
    
    
    
    Route::get('/usersform',function(){
        return view('usersform');
    });



    Route::get('/stationsform',function(){
        return view('stationsform');
    });

    Route::get('/chooseaccounttype',function(){
        return view('chooseaccounttype');
    });

Route::get('/usersform',function(){
    return view('usersform');
});

Route::get('/edit',function(){
    return view('profile.edit');
});

Route::get('/search',function(){
    return view('objectsearch');
});

Route::get('/send-mail', [SendMailController::class, 'sendWelcomeEmail']);

Route::get('send-mail',[EmailController::class, 'sendWelcomeEmail']);

Route::get('/registerSuccess', function () {
    return view('registerSuccess');
})->name('registerSuccess');


Route::get('/verification-form', function () {
    return view('verificaemail');
});

Route::get('/deleteAccount/{user}', function () {
    return view('confirm-deletion');
})->name('deleteAccount');


//caso o timer tenha expirado
Route::post('/generate-new-token/{uuid}', [verificationCodeController::class, 'geraNovoToken'])->name('generate-new-token');




//Cenas pra verificar os emails

Route::get('/verify-email/{uuid}', [verificationCodeController::class, 'verifyEmail'])->name('verify-email');

Route::view('/tokenexpirou/{uuid}', 'tokenexpirou')->name('tokenexpirou');
Route::view('/novoemail/', 'novoemail')->name('novoemail');
Route::view('/verificaemail/', 'verificaemail')->name('verificaemail');
