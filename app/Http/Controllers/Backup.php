<?php
namespace App\Http\Controllers;

use Artisan;

class Backup extends Controller
{
    public function index()
    {
        // Controller or Livewire
        Artisan::call('backup:run', ['--only-db' => true]);
        $output = Artisan::output();
        dd($output);

    }
}
