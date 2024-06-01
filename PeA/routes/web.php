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
Route::prefix('users')->group(function () {
    Route::get('/', [ApiController::class, 'index'])->name('users.store');
    Route::get('/active', [ApiController::class, 'showactive'])->name('usersactive.store');
    Route::get('/deactivated', [ApiController::class, 'showdeactivated'])->name('usersdeactivated.store');
    Route::post('/create', [ApiController::class, 'register'])->name('user.register');
    Route::delete('/{user}', [ApiController::class, 'destroy'])->name("user.destroy");
    Route::get('{user}/edit', [ApiController::class, 'edit'])->name('user.edit');
    Route::put('/{user}', [ApiController::class, 'update'])->name('user.update');
    Route::get('/{user}/confirm-delete', [ApiController::class, 'confirmDelete'])->name('user.confirm-delete');
    Route::post('/deactivate/{user}', [ApiController::class, 'deactivateacount'])->name('user.desactive');
    Route::post('/activate/{user}', [ApiController::class, 'activeacount'])->name('user.useractive');
    Route::get('/report', function () {
        return view('profile.users.report');
    })->name("user.showrepot");
    Route::post('/report', [ApiController::class, 'report'])->name('user.userreport');
    Route::view('/{user}','profile.users.user')->name('user.profile');
});

// Police Routes
Route::prefix('polices')->group(function () {
    Route::get('/', [PoliceController::class, 'index'])->name('polices.store');
    Route::get('/{user}/edit', [PoliceController::class, 'edit'])->name('police.edit');
    Route::delete('/{police}', [PoliceController::class, 'destroy'])->name("police.destroy");
    Route::put('/{police}', [PoliceController::class, 'update'])->name('police.update');
    Route::post('/create', [PoliceController::class, 'registerPolicia'])->name('police.register');
    Route::post('/deactivate/{user}', [PoliceController::class, 'deactivateacount'])->name('police.desactive');
    Route::post('/activate/{user}', [PoliceController::class, 'activeacount'])->name('police.useractive');
    Route::get('/active', [PoliceController::class, 'showactive'])->name('policesactive.store'); 
    Route::get('/deactivated', [PoliceController::class, 'showdeactivated'])->name('policesdeactivated.store');
    Route::get('/form', [PoliceStationController::class, 'sigla'])->name('policesform.store');
    Route::get('/login', function () {
        return view('auth.policelogin');
    })->name('polices.login-view');
    Route::post('/login', [PoliceController::class, 'loginPolice'])->name('polices.login');
    Route::get('/logout', [PoliceController::class, 'logout'])->name('polices.logout');
});

// Station Routes
Route::prefix('stations')->group(function () {
    Route::get('/', [PoliceStationController::class, 'index'])->name('stations.store');
    Route::post('/create', [PoliceStationController::class, 'registerPost'])->name('station.register');
    Route::delete('/{station}', [PoliceStationController::class, 'destroy'])->name("policestation.destroy");
    Route::get('/{user}/edit', [PoliceStationController::class, 'edit'])->name('station.edit');
    Route::put('/{station}', [PoliceStationController::class, 'update'])->name('station.update');
    Route::get('/form', function () {
        return view('register.stationsform');
    });
});

// Login and Logout Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
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
    Route::get('/statmap', function () {
        return view('objectstatmap');
    });
});

// Email Routes
Route::prefix('emails')->group(function () {
    Route::get('/send', [SendMailController::class, 'sendWelcomeEmail']);
    Route::get('/send', [EmailController::class, 'sendWelcomeEmail']);
    Route::get('/verification-form', function () {
        return view('mail-template.verificaemail');
    });
    Route::view('/novoemail', 'mail-template.novoemail')->name('novoemail');
    Route::view('/verificaemail', 'mail-template.verificaemail')->name('verificaemail');
    Route::get('/verify/{uuid}', [verificationCodeController::class, 'verifyEmail'])->name('verify-email');
});

// Token Routes
Route::prefix('tokens')->group(function () {
    Route::post('/generate-new/{uuid}', [verificationCodeController::class, 'geraNovoToken'])->name('generate-new-token');
    Route::view('/expired/{uuid}', 'tokenexpirou')->name('tokenexpirou');
});

// Auction Routes
Route::prefix('auctions')->group(function () {
    Route::get('/', [AuctionController::class, 'viewAllAuctions'])->name('auctions.get');
    Route::get('/{auction}', [AuctionController::class, 'viewAuction'])->name('auction.get');
});
