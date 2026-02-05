<?php

use Illuminate\Support\Facades\Artisan;

// Run migrations when /migrate is accessed (protected by APP_KEY)
if (isset($_GET['key']) && $_GET['key'] === config('app.key')) {
    try {
        Artisan::call('migrate', ['--force' => true]);
        echo "Migrations completed: " . Artisan::output();
    } catch (Exception $e) {
        echo "Migration failed: " . $e->getMessage();
    }
    exit;
}
