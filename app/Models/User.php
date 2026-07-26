<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

		public function karyawan()
{
    return $this->hasOne(Karyawan::class, 'users_id');
}

public function dashboardRoute(): string
{
    return match(true) {
        $this->hasRole('spv') => 'dashboard.spv',
        $this->hasRole('admin') => 'dashboard.staf',
        $this->hasRole('staf') => 'dashboard.pengguna',
        default => 'login',
    };
}

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_karyawan',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

				public function permintaan()
		{
				return $this->hasMany(Permintaan::class, 'request_by');
		}

		public function pengajuan()
		{
				return $this->hasMany(Pengajuan::class, 'requested_by');
		}

		public function peminjaman()
		{
				return $this->hasMany(Peminjaman::class, 'requested_by');
		}

		public function persediaan()
		{
				return $this->hasMany(Persedian::class, 'requested_by'); // sesuaikan kalau kolomnya beda
		}
}
