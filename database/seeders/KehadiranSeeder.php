<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KehadiranSeeder extends Seeder
{
    public function run(): void
    {
        $staffEmails = [
            'dwi.kepegawaian@tu.test',
            'faizz.kepegawaian@tu.test',
            'eko.pramubakti@tu.test',
            'marsis.pramubakti@tu.test',
            'miftahul.pramubakti@tu.test',
            'ike.keuangan@tu.test',
            'aris.persuratan@tu.test',
            'ginabul.persuratan@tu.test',
            'herman.persuratan@tu.test',
            'anggra.perpustakaan@tu.test',
            'bagus.perpustakaan@tu.test',
            'sutrisno.perpustakaan@tu.test',
            'fatkurahman.inventaris@tu.test',
            'imam.inventaris@tu.test',
            'bayu.kesiswaan@tu.test',
            'wikana.kesiswaan@tu.test',
        ];

        $staffUsers = Pengguna::whereIn('email', $staffEmails)
            ->where('aktif', true)
            ->get();

        $statuses  = ['hadir','hadir','hadir','hadir','hadir','terlambat','izin','sakit'];
        $today     = Carbon::today();
        $addresses = [
            'SMA Negeri 2 Jember, Jl. Jawa No.16, Sumbersari, Jember',
            'Halaman Parkir SMA Negeri 2 Jember',
            'Ruang TU SMA Negeri 2 Jember, Jl. Jawa 16',
            'Pos Satpam SMA Negeri 2 Jember',
            'Lapangan Utama SMA Negeri 2 Jember',
        ];

        $totalAttendance = 0;

        foreach ($staffUsers as $staff) {
            for ($i = 29; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                if ($date->isWeekend()) continue;

                $status   = $statuses[array_rand($statuses)];
                $clockIn  = null;
                $clockOut = null;
                $note     = null;
                $addrIn   = null;
                $addrOut  = null;

                switch ($status) {
                    case 'hadir':
                        $clockIn  = sprintf('07:%02d', rand(10, 29));
                        $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30));
                        $addrIn   = $addresses[array_rand($addresses)];
                        $addrOut  = $addresses[array_rand($addresses)];
                        break;
                    case 'terlambat':
                        $clockIn  = sprintf('07:%02d', rand(46, 59));
                        $clockOut = sprintf('%02d:%02d', rand(15, 16), rand(0, 30));
                        $note     = 'Terlambat: ' . collect(['macet di jalan', 'ban bocor', 'antar anak sekolah', 'hujan deras'])->random();
                        $addrIn   = $addresses[array_rand($addresses)];
                        $addrOut  = $addresses[array_rand($addresses)];
                        break;
                    case 'izin':
                        $note = collect(['Urusan keluarga', 'Mengurus dokumen pribadi', 'Keperluan mendadak'])->random();
                        break;
                    case 'sakit':
                        $note = collect(['Demam dan flu', 'Sakit perut', 'Periksa ke dokter', 'Masuk angin'])->random();
                        break;
                }

                Kehadiran::updateOrCreate(
                    ['pengguna_id' => $staff->id, 'tanggal' => $date->format('Y-m-d')],
                    [
                        'jam_masuk'      => $clockIn,
                        'jam_pulang'     => $clockOut,
                        'status'        => $status,
                        'lat_masuk'   => $clockIn ? -8.165908 + (rand(-50, 50) / 100000) : null,
                        'lng_masuk'  => $clockIn ? 113.706649 + (rand(-50, 50) / 100000) : null,
                        'alamat_masuk'    => $addrIn,
                        'lat_pulang'  => $clockOut ? -8.165908 + (rand(-50, 50) / 100000) : null,
                        'lng_pulang' => $clockOut ? 113.706649 + (rand(-50, 50) / 100000) : null,
                        'alamat_pulang'   => $addrOut,
                        'catatan'          => $note,
                    ]
                );

                $totalAttendance++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('=================================================');
        $this->command->info('  KEHADIRAN SEEDER BERHASIL!');
        $this->command->info('=================================================');
        $this->command->info("  ~{$totalAttendance} data absensi (30 hari x {$staffUsers->count()} staff)");
        $this->command->info('  Statuses: hadir, terlambat, izin, sakit');
        $this->command->info('=================================================');
    }
}
