<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Middleware\ApiAuthMiddleware;

Route::middleware([ApiAuthMiddleware::class])->group(function() {
    Route::get('/me', [ProfileController::class, 'me']);
});
