<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\LostObjectController;
use App\Http\Controllers\Api\foundObjectController;
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
*/

Route::get('/', function () {
        return view('home');
    })->name('home');

// User Routes
Route::get('/users', [ApiController::class, 'index'])->name('users.store');
Route::post('/create', [ApiController::class, 'register'])->name('user.register');
Route::delete('/users/{user}', [ApiController::class, 'destroy'])->name("user.destroy");
Route::get('/user{user}/edit', [ApiController::class, 'edit'])->name('user.edit');
Route::put('/user/{user}', [ApiController::class, 'update'])->name('user.update');
Route::get('/users/{user}/confirm-delete', [ApiController::class, 'confirmDelete'])->name('user.confirm-delete');

// Police Routes
Route::get('/polices', [PoliceController::class, 'index'])->name('polices.store');
Route::get('/police/{user}/edit', [PoliceController::class, 'edit'])->name('police.edit');
Route::delete('/police/{police}', [PoliceController::class, 'destroy'])->name("police.destroy");
Route::put('/police/{police}', [PoliceController::class, 'update'])->name('police.update');
Route::post('/police-create', [PoliceController::class, 'registerPolicia'])->name('police.register');
Route::get('/policesform', [PoliceStationController::class, 'sigla'])->name('policesform.store');

// Station routes
Route::get('/stations', [PoliceStationController::class, 'index'])->name('stations.store');
Route::post('/stationcreate', [PoliceStationController::class, 'registerPost'])->name('station.register');
Route::delete('/policestation/{station}', [PoliceStationController::class, 'destroy'])->name("policestation.destroy");
Route::get('/station/{user}/edit', [PoliceStationController::class, 'edit'])->name('station.edit');
Route::put('/station/{station}', [PoliceStationController::class, 'update'])->name('station.update');

// Login views/routes
Route::get('/login', function(){
    return view('auth.login');
});
Route::post('/loginuser' ,[ApiController::class, 'login'])->name('user.login');
Route::get('/logout' ,[ApiController::class, 'logout'])->name('user.logout');


// Register views
Route::get('/chooseaccounttype',function(){
    return view('register.chooseaccounttype');
});
Route::get('/registerSuccess', function () {
    return view('register.registerSuccess');
})->name('register.success');
Route::get('/stationsform', function () {
    return view('register.stationsform');
});
Route::get('/usersform', function () {
    return view('register.usersform');
});

// Profile views
Route::view('/users/{user}','profile.users.user')->name('user.profile');
/*
Route::get('/{police}', function () {
    return view('');
});
Route::get('/{station}', function () {
    return view('');
});
*/
// Route::get('/delete-account/{user}', function () {objectregister
//     return view('profile.users.confirm-deletion');
// })->name('user.delete.account');

// Object views
Route::view('/objects/register-form', 'objects.objectregister')->name('objects.register-form');
Route::post('/objects/register', [LostObjectController::class, 'registerLostObject'])->name('objects.register');
Route::get('/lost-objects', [LostObjectController::class, 'getAllLostObjects'])->name('lost-objects.get');
Route::get('/lost-objects/{object}', [LostObjectController::class,'getLostObject'])->name('lost-object.get');
Route::delete('lost-objects/delete/{object}', [LostObjectController::class,'deleteLostObject'])->name('lost-object.delete');
Route::get('/lost-objects/{object}/edit', [LostObjectController::class,'editLostObject'])->name('lost-object.edit');
Route::put('/lost-objects/{object}', [LostObjectController::class,'upadteLostObject'])->name('lost-object.update');

Route::get('/found-objects', [foundObjectController::class, 'getAllFoundObjects'])->name('found-objects.get');
Route::get('/found-objects/{object}', [foundObjectController::class,'getFoundObject'])->name('found-object.get');
Route::delete('found-objects/delete/{object}', [foundObjectController::class,'deleteFoundObject'])->name('found-object.delete');
Route::get('/search',function(){
    return view('objects.objectsearch');
});

Route::get('/statmap',function(){
    return view('objectstatmap');
});

// Email routes
Route::get('/send-mail', [SendMailController::class, 'sendWelcomeEmail']);
Route::get('send-mail',[EmailController::class, 'sendWelcomeEmail']);
Route::get('/verification-form', function () {
    return view('mail-template.verificaemail');
});
Route::view('/novoemail', 'mail-template.novoemail')->name('novoemail');
Route::view('/verificaemail', 'mail-template.verificaemail')->name('verificaemail');
Route::get('/verify-email/{uuid}', [verificationCodeController::class, 'verifyEmail'])->name('verify-email');

// Tokens views/routes
Route::post('/generate-new-token/{uuid}', [verificationCodeController::class, 'geraNovoToken'])->name('generate-new-token');
Route::view('/tokenexpirou/{uuid}', 'tokenexpirou')->name('tokenexpirou');

// Auction views/routes
Route::get('/auctions',[AuctionController::class,'viewAllAuctions'])->name('auctions.get');
Route::get('/auctions/{auction}', [AuctionController::class,'viewAuction'])->name('auction.get');
