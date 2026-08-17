<?php

use App\Http\Controllers\QmsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QmsController::class, 'index'])->name('qms.dashboard');
Route::get('/dashboard', [QmsController::class, 'index'])->name('qms.dashboard.path');
Route::get('/qms', [QmsController::class, 'index'])->name('qms.index');
Route::post('/qms/occurrences', [QmsController::class, 'storeOccurrence'])->name('qms.occurrences.store');
