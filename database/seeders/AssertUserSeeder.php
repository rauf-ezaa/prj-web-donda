<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AssertUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

protected array $data = [
    [
        'nama_karyawan' => 'Tarsono, M.Si.',
        'nrk'           => '140121',
        'nip'           => '',
        'jabatan'       => 'Kepala Sekolah',
        'password'      => 'password',
        'role'          => 'spv',
    ],
    [
        'nama_karyawan' => 'Dra. Menuk Resti A., M.Pd.',
        'nrk'           => '173037',
        'nip'           => '',
        'jabatan'       => 'Kasatpel',
        'password'      => 'password',
        'role'          => 'spv',
    ],
    [
        'nama_karyawan' => 'Lina Sabila, S.E',
        'nrk'           => '212119',
        'nip'           => '',
        'jabatan'       => 'Staff',
        'password'      => 'password',
        'role'          => 'admin',
    ],
    [
        'nama_karyawan' => 'Donda Banjarnahor, A.Md.S.I.Ak',
        'nrk'           => '222019',
        'nip'           => '',
        'jabatan'       => 'Staff',
        'password'      => 'password',
        'role'          => 'admin',
    ],
    [
        'nama_karyawan' => 'Maryani, S.Pd.',
        'nrk'           => '212051',
        'nip'           => '',
        'jabatan'       => 'Staff',
        'password'      => 'password',
        'role'          => 'staf',
    ],
    [
        'nama_karyawan' => 'Idaman Hosea D., S.Pd',
        'nrk'           => '218539',
        'nip'           => '',
        'jabatan'       => 'Guru',
        'password'      => 'password',
        'role'          => 'staf',
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
