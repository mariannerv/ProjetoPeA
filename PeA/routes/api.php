<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Models\Owner;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\PoliceStationController;
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

Route::post('deactivate', [ApiController::class, "deactivate"]);
Route::post('activate', [ApiController::class, "activate"]);



//APIs PoliceStation
Route::post("registerPost", [PoliceStationController::class, "registerPost"]);

Route::put('updatePost', [PoliceStationController::class, 'updatePost']);

Route::delete('deletePost', [PoliceStationController::class, "deletePost"]);






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

