<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\Pengajuan;
use Illuminate\Database\Eloquent\Model;

class PengajuanDetail extends Model
{
    protected $table = 'pengajuan_detail';

    protected $fillable = [
        'pengajuan_id',
				'barang_id',           // nullable sekarang
				'nama_barang_diajukan', // field utama, freetext
				'jumlah_diajukan',
				'catatan_item',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
