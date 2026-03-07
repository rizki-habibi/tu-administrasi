<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pengunjung extends Model
{
    protected $table = 'pengunjung';

    protected $fillable = [
        'ip_address', 'user_agent', 'halaman', 'referer',
        'negara', 'kota', 'latitude', 'longitude',
        'perangkat', 'browser', 'platform',
    ];

    /**
     * Catat kunjungan baru dari request.
     */
    public static function catat(Request $request, string $halaman = '/'): self
    {
        $userAgent = $request->userAgent() ?? '';
        $perangkat = self::deteksiPerangkat($userAgent);
        $browser = self::deteksiBrowser($userAgent);
        $platform = self::deteksiPlatform($userAgent);

        return static::create([
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr($userAgent, 0, 255),
            'halaman'    => $halaman,
            'referer'    => $request->header('referer') ? mb_substr($request->header('referer'), 0, 255) : null,
            'perangkat'  => $perangkat,
            'browser'    => $browser,
            'platform'   => $platform,
        ]);
    }

    /**
     * Hitung pengunjung unik (berdasarkan IP) hari ini.
     */
    public static function hariIni(): int
    {
        return static::whereDate('created_at', today())
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Hitung pengunjung unik bulan ini.
     */
    public static function bulanIni(): int
    {
        return static::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('ip_address')
            ->count('ip_address');
    }

    /**
     * Hitung total pengunjung unik keseluruhan.
     */
    public static function totalUnik(): int
    {
        return static::distinct('ip_address')->count('ip_address');
    }

    /**
     * Hitung total kunjungan (page views) keseluruhan.
     */
    public static function totalKunjungan(): int
    {
        return static::count();
    }

    /**
     * Deteksi tipe perangkat dari user agent.
     */
    private static function deteksiPerangkat(string $ua): string
    {
        if (preg_match('/mobile|android|iphone|ipod|opera mini|iemobile|blackberry/i', $ua)) {
            return 'mobile';
        }
        if (preg_match('/tablet|ipad|kindle|silk/i', $ua)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Deteksi nama browser dari user agent.
     */
    private static function deteksiBrowser(string $ua): string
    {
        if (preg_match('/edg/i', $ua)) return 'Edge';
        if (preg_match('/opr|opera/i', $ua)) return 'Opera';
        if (preg_match('/chrome|chromium|crios/i', $ua)) return 'Chrome';
        if (preg_match('/firefox|fxios/i', $ua)) return 'Firefox';
        if (preg_match('/safari/i', $ua)) return 'Safari';
        if (preg_match('/msie|trident/i', $ua)) return 'IE';
        return 'Lainnya';
    }

    /**
     * Deteksi platform/OS dari user agent.
     */
    private static function deteksiPlatform(string $ua): string
    {
        if (preg_match('/windows/i', $ua)) return 'Windows';
        if (preg_match('/macintosh|mac os/i', $ua)) return 'macOS';
        if (preg_match('/linux/i', $ua) && !preg_match('/android/i', $ua)) return 'Linux';
        if (preg_match('/android/i', $ua)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $ua)) return 'iOS';
        return 'Lainnya';
    }
}
