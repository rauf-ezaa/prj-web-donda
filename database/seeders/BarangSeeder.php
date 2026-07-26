<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Barang::create([
				// 	'nama_barang' => 'Paku Payung',
				// 	'stok_tersedia' => '10',
				// 	'satuan' => 'DUS',
				// 	'description' => 'Uk: Standard',
				// 	'klasifikasi_kib' => 1, //soalnya sarana prasarana

				// ]);

				// Barang::create([
				// 	'nama_barang' => 'Paku',
				// 	'stok_tersedia' => '10',
				// 	'satuan' => 'KG',
				// 	'description' => 'Payung, 10 Cm',
				// 	'klasifikasi_kib' => 1, //soalnya sarana prasarana

				// ]);

				// Barang::create([
				// 	'nama_barang' => 'Aseptic Gel 500 ml+Dispenser',
				// 	'stok_tersedia' => '10',
				// 	'satuan' => 'BOTOL',
				// 	'description' => 'Jenis : Cairan Antiseptik; Tipe : Hand Hygiene Alcohol Gel; 500 ml',
				// 	'klasifikasi_kib' => 1, //soalnya sarana prasarana

				// ]);

				// Barang::create([
				// 	'nama_barang' => 'Alkohol swab',
				// 	'stok_tersedia' => '10',
				// 	'satuan' => 'BOX',
				// 	'description' => '100 pcs /box',
				// 	'klasifikasi_kib' => 1, //soalnya sarana prasarana




				Barang::create([
					'nama_barang' => 'KABEL VGA',
					'stok_tersedia' => '10',
					'satuan' => 'UNIT',
					'description' => 'Â kabel VGA 10 mtr',
					'klasifikasi_kib' => 2, //soalnya sarana prasarana
				]);

					Barang::create([
					'nama_barang' => 'Printer EPSON L5290',
					'stok_tersedia' => '10',
					'satuan' => 'BUAH',
					'description' => 'Printer multifungsi (Print, Scan, Copy, Fax, WiFi, ADF), Kecepatan: mencapai 33 ppm, Resolusi: 5760 × 1440 dpi',
					'klasifikasi_kib' => 2, //soalnya sarana prasarana

				]);

						Barang::create([
					'nama_barang' => '	ASUS Vivobook 14',
					'stok_tersedia' => '10',
					'satuan' => 'BUah',
					'description' => 'Layar: 14 inci, resolusi Full HD (1920 x 1080), panel IPS,  Rasio 16:9. Prosesor: Intel i7-1355U. Grafis: Intel UHD/Iris Xe Graphics . RAM: 16GB DDR4 (dengan opsi upgrade). Penyimpanan:512GB M.2 NVMe PCIe 4.0 SSD. Konektivitas: Wi-Fi 6E, Bluetooth 5.3, port USB-C, USB-A, HDMI, dan jack audio. Fitur Keamanan: Sensor sidik jari. Sistem Operasi: Windows 11 Home',
					'klasifikasi_kib' => 2, //soalnya sarana prasarana


				]);
    }
}
