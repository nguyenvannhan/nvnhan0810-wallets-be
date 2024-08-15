<?php

use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;

Route::middleware([ApiAuthMiddleware::class])->group(function() {
    Route::get('/test', function (Request $request) {
        return response()->json(['message' => $request->user]);
    });
});
