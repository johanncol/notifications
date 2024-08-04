<?php

namespace Johanncol\Notifications\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    use HasFactory;

    protected $table = 'notificaciones_globales';

    protected $fillable = [
        'tipo',
        'titulo',
        'mensaje',
        'ruta',
        'fecha_creacion',
        'importancia',
        'destino',
    ];

    // public function usuarios()
    // {
    //     return $this->belongsToMany(User::class, 'notificaciones_usuarios', 'notificacion_id', 'usuario_id')
    //                 ->withPivot('leida', 'fecha_lectura')
    //                 ->withTimestamps();
    // }


}
