<?php

namespace App\Models;

use App\Models\KIB;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    protected $fillable =   [ 'nama_barang','harga_barang','stok_tersedia','description','klasifikasi_kib','kategori_id'];

    public function kib(){
        return $this->belongsTo(KIB::class,'klasifikasi_kib');
    }

    // public
}
