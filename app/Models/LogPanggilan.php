<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPanggilan extends Model
{
    protected $table = 'log_panggilans';
    protected $fillable = [
        'nomor_antrian',
        'unit_id',
        'loket_id',
        'status',
        'dipanggil_at',
        'diproses_at',
    ];

    protected $casts = [
        'dipanggil_at' => 'datetime',
        'diproses_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function loket()
    {
        return $this->belongsTo(Loket::class);
    }
}
