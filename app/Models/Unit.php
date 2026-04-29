<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $table = 'units';
    protected $fillable = [
        'unit',
        'kode_unit',
    ];

    protected $hidden = [
        // bisa ditambahkan jika ada kolom sensitif
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

     public function panggilans()
    {
        return $this->hasMany(Panggilan::class, 'unit_id', 'id');
    }
   
}
