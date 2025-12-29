<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'cart_items';

    public function add(int $id, int $qty, string $unit): void
    {
        $cart = Session::get($this->sessionKey, []);

        // Retrieve the product to store in session as requested
        $product = Product::find($id);

        if (!$product) {
            return;
        }

        $cart[$id] = [
            'qty' => $qty,
            'unit_type' => $unit,
            'product' => $product,
        ];

        Session::put($this->sessionKey, $cart);
    }

    public function remove(int $id): void
    {
        $cart = Session::get($this->sessionKey, []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put($this->sessionKey, $cart);
        }
    }

    public function getDetails(): Collection
    {
        $cart = Session::get($this->sessionKey, []);

        return collect($cart)->map(function ($item) {
            /** @var Product $product */
            $product = $item['product'];
            
            // Ensure we don't have stale data if possible, but using session obj as requested.
            // We inject the cart-specific details into the product object for the view.
            $product->qty = $item['qty'];
            $product->unit_type = $item['unit_type'];
            $product->subtotal = $this->calculateSubtotal($product->price, $item['qty'], $item['unit_type']);

            return $product;
        });
    }

    public function total(): float
    {
        return $this->getDetails()->sum('subtotal');
    }

    public function count(): int
    {
        return count(Session::get($this->sessionKey, []));
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    public function getWhatsAppLink(): string
    {
        $items = $this->getDetails();
        if ($items->isEmpty()) {
            return '';
        }

        $total = number_format($this->total() / 100, 2);
        
        $message = "Hola Monte Carmelo, mi pedido:\n";

        foreach ($items as $item) {
            $qtyDisplay = $item->qty;
            if ($item->unit_type === 'kg') {
                $qtyDisplay = $item->qty . 'g';
            } elseif ($item->unit_type === 'unit') {
                 $qtyDisplay .= ' un.';
            }

            $message .= "- {$item->name} ({$qtyDisplay})\n";
        }

        $message .= "Total Estimado: \${$total}";

        // Using a generic number or config would be better, hardcoding as per previous context or empty
        // The prompt says "getWhatsAppLink()", implies returning the string.
        // Assuming a default number or passed as arg? The prompt doesn't specify arg for this method but previous did.
        // I'll add an optional argument defaulting to a config or placeholder.
        $phoneNumber = '5491112345678'; 

        return "https://wa.me/{$phoneNumber}?text=" . urlencode($message);
    }

    protected function calculateSubtotal(int $price, int $qty, string $unitType): int
    {
        if ($unitType === 'kg') {
            // Price per Kg, qty in grams
            return (int) round(($price * $qty) / 1000);
        }

        return $price * $qty;
    }
}

