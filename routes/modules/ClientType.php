<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'client-type'], function () {
    Route::get('list', [App\Http\Controllers\admin\ClientTypeController::class, 'index']);
    Route::any('add', [App\Http\Controllers\admin\ClientTypeController::class, 'add']);
    Route::any('edit/{id}', [App\Http\Controllers\admin\ClientTypeController::class, 'edit']);
    Route::any('delete/{id}', [App\Http\Controllers\admin\ClientTypeController::class, 'delete']);
    Route::any('update-status/{id}/{status}', [App\Http\Controllers\admin\ClientTypeController::class, 'updateStatus']);
});
