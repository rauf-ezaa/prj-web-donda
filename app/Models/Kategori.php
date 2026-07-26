<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\KIB;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['nama_kategori','jenis_barang','kode_kib'];

    public function kib()
    {
        return $this->belongsTo(KIB::class,'kode_kib');
    }

		public function barang()
		{
			return $this->hasMany(Barang::class);
		}
}
