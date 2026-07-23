<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\Permintaan;
use Illuminate\Database\Eloquent\Model;

class PermintaanDetail extends Model
{
    protected $table = 'permintaan_details';

    protected $fillable = [
        'permintaan_id',
        'barang_id',
        'jumlah_diminta',
        'jumlah_disetujui',
        'catatan_item',
    ];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
