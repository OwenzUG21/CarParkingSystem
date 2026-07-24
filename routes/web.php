<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/admin', function () {
    return view('admin');
});

// Lot Manager dashboard
Route::get('/manager', function () {
    return view('dash');
});

// Keeper dashboard
Route::get('/keeper', function () {
    return view('keeper');
});

