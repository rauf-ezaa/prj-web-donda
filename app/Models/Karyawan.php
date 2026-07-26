<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';

    protected $fillable = [
        'user_id',
        'nama_karyawan',
        'nrk',
        'nip',
        'jabatan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'users_id');
    }
}
