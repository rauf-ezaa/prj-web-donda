<?php

namespace App\Models;

use App\Models\Kategori;
use App\Models\KIB;
use App\Models\Persedian;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
		protected $attributes = ['stok_tersedia' => 0];
    protected $fillable =   [ 'nama_barang','harga_barang','description','klasifikasi_kib','kategori_id'];

    public function kib(){
        return $this->belongsTo(KIB::class,'klasifikasi_kib');
    }

		public function kategori(){
			return $this->belongsTo(Kategori::class,'kategori_id');
		}

		public function persediaan()
		{
			return $this->hasMany(Persedian::class);
		}

    // public
}
