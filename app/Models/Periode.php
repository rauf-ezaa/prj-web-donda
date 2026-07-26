<?php
// app/Models/Periode.php
namespace App\Models;

use App\Models\SaldoAwal;
use App\Models\StokOpname;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
		protected $table = 'periodes';
    protected $fillable = ['nama', 'semester', 'tahun', 'status', 'dikunci_oleh', 'dikunci_at'];
    protected $casts = ['dikunci_at' => 'datetime'];

    public function stokOpnames() { return $this->hasMany(StokOpname::class); }
    public function saldoAwals() { return $this->hasMany(SaldoAwal::class); }
    public function dikuncOleh() { return $this->belongsTo(User::class, 'dikunci_oleh'); }

    public static function aktif()
    {
        return static::where('status', 'aktif')->latest('id')->first();
    }

    public function isTerkunci(): bool
    {
        return $this->status === 'terkunci';
    }
}
