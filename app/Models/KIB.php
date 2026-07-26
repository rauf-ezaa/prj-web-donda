<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;

class KIB extends Model
{
    protected $table = 'kib';

    protected $fillable = [
        'kode_kib',
        'klasifikasi',
        'deskripsi',
    ];

    public function barang(){
        return $this->hasMany(Barang::class,'klasifikasi_kib');
    }
}
