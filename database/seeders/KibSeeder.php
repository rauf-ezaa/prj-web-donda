<?php

namespace Database\Seeders;

use App\Models\KIB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KibSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
				KIB::create([
					'kode_kib' => 'Sarpras',
					'klasifikasi' => 'Sarana dan Prasarana',
					'deskripsi' => 'Sarana dan Prasarana yang menunjang proses pendidikan'
				]);

				KIB::create([
					'kode_kib' => 'KIB-B',
					'klasifikasi' => 'Peralatan dan Mesin',
					'deskripsi' => 'Mencatat Aset bergerak seperti alat kantor, alat kendaraan, alat studio, dan alat kedokteran.'
				]);

					KIB::create([
					'kode_kib' => 'ATK',
					'klasifikasi' => 'Alat Tulis Kantor',
					'deskripsi' => 'Alat yang menunjan proses kegiatan belajar mengajar'
				]);
    }
}
