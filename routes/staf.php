<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staf Routes (Semua Role Staff / Pegawai TU)
|--------------------------------------------------------------------------
| Prefix: /staf
| Name: staf.*
| Middleware: auth, role:all_staff
|
| Struktur file route staff dipisah per role:
|   routes/staf/umum.php              → Fitur umum semua staff
|   routes/staf/kepegawaian.php       → Fitur khusus kepegawaian
|   routes/staf/pramu-bakti.php       → Fitur khusus pramu bakti
|   routes/staf/keuangan.php          → Fitur khusus keuangan
|   routes/staf/persuratan.php        → Fitur khusus persuratan
|   routes/staf/perpustakaan.php      → Fitur khusus perpustakaan
|   routes/staf/inventaris.php        → Fitur khusus inventaris/sarpras
|   routes/staf/kesiswaan-kurikulum.php → Fitur khusus kesiswaan & kurikulum
|
| Role yang termasuk:
| - kepegawaian, pramu_bakti, keuangan, persuratan,
|   perpustakaan, inventaris, kesiswaan_kurikulum, staff
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| 1. Routes Umum (Semua Staff)
|--------------------------------------------------------------------------
*/
require __DIR__.'/staf/umum.php';

/*
|--------------------------------------------------------------------------
| 2. Routes Khusus Per Role
|--------------------------------------------------------------------------
| File-file ini berisi route tambahan yang hanya relevan untuk role tertentu.
| Semua route di bawah ini tetap menggunakan prefix 'staf.' dan middleware
| 'all_staff', tapi akan dicek di controller jika perlu pembatasan lebih lanjut.
|--------------------------------------------------------------------------
*/
require __DIR__.'/staf/kepegawaian.php';
require __DIR__.'/staf/pramu-bakti.php';
require __DIR__.'/staf/keuangan.php';
require __DIR__.'/staf/persuratan.php';
require __DIR__.'/staf/perpustakaan.php';
require __DIR__.'/staf/inventaris.php';
require __DIR__.'/staf/kesiswaan-kurikulum.php';
