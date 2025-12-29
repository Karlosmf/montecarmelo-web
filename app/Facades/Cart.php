<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void add(int $id, int $qty, string $unit)
 * @method static void remove(int $id)
 * @method static \Illuminate\Support\Collection getDetails()
 * @method static float total()
 * @method static void clear()
 * @method static int count()
 * @method static string getWhatsAppLink()
 * 
 * @see \App\Services\CartService
 */
class Cart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}
