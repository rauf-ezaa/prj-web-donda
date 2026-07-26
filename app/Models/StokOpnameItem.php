<?php
// app/Models/StokOpnameItem.php
namespace App\Models;

use App\Models\Barang;
use App\Models\StokOpname;
use Illuminate\Database\Eloquent\Model;

class StokOpnameItem extends Model
{
		protected $table = 'stok_opname_items';
    protected $fillable = ['stok_opname_id', 'barang_id', 'stok_sistem', 'stok_fisik', 'selisih', 'keterangan'];

    public function stokOpname() { return $this->belongsTo(StokOpname::class); }
    public function barang() { return $this->belongsTo(Barang::class); }
}
