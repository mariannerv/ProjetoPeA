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
Route::get('/usersactive', [ApiController::class, 'showactive'])->name('usersactive.store'); 
Route::get('/usersdeactivated.', [ApiController::class, 'showdeactivated'])->name('usersdeactivated.store');
Route::post('/create', [ApiController::class, 'register'])->name('user.register');
Route::delete('/users/{user}', [ApiController::class, 'destroy'])->name("user.destroy");
Route::get('/user{user}/edit', [ApiController::class, 'edit'])->name('user.edit');
Route::put('/user/{user}', [ApiController::class, 'update'])->name('user.update');
Route::get('/users/{user}/confirm-delete', [ApiController::class, 'confirmDelete'])->name('user.confirm-delete');
Route::post('/userdeactive/{user}', [ApiController::class, 'deactivateacount'])->name('user.desactive');
Route::post('/useractive/{user}', [ApiController::class, 'activeacount'])->name('user.useractive');
Route::get('/report', function(){
    return view('profile.users.report');
})->name("user.showrepot");
Route::post('/userreport', [ApiController::class, 'report'])->name('user.userreport');
// Police Routes
Route::get('/polices', [PoliceController::class, 'index'])->name('polices.store');
Route::get('/police/{user}/edit', [PoliceController::class, 'edit'])->name('police.edit');

Route::post('/policedeactive/{user}', [PoliceController::class, 'deactivateacount'])->name('police.desactive');
Route::post('/policeactive/{user}', [PoliceController::class, 'activeacount'])->name('police.useractive');

Route::get('/policesactive', [PoliceController::class, 'showactive'])->name('policesactive.store'); 
Route::get('/policesdeactivated.', [PoliceController::class, 'showdeactivated'])->name('policesdeactivated.store');


Route::get('/police/{user}/confirm-delete', [PoliceController::class, 'confirmDelete'])->name('police.confirm-delete');

Route::delete('/police/{police}', [PoliceController::class, 'destroy'])->name("police.destroy");
Route::put('/police/{police}', [PoliceController::class, 'update'])->name('police.update');
Route::post('/police-create', [PoliceController::class, 'registerPolicia'])->name('police.register');
Route::get('/policesform', [PoliceStationController::class, 'sigla'])->name('policesform.store');
Route::get('/loginpolice', function(){
    return view('auth.policelogin');
});
Route::post('/policelogin' , [PoliceController::class, 'loginPolice'])->name('polices.login');

Route::get('/logoutpolice' ,[PoliceController::class, 'logout'])->name('polices.logout');
// Station routes
Route::get('/stations', [PoliceStationController::class, 'index'])->name('stations.store');
Route::post('/stationcreate', [PoliceStationController::class, 'registerPost'])->name('station.register');
Route::delete('/policestation/{station}', [PoliceStationController::class, 'destroy'])->name("policestation.destroy");
Route::get('/station/{user}/edit', [PoliceStationController::class, 'edit'])->name('station.edit');
Route::put('/station/{station}', [PoliceStationController::class, 'update'])->name('station.update');
Route::get('/testeauth', function(){
    return view('auth.testeauth');
});
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
Route::get('/register-success', function () {
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

Route::view('/police/{police}','profile.users.police')->name('police.profile');

#showprofile
Route::get('/usersadmin/{user}', [ApiController::class, 'showprofile'])->name('useradm.profile');

Route::get('/showreportadmin/{user}', [ApiController::class, 'showreportadmin'])->name('showreport.admin');

Route::post('/reportadmin/{email}', [ApiController::class, 'reportadmin'])->name('reportadmin.admin');


Route::get('/daradmin/{user}', [ApiController::class, 'addadmin'])->name('daradmin.admin');
Route::get('/deladmin/{user}', [ApiController::class, 'deladmin'])->name('deladmin.admin');
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
Route::view('/lost-objects/register-form', 'objects.lost-objects.lost-object-register')->name('lost-objects.register-form');
Route::post('/lost-objects/register', [LostObjectController::class, 'registerLostObject'])->name('lost-objects.register');
Route::get('/lost-objects', [LostObjectController::class, 'getAllLostObjects'])->name('lost-objects.get');
Route::get('/lost-objects/{object}', [LostObjectController::class,'getLostObject'])->name('lost-object.get');
Route::delete('lost-objects/delete/{object}', [LostObjectController::class,'deleteLostObject'])->name('lost-object.delete');
Route::get('/lost-objects/{object}/edit', [LostObjectController::class,'editLostObject'])->name('lost-object.edit');
Route::put('/lost-objects/{object}', [LostObjectController::class,'upadteLostObject'])->name('lost-object.update');

Route::get('/found-objects', [foundObjectController::class, 'getAllFoundObjects'])->name('found-objects.get');
Route::view('/found-objects/register-form', 'objects.foundobjectregister')->name('found-objects.register-form');
Route::post('/lost-objects/register', [foundObjectController::class, 'registerFoundObject'])->name('found-objects.register');
Route::get('/found-objects/{object}', [foundObjectController::class,'getFoundObject'])->name('found-object.get');
Route::delete('found-objects/delete/{object}', [foundObjectController::class,'deleteFoundObject'])->name('found-object.delete');
Route::get('/search',function(){
    return view('objects.objectsearch');
});

// Login and Logout Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('login');
    })->name('user.login-view');
    Route::post('/login', [ApiController::class, 'login'])->name('user.login');
    Route::get('/logout', [ApiController::class, 'logout'])->name('user.logout');
    Route::get('/testeauth', function () {
        return view('auth.testeauth');
    });
});

// Registration Views
Route::prefix('register')->group(function () {
    Route::get('/chooseaccounttype', function () {
        return view('register.chooseaccounttype');
    });
    Route::get('/success', function () {
        return view('register.registerSuccess');
    })->name('register.success');
    Route::get('/stationsform', function () {
        return view('register.stationsform');
    });
    Route::get('/usersform', function () {
        return view('register.usersform');
    });
});

// Object Routes
Route::prefix('objects')->group(function () {
    Route::view('/register-form', 'objects.objectregister')->name('objects.register-form');
    Route::post('/register', [LostObjectController::class, 'registerLostObject'])->name('objects.register');
    Route::get('/lost', [LostObjectController::class, 'getAllLostObjects'])->name('lost-objects.get');
    Route::get('/lost/{object}', [LostObjectController::class, 'getLostObject'])->name('lost-object.get');
    Route::delete('/lost/delete/{object}', [LostObjectController::class, 'deleteLostObject'])->name('lost-object.delete');
    Route::get('/lost/{object}/edit', [LostObjectController::class, 'editLostObject'])->name('lost-object.edit');
    Route::put('/lost/{object}', [LostObjectController::class, 'upadteLostObject'])->name('lost-object.update');
    Route::get('/found', [foundObjectController::class, 'getAllFoundObjects'])->name('found-objects.get');
    Route::get('/found/{object}', [foundObjectController::class, 'getFoundObject'])->name('found-object.get');
    Route::delete('/found/delete/{object}', [foundObjectController::class, 'deleteFoundObject'])->name('found-object.delete');
    Route::get('/search', function () {
        return view('objects.objectsearch');
    });
    
});
Route::get('/statmap', function () {
    return view('objectstatmap');
});
// Auction views/routes
Route::get('/auctions',[AuctionController::class,'viewAllAuctions'])->name('auctions.get');
Route::get('/auctions/{auction}', [AuctionController::class,'viewAuction'])->name('auction.get');

?>