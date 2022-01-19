<?php

namespace App\BackupCleaner;

use Spatie\Backup\Tasks\Cleanup\CleanupStrategy;

use Spatie\Backup\BackupDestination\BackupCollection;

class BackupCleaner extends CleanupStrategy
{
    public function deleteOldBackups(BackupCollection $backups){
        $backup = $backups->oldest();
        if(isset($backup)) {
            $backup->delete();
        }
    }
}
