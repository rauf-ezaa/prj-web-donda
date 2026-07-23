<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';
    protected $fillable = ['nama_karyawan','nrk','nip','user_id'];

}
