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
|   routes/staf/inventaris.php        → Fitur khusus inventaris/sarpras
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
*/
require __DIR__.'/staf/inventaris.php';
