<?php

use Illuminate\Support\Facades\Route;

// SPA fallback - serves React app for any route not matched by API
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
