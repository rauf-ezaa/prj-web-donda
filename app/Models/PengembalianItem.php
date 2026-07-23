<?php

namespace App\Models;

use App\Models\PeminjamanItem;
use App\Models\Pengembalian;
use Illuminate\Database\Eloquent\Model;

class PengembalianItem extends Model
{

		protected $table = 'pengembalian_items';
    protected $fillable = ['pengembalian_id', 'peminjaman_item_id',
    'qty_baik', 'qty_rusak_ringan', 'qty_rusak_berat',
    'qty_hilang', 'qty_habis_terpakai'];

    public function pengembalian() { return $this->belongsTo(Pengembalian::class); }
    public function peminjamanItem() { return $this->belongsTo(PeminjamanItem::class); }
}
