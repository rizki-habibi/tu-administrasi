<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AttendanceSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | 1. ADMIN ACCOUNT (Kepala Tata Usaha)
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'admin@tu.test'],
            [
                'nama'          => 'Drs. Bambang Supriyanto, M.Pd.',
                'nip'           => '196805151992031005',
                'password'      => Hash::make('password'),
                'peran'          => 'admin',
                'jabatan'      => 'Kepala Tata Usaha',
                'telepon'         => '081234567890',
                'alamat'       => 'Jl. Mastrip No. 45, Kel. Sumbersari, Kec. Sumbersari, Jember',
                'aktif'     => true,
                'tanggal_lahir' => '1968-05-15',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. KEPALA SEKOLAH
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'kepsek@tu.test'],
            [
                'nama'          => 'Dr. H. Sugianto, M.Pd.',
                'nip'           => '196701011991031001',
                'password'      => Hash::make('password'),
                'peran'          => 'kepala_sekolah',
                'jabatan'      => 'Kepala Sekolah',
                'telepon'         => '081234567800',
                'alamat'       => 'Jl. Kaliurang No. 10, Jember',
                'aktif'     => true,
                'tanggal_lahir' => '1967-01-01',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 3. STAFF ACCOUNTS — Berdasarkan data IKI Pelaksana
        |--------------------------------------------------------------------------
        | Roles:
        | 1. kepegawaian         = IKI 1 KEPEGAWAIAN
        | 2. pramu_bakti         = IKI 2 PRAMU BAKTI
        | 3. keuangan            = IKI 3 KEUANGAN
        | 4. persuratan          = IKI 4 PERSURATAN
        | 5. perpustakaan        = IKI 5 PERPUSTAKAAN
        | 6. inventaris          = IKI 6 INVENTARIS/SARPRAS
        | 7. kesiswaan_kurikulum = IKI 7 KESISWAAN/KURIKULUM
        |
        | 2 users get today's month-day as birthday for testing birthday feature.
        */

        // Pre-generate birthdays: index 0 and 8 get today's month-day
        $todayMonthDay = $today->format('m-d');

        $staffData = [
            // === IKI 1: KEPEGAWAIAN ===
            [
                'nama'           => 'Dwi Kriswahyudi',
                'email'          => 'dwi.kepegawaian@tu.test',
                'peran'           => 'kepegawaian',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '1 KEPEGAWAIAN',
                'kode_depan'     => '14344',
                'telepon'          => '081298765001',
                'alamat'        => 'Jl. Kalimantan No. 12, Jember',
                'tanggal_lahir'  => '1985-' . $todayMonthDay, // birthday today for testing
            ],
            [
                'nama'           => 'Faizz Moch. Nur Adam',
                'email'          => 'faizz.kepegawaian@tu.test',
                'peran'           => 'kepegawaian',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '1 KEPEGAWAIAN',
                'kode_depan'     => '14344',
                'telepon'          => '081298765002',
                'alamat'        => 'Jl. Sumatera No. 8, Jember',
                'tanggal_lahir'  => '1992-07-14',
            ],

            // === IKI 2: PRAMU BAKTI ===
            [
                'nama'           => 'Eko Bagus Febrianto',
                'email'          => 'eko.pramubakti@tu.test',
                'peran'           => 'pramu_bakti',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '2 PRAMU BAKTI',
                'kode_depan'     => '23304',
                'telepon'          => '081298765003',
                'alamat'        => 'Jl. Jawa No. 25, Jember',
                'tanggal_lahir'  => '1990-02-15',
            ],
            [
                'nama'           => 'Marsis',
                'email'          => 'marsis.pramubakti@tu.test',
                'peran'           => 'pramu_bakti',
                'jabatan'       => 'Pengelola Umum Operasional',
                'iki_pelaksana'  => '2 PRAMU BAKTI',
                'kode_depan'     => '23304',
                'telepon'          => '081298765004',
                'alamat'        => 'Jl. Sulawesi No. 3, Jember',
                'tanggal_lahir'  => '1978-11-20',
            ],
            [
                'nama'           => 'Miftahul Ulum',
                'email'          => 'miftahul.pramubakti@tu.test',
                'peran'           => 'pramu_bakti',
                'jabatan'       => 'Pengelola Umum Operasional',
                'iki_pelaksana'  => '2 PRAMU BAKTI',
                'kode_depan'     => '23304',
                'telepon'          => '081298765005',
                'alamat'        => 'Jl. Borneo No. 17, Jember',
                'tanggal_lahir'  => '1988-06-03',
            ],

            // === IKI 3: KEUANGAN ===
            [
                'nama'           => 'Ike Wijayanti',
                'email'          => 'ike.keuangan@tu.test',
                'peran'           => 'keuangan',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '3 KEUANGAN',
                'kode_depan'     => '14342',
                'telepon'          => '081298765006',
                'alamat'        => 'Jl. Papua No. 9, Jember',
                'tanggal_lahir'  => '1986-09-25',
            ],

            // === IKI 4: PERSURATAN ===
            [
                'nama'           => 'Aris Sugito',
                'email'          => 'aris.persuratan@tu.test',
                'peran'           => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '4 PERSURATAN',
                'kode_depan'     => '14345',
                'telepon'          => '081298765007',
                'alamat'        => 'Jl. Bali No. 22, Jember',
                'tanggal_lahir'  => '1983-12-10',
            ],
            [
                'nama'           => 'Ginabul Rahayu',
                'email'          => 'ginabul.persuratan@tu.test',
                'peran'           => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '4 PERSURATAN',
                'kode_depan'     => '14345',
                'telepon'          => '081298765008',
                'alamat'        => 'Jl. Flores No. 5, Jember',
                'tanggal_lahir'  => '1991-04-18',
            ],
            [
                'nama'           => 'Herman Budi Santoso',
                'email'          => 'herman.persuratan@tu.test',
                'peran'           => 'persuratan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '4 PERSURATAN',
                'kode_depan'     => '14345',
                'telepon'          => '081298765009',
                'alamat'        => 'Jl. Lombok No. 14, Jember',
                'tanggal_lahir'  => '1980-' . $todayMonthDay, // birthday today for testing
            ],

            // === IKI 5: PERPUSTAKAAN ===
            [
                'nama'           => 'Anggra Okta Wijaya',
                'email'          => 'anggra.perpustakaan@tu.test',
                'peran'           => 'perpustakaan',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '5 PERPUSTAKAAN',
                'kode_depan'     => '19463',
                'telepon'          => '081298765010',
                'alamat'        => 'Jl. Timor No. 7, Jember',
                'tanggal_lahir'  => '1993-10-08',
            ],
            [
                'nama'           => 'Bagus Pribadi',
                'email'          => 'bagus.perpustakaan@tu.test',
                'peran'           => 'perpustakaan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '5 PERPUSTAKAAN',
                'kode_depan'     => '19463',
                'telepon'          => '081298765011',
                'alamat'        => 'Jl. Madura No. 33, Jember',
                'tanggal_lahir'  => '1987-01-27',
            ],
            [
                'nama'           => 'Moh. Sutrisno',
                'email'          => 'sutrisno.perpustakaan@tu.test',
                'peran'           => 'perpustakaan',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '5 PERPUSTAKAAN',
                'kode_depan'     => '19463',
                'telepon'          => '081298765012',
                'alamat'        => 'Jl. Nusa Tenggara No. 11, Jember',
                'tanggal_lahir'  => '1979-08-30',
            ],

            // === IKI 6: INVENTARIS/SARPRAS ===
            [
                'nama'           => 'Fatkurahman',
                'email'          => 'fatkurahman.inventaris@tu.test',
                'peran'           => 'inventaris',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '6 INVENTARIS/SARPRAS',
                'kode_depan'     => '14343',
                'telepon'          => '081298765013',
                'alamat'        => 'Jl. Kartini No. 20, Jember',
                'tanggal_lahir'  => '1984-03-22',
            ],
            [
                'nama'           => 'Imam Basori',
                'email'          => 'imam.inventaris@tu.test',
                'peran'           => 'inventaris',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '6 INVENTARIS/SARPRAS',
                'kode_depan'     => '14343',
                'telepon'          => '081298765014',
                'alamat'        => 'Jl. Diponegoro No. 15, Jember',
                'tanggal_lahir'  => '1989-05-11',
            ],

            // === IKI 7: KESISWAAN/KURIKULUM ===
            [
                'nama'           => 'Bayu Kurniawan',
                'email'          => 'bayu.kesiswaan@tu.test',
                'peran'           => 'kesiswaan_kurikulum',
                'jabatan'       => 'Penata Layanan Operasional',
                'iki_pelaksana'  => '7 KESISWAAN/KURIKULUM',
                'kode_depan'     => '23305',
                'telepon'          => '081298765015',
                'alamat'        => 'Jl. Gajah Mada No. 10, Jember',
                'tanggal_lahir'  => '1991-12-01',
            ],
            [
                'nama'           => 'Wikana Subadra Subowo',
                'email'          => 'wikana.kesiswaan@tu.test',
                'peran'           => 'kesiswaan_kurikulum',
                'jabatan'       => 'Operator Layanan Operasional',
                'iki_pelaksana'  => '7 KESISWAAN/KURIKULUM',
                'kode_depan'     => '23305',
                'telepon'          => '081298765016',
                'alamat'        => 'Jl. Sudirman No. 42, Jember',
                'tanggal_lahir'  => '1982-08-19',
            ],
        ];

        foreach ($staffData as $data) {
            $role = $data['peran'];
            unset($data['peran']);
            User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password'  => Hash::make('password'),
                    'peran'      => $role,
                    'aktif' => true,
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. ATTENDANCE SETTINGS
        |--------------------------------------------------------------------------
        */
        AttendanceSetting::updateOrCreate(
            ['id' => 1],
            [
                'jam_masuk'          => '07:30',
                'jam_pulang'         => '16:00',
                'toleransi_terlambat_menit'  => 15,
                'lat_kantor'         => -8.165908,
                'lng_kantor'        => 113.706649,
                'jarak_maksimal_meter'     => 200,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | OUTPUT
        |--------------------------------------------------------------------------
        */
        $this->command->info('');
        $this->command->info('=================================================');
        $this->command->info('  AKUN SEEDER BERHASIL!');
        $this->command->info('=================================================');
        $this->command->info('');
        $this->command->info('  AKUN LOGIN (password: password)');
        $this->command->info('  ─────────────────────────────────');
        $this->command->info('  Admin (KaTU)        : admin@tu.test');
        $this->command->info('  Kepala Sekolah      : kepsek@tu.test');
        $this->command->info('  ─── IKI 1: KEPEGAWAIAN ───');
        $this->command->info('  Dwi Kriswahyudi     : dwi.kepegawaian@tu.test');
        $this->command->info('  Faizz Moch. N.A.    : faizz.kepegawaian@tu.test');
        $this->command->info('  ─── IKI 2: PRAMU BAKTI ───');
        $this->command->info('  Eko Bagus F.         : eko.pramubakti@tu.test');
        $this->command->info('  Marsis              : marsis.pramubakti@tu.test');
        $this->command->info('  Miftahul Ulum       : miftahul.pramubakti@tu.test');
        $this->command->info('  ─── IKI 3: KEUANGAN ───');
        $this->command->info('  Ike Wijayanti       : ike.keuangan@tu.test');
        $this->command->info('  ─── IKI 4: PERSURATAN ───');
        $this->command->info('  Aris Sugito         : aris.persuratan@tu.test');
        $this->command->info('  Ginabul Rahayu      : ginabul.persuratan@tu.test');
        $this->command->info('  Herman Budi S.      : herman.persuratan@tu.test');
        $this->command->info('  ─── IKI 5: PERPUSTAKAAN ───');
        $this->command->info('  Anggra Okta W.      : anggra.perpustakaan@tu.test');
        $this->command->info('  Bagus Pribadi       : bagus.perpustakaan@tu.test');
        $this->command->info('  Moh. Sutrisno       : sutrisno.perpustakaan@tu.test');
        $this->command->info('  ─── IKI 6: INVENTARIS ───');
        $this->command->info('  Fatkurahman         : fatkurahman.inventaris@tu.test');
        $this->command->info('  Imam Basori         : imam.inventaris@tu.test');
        $this->command->info('  ─── IKI 7: KESISWAAN/KURIKULUM ───');
        $this->command->info('  Bayu Kurniawan      : bayu.kesiswaan@tu.test');
        $this->command->info('  Wikana S.S.         : wikana.kesiswaan@tu.test');
        $this->command->info('');
        $this->command->info('  Ulang tahun hari ini (testing):');
        $this->command->info('  - Dwi Kriswahyudi (dwi.kepegawaian@tu.test)');
        $this->command->info('  - Herman Budi Santoso (herman.persuratan@tu.test)');
        $this->command->info('');
        $this->command->info('  Total: 1 admin, 1 kepsek, 16 staff (7 role)');
        $this->command->info('  + AttendanceSetting (07:30-16:00, toleransi 15 menit)');
        $this->command->info('=================================================');
    }
}
