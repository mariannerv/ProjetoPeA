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
    
//User Routes

    Route::get('/users' ,[ApiController::class ,'index'])->name('users.store');
    Route::post('/create' ,[ApiController::class ,'register'])->name('user.register');
    Route::delete('/users/{user}' , [ApiController::class , 'destroy'])->name("user.destroy");
    Route::get('/user{user}/edit' ,[ApiController::class ,'edit' ] )->name('user.edit');
    Route::put('/user/{user}' ,[ApiController::class ,'update'])->name('user.update');


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

    Route::get('/',function(){
        return view('chooseaccounttype');
    });

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


