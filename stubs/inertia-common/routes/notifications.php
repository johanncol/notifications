<?php

use Illuminate\Support\Facades\Route;
use JohannDesarrollador\Notifications\Http\Controllers\NotificacionController;

Route::middleware('auth')->group(function () {

    
    Route::post('/notificaciones', [NotificacionController::class, 'crearNotificacion']);
    Route::post('/notificaciones/{usuario_id}/{notificacion_id}/leida', [NotificacionController::class, 'marcarComoLeida']);
    Route::get('/notificaciones/{usuario_id}', [NotificacionController::class, 'obtenerMisNotificaciones']);


});
