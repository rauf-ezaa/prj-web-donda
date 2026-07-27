<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==== PERMISSIONS ====
        $permissions = [
            // Pengajuan & Permintaan
            'view-pengajuan',
            'create-pengajuan',
            'view-permintaan',
            'create-permintaan',

            // Approval
            'approve-persediaan',
            'approve-peminjaman',
            'approve-pengembalian',

            // Peminjaman & Pengembalian
            'view-peminjaman',
            'create-peminjaman',
            'view-pengembalian',
            'create-pengembalian',

            // Master data
            'manage-barang',
            'manage-kategori',
            'manage-kib',

            // Pegawai
            'manage-pegawai',

            // Laporan
            'view-laporan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ==== ROLES ====
        $admin = Role::firstOrCreate(['name' => 'spv', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all()); // admin dapat semua akses

        $spv = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $spv->givePermissionTo([
            'view-pengajuan',
            'view-permintaan',
            'approve-persediaan',
            'approve-peminjaman',
            'approve-pengembalian',
            'view-peminjaman',
            'view-pengembalian',
            'view-laporan',
        ]);

        $staf = Role::firstOrCreate(['name' => 'staf', 'guard_name' => 'web']);
        $staf->givePermissionTo([
            'view-pengajuan',
            'create-pengajuan',
            'view-permintaan',
            'create-permintaan',
            'view-peminjaman',
            'create-peminjaman',
            'view-pengembalian',
            'create-pengembalian',
        ]);
    }
}
