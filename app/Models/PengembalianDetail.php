<?php

namespace App\Models;

use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use Illuminate\Database\Eloquent\Model;

class PengembalianDetail extends Model
{

   protected $fillable = ['pengembalian_id', 'peminjaman_item_id', 'qty_baik', 'qty_rusak', 'qty_hilang'];
    public function pengembalian() { return $this->belongsTo(Pengembalian::class); }
    public function peminjamanDetail() { return $this->belongsTo(PeminjamanDetail::class); }
}
