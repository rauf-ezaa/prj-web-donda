<?php
namespace App\Services;

use App\Models\{Peminjaman, Permintaan, Pengajuan, Pembelian, Pengembalian, SaldoAwal, StokOpname, User};
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * STAFF — transaksi miliknya sendiri yang butuh perhatian
     * (ditolak, dibatalkan, atau statusnya baru berubah).
     */
    public function forStaff(int $userId): Collection
    {
        $hasil = collect();

        $hasil = $hasil->merge($this->mapItem(
            Peminjaman::where('requested_by', $userId)
                ->whereIn('status', ['ditolak', 'dipinjam', 'sebagian_dikembalikan', 'selesai'])
                ->latest('updated_at')->take(5)->get(),
            'Peminjaman', 'kode_peminjaman', route: 'peminjaman.show'
        ));

        $hasil = $hasil->merge($this->mapItem(
            Permintaan::where('request_by', $userId)
                ->whereIn('status_permintaan', ['rejected', 'approved'])
                ->latest('updated_at')->take(5)->get(),
            'Permintaan', 'kode_permintaan', statusKey: 'status_permintaan', route: 'permintaan.show'
        ));

        $hasil = $hasil->merge($this->mapItem(
            Pengajuan::where('requested_by', $userId)
                ->whereIn('status', ['ditolak', 'selesai'])
                ->latest('updated_at')->take(5)->get(),
            'Pengajuan', 'kode_pengajuan', route: 'pengajuan.show'
        ));

        return $hasil->sortByDesc('waktu')->values()->take(8);
    }

    /**
     * ADMIN — transaksi yang menunggu verifikasi TAHAP ADMIN.
     */
    public function forAdmin(): Collection
    {
        $hasil = collect();

        $hasil = $hasil->merge($this->mapItem(
            Permintaan::where('status_permintaan', 'pending')->latest()->take(5)->get(),
            'Permintaan', 'kode_permintaan', statusKey: 'status_permintaan', route: 'admin.permintaan.show'
        ));

        $hasil = $hasil->merge($this->mapItem(
            Pengembalian::where('status', 'menunggu_verifikasi_admin')->latest()->take(5)->get(),
            'Pengembalian', 'id', route: 'admin.pengembalian.show'
        ));

        return $hasil->sortByDesc('waktu')->values()->take(8);
    }

    /**
     * SPV — transaksi yang menunggu verifikasi TAHAP SPV (final).
     */
    public function forSpv(): Collection
    {
        $hasil = collect();

        $hasil = $hasil->merge($this->mapItem(
            Peminjaman::where('status', 'menunggu_spv')->latest()->take(5)->get(),
            'Peminjaman', 'kode_peminjaman', route: 'spv.peminjaman.show'
        ));

        $hasil = $hasil->merge($this->mapItem(
            Permintaan::where('status_permintaan', 'menunggu_spv')->latest()->take(5)->get(),
            'Permintaan', 'kode_permintaan', statusKey: 'status_permintaan', route: 'spv.permintaan.show'
        ));

        $hasil = $hasil->merge($this->mapItem(
            Pengembalian::where('status', 'menunggu_verifikasi_spv')->latest()->take(5)->get(),
            'Pengembalian', 'id', route: 'spv.pengembalian.show'
        ));

        $hasil = $hasil->merge($this->mapItem(
            Pembelian::where('status', 'menunggu_verifikasi_spv')->latest()->take(5)->get(),
            'Pembelian', 'no_transaksi', route: 'spv.pembelian.show'
        ));

        $hasil = $hasil->merge($this->mapItem(
            SaldoAwal::where('status', 'menunggu_verifikasi_spv')->latest()->take(5)->get(),
            'Saldo Awal', 'no_transaksi', route: 'spv.saldo-awal.show'
        ));

        $hasil = $hasil->merge($this->mapItem(
            StokOpname::where('status', 'menunggu_verifikasi_spv')->latest()->take(5)->get(),
            'Stok Opname', 'no_bast', route: 'spv.stok-opname.show'
        ));

        return $hasil->sortByDesc('waktu')->values()->take(8);
    }

    /**
     * Router utama — panggil ini dari header, otomatis pilih sesuai role user.
     */
    public function forUser(User $user): Collection
    {
        if ($user->hasRole('spv')) return $this->forSpv();
        if ($user->hasRole('admin')) return $this->forAdmin();
        return $this->forStaff($user->id);
    }

    protected function mapItem($rows, string $label, string $kolomKode, string $statusKey = 'status', string $route = ''): Collection
    {
        return collect($rows)->map(function ($row) use ($label, $kolomKode, $statusKey, $route) {
            return [
                'label'   => $label,
                'kode'    => $row->{$kolomKode} ?? "#{$row->id}",
                'status'  => str_replace('_', ' ', ucfirst($row->{$statusKey} ?? '-')),
                'waktu'   => $row->updated_at,
                'link'    => $route ? route($route, $row->id) : '#',
            ];
        });
    }
}
