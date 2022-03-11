<?php

use App\Http\Controllers\novelasController;
use App\Models\Capitulo;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', [novelasController::class, 'index']);

Route::get('/novelas', [novelasController::class, 'probando']);

Route::post('/novelas/buscar', [novelasController::class, 'buscador']);

Route::post('/novelas/create', [novelasController::class, 'store']);

Route::get('/enlistar', function () {
    return view('reproductor', [
        'capitulos' => Capitulo::where('id_Novelas', '=', request('q'))->orderBy('id', 'desc')
            ->get()
    ]);
});

Route::post('/novelas/capitulo', [novelasController::class, 'capitulo']);

Route::post('/novelas/marcado', [novelasController::class, 'marcado']);

Route::post('/novelas/subir', [novelasController::class, 'subirCapitulo']);
