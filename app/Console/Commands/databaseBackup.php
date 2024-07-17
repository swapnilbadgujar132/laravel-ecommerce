<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class databaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for database backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $dbUsername = 'root';
        // $dbPassword = '';
        // $dbHost = 'localhost';
        // $dbName = 'myecomproject';


        $dbUsername = env('DB_USERNAME');
        $dbPassword =  env('DB_PASSWORD');
        $dbHost =  env('DB_HOST');
        $dbName =  env('DB_DATABASE');
        
        if (empty($dbUsername) || empty($dbHost) || empty($dbName)) {
            $this->error('Database credentials are not set properly.');
            $this->info('DB_USERNAME: ' . $dbUsername);
            $this->info('DB_HOST: ' . $dbHost);
            $this->info('DB_DATABASE: ' . $dbName);
            return;
        }

        $gzFileName = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";
        $mysqldumpPath = "C:\\xampp\\mysql\\bin\\mysqldump.exe";
        $gzipPath = "C:\\Program Files (x86)\\GnuWin32\\bin\\gzip.exe";
        $backupPath = storage_path("app/backup/" . $gzFileName);
        $gzBackupPath = storage_path("app/backup/" . $gzFileName);

        if (!file_exists(storage_path('app/backup'))) {
            mkdir(storage_path('app/backup'), 0777, true);
        }

        $dumpCommand = "\"{$mysqldumpPath}\" --user={$dbUsername} --password={$dbPassword} --host={$dbHost} {$dbName} > \"{$backupPath}\"";
        $gzipCommand = "\"{$gzipPath}\" \"{$backupPath}\"";

        // Debugging commands
        $this->info("Dump Command: " . $dumpCommand);
        $this->info("Gzip Command: " . $gzipCommand);

        $output = NULL;
        $returnVar = NULL;

        exec($dumpCommand, $output, $returnVar);
        if ($returnVar !== 0) {
            $this->error("Backup command failed with return value {$returnVar}");
            $this->error("Command output: " . implode("\n", $output));
            return;
        }

        exec($gzipCommand, $output, $returnVar);
        if ($returnVar !== 0) {
            $this->error("Gzip command failed with return value {$returnVar}");
            $this->error("Command output: " . implode("\n", $output));
            return;
        }

        $this->info("Database backup successfully created at {$gzBackupPath}");
    }
}
