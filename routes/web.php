<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'home');
Volt::route('/products', 'catalog.index');
Volt::route('/contact', 'contact');
