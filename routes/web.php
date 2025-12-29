<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'home');
Volt::route('/products', 'catalog.index');
Volt::route('/contact', 'contact');

// Admin Routes
Route::prefix('admin')->group(function () {
    Volt::route('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Volt::route('/users', 'admin.users')->name('admin.users');
    Volt::route('/categories', 'admin.categories')->name('admin.categories');
    Volt::route('/tags', 'admin.tags')->name('admin.tags');
    Volt::route('/products', 'admin.products')->name('admin.products');
    Volt::route('/orders', 'admin.orders.index')->name('admin.orders');
    // Future routes will go here
});
