<?php

namespace Tests\Feature;

use App\Facades\Cart;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_items_to_cart()
    {
        $product = Product::factory()->create([
            'name' => 'Manzanas',
            'price' => 1000, // $10.00
            'unit_type' => 'kg'
        ]);

        Cart::add($product->id, 500, 'kg'); // 500g

        $this->assertEquals(1, Cart::count());
        $this->assertEquals(500, session('cart_items')[$product->id]['qty']);
    }

    public function test_can_retrieve_cart_items_with_totals()
    {
        $apple = Product::factory()->create([
            'name' => 'Manzanas',
            'price' => 1000, // $10.00 per kg
            'unit_type' => 'kg'
        ]);

        $water = Product::factory()->create([
            'name' => 'Agua',
            'price' => 500, // $5.00 per unit
            'unit_type' => 'unit'
        ]);

        Cart::add($apple->id, 500, 'kg'); // 500g = 0.5kg -> $5.00 (500 cents)
        Cart::add($water->id, 2, 'unit'); // 2 units -> $10.00 (1000 cents)

        $items = Cart::getDetails();

        $this->assertCount(2, $items);
        
        $appleItem = $items->firstWhere('id', $apple->id);
        $this->assertEquals(500, $appleItem->subtotal); // 500 cents

        $waterItem = $items->firstWhere('id', $water->id);
        $this->assertEquals(1000, $waterItem->subtotal); // 1000 cents

        $this->assertEquals(1500, Cart::total());
    }

    public function test_can_remove_item()
    {
        $product = Product::factory()->create();
        Cart::add($product->id, 1, 'unit');
        
        Cart::remove($product->id);
        
        $this->assertEquals(0, Cart::count());
    }

    public function test_whatsapp_link_generation()
    {
        $product = Product::factory()->create([
            'name' => 'TestProduct',
            'price' => 1000,
        ]);

        Cart::add($product->id, 1, 'unit');

        $link = Cart::getWhatsAppLink();

        $this->assertStringContainsString('https://wa.me/5491112345678', $link);
        $this->assertStringContainsString('TestProduct', urldecode($link));
    }
}
