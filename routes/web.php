<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chatcontroller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [Chatcontroller::class, 'index'])->name('chat.index');
Route::post('/messages', [Chatcontroller::class, 'store'])->name('chat.store');