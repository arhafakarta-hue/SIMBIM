<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('simbim:info', function () {
    $this->info('SIMBIM Realtime Laravel siap digunakan.');
})->purpose('Menampilkan info aplikasi SIMBIM');
