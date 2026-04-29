<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    use HasFactory;

    protected $table = 'lokets';

    protected $fillable = [
        'nama_loket',
        'unit_id',
        'status',
    ];

   
    protected $hidden = [
        // bisa ditambahkan jika ada kolom sensitif
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function unit()
        {
            return $this->belongsTo(Unit::class);
        }


}
