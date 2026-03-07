<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = [
        'nama', 'nip', 'email', 'password', 'peran', 'telepon', 'tanggal_lahir',
        'jabatan', 'kode_depan', 'iki_pelaksana', 'foto', 'alamat', 'aktif',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // All available roles
    const ROLE_ADMIN = 'admin';
    const ROLE_KEPALA_SEKOLAH = 'kepala_sekolah';
    const ROLE_KEPEGAWAIAN = 'kepegawaian';
    const ROLE_PRAMU_BAKTI = 'pramu_bakti';
    const ROLE_KEUANGAN = 'keuangan';
    const ROLE_PERSURATAN = 'persuratan';
    const ROLE_PERPUSTAKAAN = 'perpustakaan';
    const ROLE_INVENTARIS = 'inventaris';
    const ROLE_KESISWAAN_KURIKULUM = 'kesiswaan_kurikulum';
    const ROLE_STAFF = 'staff';
    const ROLE_MAGANG = 'magang';

    const ROLES = [
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_KEPALA_SEKOLAH => 'Kepala Sekolah',
        self::ROLE_KEPEGAWAIAN => 'Kepegawaian',
        self::ROLE_PRAMU_BAKTI => 'Pramu Bakti',
        self::ROLE_KEUANGAN => 'Keuangan',
        self::ROLE_PERSURATAN => 'Persuratan',
        self::ROLE_PERPUSTAKAAN => 'Perpustakaan',
        self::ROLE_INVENTARIS => 'Inventaris/Sarpras',
        self::ROLE_KESISWAAN_KURIKULUM => 'Kesiswaan/Kurikulum',
        self::ROLE_STAFF => 'Staff',
        self::ROLE_MAGANG => 'Staff Magang',
    ];

    // Staff-level roles (roles that share the staff layout)
    const STAFF_ROLES = [
        self::ROLE_KEPEGAWAIAN, self::ROLE_PRAMU_BAKTI, self::ROLE_KEUANGAN,
        self::ROLE_PERSURATAN, self::ROLE_PERPUSTAKAAN, self::ROLE_INVENTARIS,
        self::ROLE_KESISWAAN_KURIKULUM, self::ROLE_STAFF,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aktif' => 'boolean',
            'tanggal_lahir' => 'date',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->peran === self::ROLE_ADMIN;
    }

    public function isKepalaSekolah(): bool
    {
        return $this->peran === self::ROLE_KEPALA_SEKOLAH;
    }

    public function isMagang(): bool
    {
        return $this->peran === self::ROLE_MAGANG;
    }

    public function isStaff(): bool
    {
        return in_array($this->peran, self::STAFF_ROLES) || $this->peran === self::ROLE_STAFF;
    }

    public function isStaffRole(): bool
    {
        return in_array($this->peran, self::STAFF_ROLES);
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->peran] ?? ucfirst($this->peran);
    }

    /**
     * Get the dashboard route for this user's role
     */
    public function getDashboardRoute(): string
    {
        return match ($this->peran) {
            self::ROLE_ADMIN => 'admin.beranda',
            self::ROLE_KEPALA_SEKOLAH => 'kepala-sekolah.beranda',
            self::ROLE_MAGANG => 'magang.beranda',
            default => 'staf.beranda',
        };
    }

    /**
     * Get the prefix for this user's role routes
     */
    public function getRoutePrefix(): string
    {
        return match ($this->peran) {
            self::ROLE_ADMIN => 'admin',
            self::ROLE_KEPALA_SEKOLAH => 'kepala-sekolah',
            self::ROLE_MAGANG => 'magang',
            default => 'staf',
        };
    }

    public function isBirthdayToday(): bool
    {
        return $this->tanggal_lahir && $this->tanggal_lahir->format('m-d') === now()->format('m-d');
    }

    public function ucapanDiterima()
    {
        return $this->hasMany(UcapanUlangTahun::class, 'penerima_id');
    }

    public function ucapanDikirim()
    {
        return $this->hasMany(UcapanUlangTahun::class, 'pengirim_id');
    }

    public function catatanBeranda()
    {
        return $this->hasMany(CatatanBeranda::class, 'pengguna_id');
    }

    public function attendances()
    {
        return $this->hasMany(Kehadiran::class, 'pengguna_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(PengajuanIzin::class, 'pengguna_id');
    }

    public function reports()
    {
        return $this->hasMany(Laporan::class, 'pengguna_id');
    }

    public function events()
    {
        return $this->hasMany(Acara::class, 'dibuat_oleh');
    }

    public function notifications()
    {
        return $this->hasMany(Notifikasi::class, 'pengguna_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('sudah_dibaca', false);
    }

    public function todayAttendance()
    {
        return $this->hasOne(Kehadiran::class, 'pengguna_id')->whereDate('tanggal', today());
    }

    public function skp()
    {
        return $this->hasMany(Skp::class, 'pengguna_id');
    }
}
