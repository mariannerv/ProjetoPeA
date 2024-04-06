<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Models\Owner;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PoliceStationController;
use App\Http\Controllers\Api\PoliceController;
use App\Http\Controllers\Api\AuctionController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\foundObjectController;
use App\Http\Controllers\Api\LostObjectController;
use App\Http\Controllers\Api\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//APIs User

Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);


Route::group([
    "middleware" => ["auth:sanctum"]
], function(){
    Route::get("profile", [ApiController::class, "profile"]);
    Route::get("logout", [ApiController::class, "logout"]);
    Route::delete('delete', [ApiController::class, "delete"]);
    Route::put('update', [ApiController::class, 'update']);
});

Route::get("lostObjects", [ApiController::class, "lostObjects"]);
Route::get("myBids", [ApiController::class, "myBids"]);
Route::post('deactivate', [ApiController::class, "deactivate"]);
Route::post('activate', [ApiController::class, "activate"]);


//APIs Policia

Route::post("registerPolice", [PoliceController::class, "registerPolice"]);
Route::post("loginPolice", [PoliceController::class, "loginPolice"]);

Route::group([
    "middleware" => ["auth:sanctum"]
], function(){
    Route::get("profilePolice", [PoliceController::class, "profilePolice"]);
    Route::get("logoutPolice", [PoliceController::class, "logoutPolice"]);
    Route::delete('deletePolice', [PoliceController::class, "deletePolice"]);
    Route::put('updatePolice', [PoliceController::class, 'updatePolice']);
});


Route::post('deactivatePolice', [PoliceController::class, "deactivatePolice"]);
Route::post('activatePolice', [PoliceController::class, "activatePolice"]);



//API foundObject

Route::post("registerFoundObject", [foundObjectController::class, "registerFoundObject"]);
Route::get("viewFoundObject", [foundObjectController::class, "viewFoundObject"]);
Route::put("updateFoundObject", [foundObjectController::class, "updateFoundObject"]);
Route::delete('deleteFoundObject', [foundObjectController::class, "deleteFoundObject"]);

//APIs PoliceStation
Route::post("registerPost", [PoliceStationController::class, "registerPost"]);

Route::put('updatePost', [PoliceStationController::class, 'updatePost']);

Route::delete('deletePost', [PoliceStationController::class, "deletePost"]);

Route::get('viewPost', [PoliceStationController::class, "viewPost"]);



//API do Auction

Route::post("createAuction", [AuctionController::class, "createAuction"]);
Route::get("viewAuction", [AuctionController::class, "viewAuction"]);
Route::put("editAuction", [AuctionController::class, "editAuction"]);
Route::delete("deleteAuction", [AuctionController::class, "deleteAuction"]);
Route::get("viewAllAuctions", [AuctionController::class, "viewAllAuctions"]);

//API das Bids

Route::post("placeBid", [BidController::class, "placeBid"]);


//Api dos LostObjects

Route::post("registerLostObject", [LostObjectController::class, "registerLostObject"]);
Route::put("updateLostObject", [LostObjectController::class, "updateLostObject"]);
Route::delete("deleteLostObject", [LostObjectController::class, "deleteLostObject"]);
Route::post("crossCheck", [LostObjectController::class, "crossCheck"]);
Route::get("getLostObject", [LostObjectController::class, "getLostObject"]);






//Para testar se a conexão ao mongo está a funcionar

Route::get('/test_mongodb/', function (Illuminate\Http\Request $request) {

    $connection = DB::connection('mongodb');
    $msg = 'MongoDB is accessible!';
    try {
        $connection->command(['ping' => 1]);
        $dbName = $connection->getDatabaseName();
        $uri = config('database.connections.mongodb.dsn');
        $dbName = config('database.connections.mongodb.database');
    
        
    } catch (\Exception $e) {
        $msg = 'MongoDB is not accessible. Error: ' . $e->getMessage();
    }
    return ['uri' => $uri, 'dbName' => $dbName];
});






//Teste para mandar email de para confirmar o email, tem de se ver um serviço de emails tipo Mailtrip ou Mailtrap

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');













//OWNER
Route::post('/Owner', [OwnerController::class, 'store']);

Route::get('/Owner/{civilId}', [OwnerController::class, 'getUserByCivilId']);

//Route::post('login', [AuthController::class, 'login']);

