<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chatcontroller;
 
Route::get('/', [Chatcontroller::class, 'index'])->name('chat.index');
Route::post('/messages', [Chatcontroller::class, 'store'])->name('chat.store');