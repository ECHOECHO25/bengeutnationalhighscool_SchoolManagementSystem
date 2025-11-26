<?php
namespace App\Livewire\Admin;

use App\Jobs\RunDatabaseBackup;
use App\Jobs\RunDatabaseRestore;
use App\Models\BackupFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BackupRestore extends Component
{
    use WithFileUploads;
    public $sql_file;
    public $backups = [];

    public function restoreDatabase($name)
    {
        RunDatabaseRestore::dispatch($name);
         sweetalert()->success('Database restored successfully. Please wait for a few seconds and refresh the page.');
    }

   

    public function mount()
    {
        $this->loadBackups();
    }

    public function loadBackups()
    {
        $disk   = 'snapshots';
        $folder = ''; // root of the snapshots disk

        $files = Storage::disk($disk)->files($folder);

        $this->backups = collect($files)
            ->filter(fn($f) => strtolower(pathinfo($f, PATHINFO_EXTENSION)) === 'sql')
            ->map(function ($f) use ($disk) {
                return [
                    'filename'      => basename($f),
                    'path'          => $f,
                    'size'          => Storage::disk($disk)->size($f),
                    'last_modified' => date(
                        'Y-m-d H:i:s',
                        Storage::disk($disk)->lastModified($f)
                    ),
                ];
            })
            ->sortByDesc(fn($file) => $file['last_modified'])
            ->values()
            ->toArray();

    }
    public $output = '';

    public function createBackup(): void
    {
        $backup = BackupFile::create([
            'filename' => 'backup-' . date('Y-m-d-H-i-s'),
        ]);
        RunDatabaseBackup::dispatch($backup->filename);
    sweetalert()->success('Backup create successfully. Please wait for a few seconds and refresh the page.');

    }

    public function downloadBackup($path)
    {
        return Storage::disk('snapshots')->download($path);
    }

    public function deleteBackup($path)
    {
        Storage::disk('local')->delete($path);
        $this->loadBackups(); // refresh table
    }

    public function render()
    {
        return view('livewire.admin.backup-restore');
    }
}
