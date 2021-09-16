<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bonificacion extends Model
{
    protected $table = "bonificacion";
    protected $primaryKey = "idBonificacion";
    protected $fillable = [
        'fechaInicio',
        'fechaFin',
        'diasBonificar',
        'estado',
    ];
    public $timestamps = false;
}
