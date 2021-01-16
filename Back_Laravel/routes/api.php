<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/nuevo-usuario','App\Http\Controllers\UsuarioController@guardar')->name('guardar');
Route::post('/editar-usuario','App\Http\Controllers\UsuarioController@editar')->name('editar');
Route::post('/eliminar-usuario','App\Http\Controllers\UsuarioController@eliminar')->name('eliminar');
Route::get('/servicios','App\Http\Controllers\UsuarioController@servicios')->name('servicios');
Route::get('/usuarios','App\Http\Controllers\UsuarioController@usuarios')->name('usuarios');
Route::post('/login','App\Http\Controllers\UsuarioController@login')->name('login');
Route::post('/ingresos-usuario','App\Http\Controllers\UsuarioController@ingresosByUsuario')->name('ingresos');
