<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staf Routes (Semua Role Staff / Pegawai TU)
|--------------------------------------------------------------------------
| Prefix: /staf
| Name: staf.*
| Middleware: auth, role:all_staff
|--------------------------------------------------------------------------
*/

// 1. Routes Umum (Semua Staff)
require __DIR__.'/staf/umum.php';

// 2. Routes Khusus Per Role
require __DIR__.'/staf/inventaris.php';
require __DIR__.'/staf/inventaris-peminjaman.php';
require __DIR__.'/staf/keuangan.php';
require __DIR__.'/staf/persuratan.php';
require __DIR__.'/staf/perpustakaan.php';
require __DIR__.'/staf/kesiswaan-kurikulum.php';
require __DIR__.'/staf/kepegawaian.php';
require __DIR__.'/staf/pramubakti.php';
