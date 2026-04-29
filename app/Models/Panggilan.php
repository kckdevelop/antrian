<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Panggilan extends Model
{
    use HasFactory;

    protected $table = 'panggilans';

    protected $fillable = [
        'unit_id',
        'loket_id',
        'nomor_antrian',
        'status',
        'dipanggil_at',
    ];
    protected $casts = [
    'dipanggil_at' => 'datetime:H:i', // atau 'datetime' saja
];
    /**
     * Relasi ke tabel Unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    /**
     * Relasi ke tabel Loket
     */
    public function loket()
    {
        return $this->belongsTo(Loket::class, 'loket_id');
    }
}
