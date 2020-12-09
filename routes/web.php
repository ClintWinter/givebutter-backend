<?php

use App\Models\Contact;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactEmailController;
use App\Http\Controllers\ContactMergeController;
use App\Http\Controllers\ContactPhoneController;

Route::prefix('contacts')->group(function () {
    Route::get('new', [ContactController::class, 'create']);
    Route::post('new', [ContactController::class, 'store']);
    Route::get('{contact}/edit', [ContactController::class, 'edit']);
    Route::put('{contact}/edit', [ContactController::class, 'update']);

    Route::put('{contact}/add-email', [ContactEmailController::class, 'store']);
    Route::delete('{contact}/delete-email', [ContactEmailController::class, 'destroy']);

    Route::put('{contact}/add-phone', [ContactPhoneController::class, 'store']);
    Route::delete('{contact}/delete-phone', [ContactPhoneController::class, 'destroy']);

    Route::post('merge', [ContactMergeController::class, 'store']);
});
