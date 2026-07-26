<?php

namespace App\Models;

use App\Models\Kategori;
use App\Models\KIB;
use App\Models\Persedian;
use App\Models\SaldoAwalItem;
use App\Models\StokOpnameItem;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
		const SATUAN_OPTIONS = [
        'DUS', 'KG', 'BOTOL', 'BOX', 'BUAH', 'BUNGKUS',
        'UNIT', 'PACK', 'PCS', 'BUKU', 'RIM', 'PAD',
    ];

		public function pembelianItem()
		{
				return $this->hasMany(PembelianItem::class);
		}


    protected $attributes = [
														 'harga_barang' => 0];

    protected $fillable =   [ 'nama_barang','stok_tersedia','description','klasifikasi_kib','kategori_id'];

		public function bolehDiminta(): bool
		{
				return $this->kib->kode_kib !== 'KIB-B';
		}

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

				public function permintaanDetail()
		{
				return $this->hasMany(PermintaanDetail::class);
		}

		public function pengajuanDetail()
		{
				return $this->hasMany(PengajuanDetail::class);
		}

		public function peminjamanDetail()
		{
				return $this->hasMany(PeminjamanDetail::class);
		}

		public function saldoAwalItem()
		{
				return $this->hasMany(SaldoAwalItem::class);
		}

    public function StokOpnameItem() { return $this->hasMany(StokOpnameItem::class); }


}
