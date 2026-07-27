<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserInitSeeder extends Seeder
{
    /**
     * Data dummy user + karyawan + role.
     * Sesuaikan field & role name dengan struktur project kamu.
     */
    protected array $data = [
        [
            'nama_karyawan' => 'Admin Sistem',
            'nrk'           => '00001',
            'nip'           => '198501012010011001',
            'jabatan'       => 'Administrator',
            'password'      => 'password',
            'role'          => 'spv', // sesuaikan dengan nama role di RolePermissionSeeder
        ],
        [
            'nama_karyawan' => 'Drs. Ahmad Syarifudin',
            'nrk'           => '00002',
            'nip'           => '196705152005011001',
            'jabatan'       => 'Kepala Sekolah',
            'password'      => 'password',
            'role'          => 'spv', // approval tahap 1
        ],
        [
            'nama_karyawan' => 'Budi Santoso, S.Pd',
            'nrk'           => '00003',
            'nip'           => '198002022008011002',
            'jabatan'       => 'Waka Sarana Prasarana',
            'password'      => 'password',
            'role'          => 'admin', // approval tahap 2
        ],
        [
            'nama_karyawan' => 'Siti Aminah',
            'nrk'           => '00004',
            'nip'           => '199303032015012003',
            'jabatan'       => 'Staff Inventaris',
            'password'      => 'password',
            'role'          => 'staf', // kelola barang, borrow, return
        ],
        [
            'nama_karyawan' => 'Rizky Pratama',
            'nrk'           => '00005',
            'nip'           => '199512122020011005',
            'jabatan'       => 'Guru Produktif TKJ',
            'password'      => 'password',
            'role'          => 'staf', // peminjam barang biasa
        ],
    ];

    public function run(): void
    {
        foreach ($this->data as $item) {
            DB::beginTransaction();
            try {
                $user = User::create([
                    'nama_karyawan' => $item['nama_karyawan'],
                    'email'         => $item['nrk'],
                    'password'      => Hash::make($item['password']),
                ]);

                $user->karyawan()->create([
                    'nama_karyawan' => $item['nama_karyawan'],
                    'nrk'           => $item['nrk'],
                    'nip'           => $item['nip'],
                    'jabatan'       => $item['jabatan'],
                ]);

                $user->syncRoles([$item['role']]);

                DB::commit();

                $this->command->info("User {$item['nama_karyawan']} ({$item['role']}) berhasil dibuat.");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->command->error("Gagal membuat user {$item['nama_karyawan']}: {$e->getMessage()}");
            }
        }
    }
}
