<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruta',
        'producto_id'
    ];

    //relacion uno a muchos inversa
    public function producto(){
        return $this->belongsTo(Producto::class);
    }
}
