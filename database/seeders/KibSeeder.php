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
            'kode_kib' => 'KIB-A',
            'klasifikasi' => 'Tanah',
            'deskripsi' => 'Mencatat aset berupa lahan/tanah.'
        ]);

         KIB::create([
            'kode_kib' => 'KIB-B',
            'klasifikasi' => 'Peralatan dan Mesin',
            'deskripsi' => 'Mencata Aset bergerak seperti alat kantor, alat kendaraan, alat studio, dan alat kedokteran.'
        ]);


        KIB::create([
        'kode_kib' => 'KIB-C',
        'klasifikasi' => 'Gedung dan Bangunan',
        'deskripsi' => 'Digunakan untuk mencatat aset berupa gedung dan bangunan yang dimiliki sekolah, seperti ruang kelas, laboratorium, perpustakaan, kantor, aula, dan bangunan pendukung lainnya.'
        ]);


        KIB::create([
        'kode_kib' => 'KIB-D',
        'klasifikasi' => 'Jalan, Irigasi, dan Jaringan',
        'deskripsi' => 'Digunakan untuk mencatat aset berupa infrastruktur yang mendukung kegiatan sekolah, seperti jalan lingkungan sekolah, saluran drainase, jaringan listrik, jaringan air bersih, dan jaringan komunikasi.'
    
        ]);

        KIB::create([
        'kode_kib' => 'KIB-E',
        'klasifikasi' => 'Aset Tetap Lainnya',
        'deskripsi' => 'Digunakan untuk mencatat aset tetap yang tidak termasuk dalam kategori sebelumnya, seperti buku perpustakaan, koleksi museum, barang seni, alat peraga pendidikan, dan aset sejenis lainnya.'
    
        ]);

        KIB::create([
        'kode_kib' => 'KIB-F',
        'klasifikasi' => 'Konstruksi Dalam Pengerjaan',
        'deskripsi' => 'Digunakan untuk mencatat aset yang masih dalam proses pembangunan atau pengerjaan dan belum siap digunakan, seperti pembangunan gedung baru, renovasi besar, atau proyek infrastruktur lainnya.'
        ]);
    }
}
