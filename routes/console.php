<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('db:backup', function () {
    $backupFile = storage_path('app/backups/database_backup_' . date('Y_m_d_H_i_s') . '.sql');
    $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . escapeshellarg($backupFile);

    if (system($command) === false) {
        $this->error('Database backup failed.');
        Log::warning('Database backup failed.', [
            'command' => $command,
            'timestamp' => now(),
        ]);
    } else {
        $this->info('Database backup created successfully: ' . $backupFile);
        Log::info('Database backup created successfully.', [
            'backup_file' => $backupFile,
            'timestamp' => now(),
        ]);
    }
})->purpose('Backup the database');
