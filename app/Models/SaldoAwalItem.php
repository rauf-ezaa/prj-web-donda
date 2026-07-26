<?php
namespace App\Models;

use App\Models\Barang;
use App\Models\SaldoAwal;
use Illuminate\Database\Eloquent\Model;

class SaldoAwalItem extends Model
{
		protected $table = 'saldo_awal_items';
    protected $fillable = ['saldo_awal_id', 'barang_id', 'qty'];

    public function saldoAwal()
    {
        return $this->belongsTo(SaldoAwal::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

}
