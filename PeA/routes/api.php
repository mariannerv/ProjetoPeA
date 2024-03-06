<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Models\Owner;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


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

//OWNER
Route::post('/Owner', [OwnerController::class, 'store']);

Route::get('/Owner/{civilId}', [OwnerController::class, 'getUserByCivilId']);