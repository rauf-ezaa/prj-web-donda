<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;

class Persedian extends Model
{
    protected $table = 'persedians';
		protected $fillable = [
			'barang_id',
			'asal_dana',
			'qty',
			'harga_satuan_unit',
			'harga_total',
			'tanggal_masuk',
			'approval_status',
			'catatan__approval'
		];

		public function barang()
		{
			return $this->belongsTo(Barang::class,'barang_id');
		}
}
