<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/setup', function () {
    $dbPath = '/tmp/database.sqlite';

    // لو الملف مش موجود، أنشئه
    if (!file_exists($dbPath)) {
        file_put_contents($dbPath, '');
    }

    // شغّل المايجريشن + السيدر
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);

    return '✅ Database created, migrated, and seeded.';
});
