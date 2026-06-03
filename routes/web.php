<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect home to dashboard
Route::redirect('/', '/dashboard');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::login')->name('login');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
    Route::livewire('/berkas/create', 'pages::berkas-form')->name('berkas.create');
    Route::livewire('/berkas/{id}/edit', 'pages::berkas-form')->name('berkas.edit');
    Route::livewire('/change-password', 'pages::change-password')->name('change-password');
    
    // Logout action
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->to('/login');
    })->name('logout');
});
