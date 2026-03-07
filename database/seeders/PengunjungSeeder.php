<?php

namespace Database\Seeders;

use App\Models\Pengunjung;
use Illuminate\Database\Seeder;

class PengunjungSeeder extends Seeder
{
    public function run(): void
    {
        $visitors = [
            ['ip_address' => '36.68.45.100', 'negara' => 'Indonesia', 'kota' => 'Jember', 'perangkat' => 'desktop', 'browser' => 'Chrome', 'platform' => 'Windows', 'halaman' => '/', 'latitude' => -8.1722, 'longitude' => 113.7029],
            ['ip_address' => '36.68.45.101', 'negara' => 'Indonesia', 'kota' => 'Surabaya', 'perangkat' => 'mobile', 'browser' => 'Chrome', 'platform' => 'Android', 'halaman' => '/', 'latitude' => -7.2575, 'longitude' => 112.7521],
            ['ip_address' => '36.68.45.102', 'negara' => 'Indonesia', 'kota' => 'Jakarta', 'perangkat' => 'desktop', 'browser' => 'Firefox', 'platform' => 'Windows', 'halaman' => '/kinerja', 'latitude' => -6.2088, 'longitude' => 106.8456],
            ['ip_address' => '36.68.45.103', 'negara' => 'Indonesia', 'kota' => 'Malang', 'perangkat' => 'mobile', 'browser' => 'Safari', 'platform' => 'iOS', 'halaman' => '/', 'latitude' => -7.9666, 'longitude' => 112.6326],
            ['ip_address' => '36.68.45.104', 'negara' => 'Indonesia', 'kota' => 'Bandung', 'perangkat' => 'desktop', 'browser' => 'Edge', 'platform' => 'Windows', 'halaman' => '/dokumen', 'latitude' => -6.9175, 'longitude' => 107.6191],
            ['ip_address' => '36.68.45.105', 'negara' => 'Indonesia', 'kota' => 'Banyuwangi', 'perangkat' => 'mobile', 'browser' => 'Chrome', 'platform' => 'Android', 'halaman' => '/', 'latitude' => -8.2193, 'longitude' => 114.3691],
            ['ip_address' => '36.68.45.106', 'negara' => 'Indonesia', 'kota' => 'Lumajang', 'perangkat' => 'desktop', 'browser' => 'Chrome', 'platform' => 'Windows', 'halaman' => '/kinerja', 'latitude' => -8.1337, 'longitude' => 113.2247],
            ['ip_address' => '36.68.45.107', 'negara' => 'Indonesia', 'kota' => 'Yogyakarta', 'perangkat' => 'tablet', 'browser' => 'Safari', 'platform' => 'iOS', 'halaman' => '/', 'latitude' => -7.7956, 'longitude' => 110.3695],
            ['ip_address' => '36.68.45.108', 'negara' => 'Indonesia', 'kota' => 'Semarang', 'perangkat' => 'mobile', 'browser' => 'Chrome', 'platform' => 'Android', 'halaman' => '/', 'latitude' => -6.9932, 'longitude' => 110.4203],
            ['ip_address' => '36.68.45.109', 'negara' => 'Indonesia', 'kota' => 'Denpasar', 'perangkat' => 'desktop', 'browser' => 'Chrome', 'platform' => 'macOS', 'halaman' => '/dokumen', 'latitude' => -8.6525, 'longitude' => 115.2192],
            ['ip_address' => '103.28.100.50', 'negara' => 'Malaysia', 'kota' => 'Kuala Lumpur', 'perangkat' => 'desktop', 'browser' => 'Chrome', 'platform' => 'Windows', 'halaman' => '/', 'latitude' => 3.1390, 'longitude' => 101.6869],
            ['ip_address' => '118.189.160.10', 'negara' => 'Singapura', 'kota' => 'Singapore', 'perangkat' => 'mobile', 'browser' => 'Safari', 'platform' => 'iOS', 'halaman' => '/', 'latitude' => 1.3521, 'longitude' => 103.8198],
            ['ip_address' => '110.44.124.20', 'negara' => 'Australia', 'kota' => 'Sydney', 'perangkat' => 'desktop', 'browser' => 'Firefox', 'platform' => 'macOS', 'halaman' => '/kinerja', 'latitude' => -33.8688, 'longitude' => 151.2093],
            ['ip_address' => '203.0.113.15', 'negara' => 'Jepang', 'kota' => 'Tokyo', 'perangkat' => 'desktop', 'browser' => 'Chrome', 'platform' => 'Windows', 'halaman' => '/', 'latitude' => 35.6762, 'longitude' => 139.6503],
            ['ip_address' => '198.51.100.25', 'negara' => 'Amerika Serikat', 'kota' => 'New York', 'perangkat' => 'desktop', 'browser' => 'Chrome', 'platform' => 'macOS', 'halaman' => '/', 'latitude' => 40.7128, 'longitude' => -74.0060],
            ['ip_address' => '192.0.2.30', 'negara' => 'Belanda', 'kota' => 'Amsterdam', 'perangkat' => 'desktop', 'browser' => 'Firefox', 'platform' => 'Linux', 'halaman' => '/dokumen', 'latitude' => 52.3676, 'longitude' => 4.9041],
            ['ip_address' => '36.68.45.110', 'negara' => 'Indonesia', 'kota' => 'Jember', 'perangkat' => 'mobile', 'browser' => 'Chrome', 'platform' => 'Android', 'halaman' => '/', 'latitude' => -8.1722, 'longitude' => 113.7029],
            ['ip_address' => '36.68.45.111', 'negara' => 'Indonesia', 'kota' => 'Jember', 'perangkat' => 'desktop', 'browser' => 'Chrome', 'platform' => 'Windows', 'halaman' => '/kinerja', 'latitude' => -8.1722, 'longitude' => 113.7029],
            ['ip_address' => '36.68.45.112', 'negara' => 'Indonesia', 'kota' => 'Jember', 'perangkat' => 'mobile', 'browser' => 'Safari', 'platform' => 'iOS', 'halaman' => '/', 'latitude' => -8.1722, 'longitude' => 113.7029],
            ['ip_address' => '36.68.45.113', 'negara' => 'Indonesia', 'kota' => 'Jember', 'perangkat' => 'desktop', 'browser' => 'Edge', 'platform' => 'Windows', 'halaman' => '/dokumen', 'latitude' => -8.1722, 'longitude' => 113.7029],
        ];

        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0',
        ];

        foreach ($visitors as $i => $visitor) {
            $daysAgo = rand(0, 30);
            $hoursAgo = rand(6, 22);

            Pengunjung::create([
                'ip_address'  => $visitor['ip_address'],
                'user_agent'  => $agents[array_rand($agents)],
                'halaman'     => $visitor['halaman'],
                'referer'     => $i % 3 === 0 ? 'https://www.google.com' : null,
                'negara'      => $visitor['negara'],
                'kota'        => $visitor['kota'],
                'latitude'    => $visitor['latitude'],
                'longitude'   => $visitor['longitude'],
                'perangkat'   => $visitor['perangkat'],
                'browser'     => $visitor['browser'],
                'platform'    => $visitor['platform'],
                'created_at'  => now()->subDays($daysAgo)->setHour($hoursAgo)->setMinute(rand(0, 59)),
                'updated_at'  => now()->subDays($daysAgo)->setHour($hoursAgo)->setMinute(rand(0, 59)),
            ]);
        }

        // Add extra visits from same IPs to make it realistic
        for ($i = 0; $i < 30; $i++) {
            $base = $visitors[array_rand($visitors)];
            Pengunjung::create([
                'ip_address'  => $base['ip_address'],
                'user_agent'  => $agents[array_rand($agents)],
                'halaman'     => ['/', '/kinerja', '/dokumen'][array_rand(['/', '/kinerja', '/dokumen'])],
                'referer'     => rand(0, 1) ? 'https://www.google.com' : null,
                'negara'      => $base['negara'],
                'kota'        => $base['kota'],
                'latitude'    => $base['latitude'],
                'longitude'   => $base['longitude'],
                'perangkat'   => $base['perangkat'],
                'browser'     => $base['browser'],
                'platform'    => $base['platform'],
                'created_at'  => now()->subDays(rand(0, 30))->setHour(rand(6, 22))->setMinute(rand(0, 59)),
                'updated_at'  => now()->subDays(rand(0, 30))->setHour(rand(6, 22))->setMinute(rand(0, 59)),
            ]);
        }
    }
}
