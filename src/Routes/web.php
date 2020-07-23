<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin', function () {
    return redirect()->away(config('core.admin_url') . "/baseUrl=" . config('core.web_url'));
});
