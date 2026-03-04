<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Backup
    |--------------------------------------------------------------------------
    */

    // Direktori lokal untuk menyimpan backup sementara
    'local_path' => storage_path('app/backups'),

    // Jumlah backup yang disimpan di Google Drive
    'keep_count' => 5,

    // Database yang akan di-backup
    'database' => [
        'connection' => env('DB_CONNECTION', 'mysql'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'tu_administrasi'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
    ],

    // Folder yang akan di-backup (selain database)
    'folders' => [
        storage_path('app/public'), // uploaded files
    ],

    // Google Drive settings
    'google_drive' => [
        // Path ke file credentials.json dari Google Cloud Console
        'credentials_path' => storage_path('app/google/credentials.json'),

        // Path ke file token.json (akan dibuat otomatis setelah auth)
        'token_path' => storage_path('app/google/token.json'),

        // Nama folder di Google Drive
        'folder_name' => env('GOOGLE_DRIVE_BACKUP_FOLDER', 'TU_Admin_Backup'),
    ],

    // Jadwal otomatis (cron expression)
    'schedule' => env('BACKUP_SCHEDULE', 'daily'), // daily, weekly, hourly
];
