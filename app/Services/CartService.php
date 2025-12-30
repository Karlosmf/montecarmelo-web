<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Events\OrderCreated;
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

    public function createOrder(array $customerData = []): Order
    {
        $items = $this->getDetails();
        $total = $this->total();

        $order = Order::create([
            'customer_name' => $customerData['name'] ?? 'Guest',
            'customer_phone' => $customerData['phone'] ?? null,
            'items' => $items->toArray(),
            'total' => (int) $total,
            'status' => 'pending',
        ]);

        OrderCreated::dispatch($order);

        $this->clear();

        return $order;
    }

    public function getWhatsAppLink(?Order $order = null): string
    {
        if ($order) {
            $items = collect($order->items);
            $totalVal = number_format($order->total / 100, 2);
            $orderId = "#" . str_pad($order->id, 4, '0', STR_PAD_LEFT);
        } else {
            // Fallback for direct link without order
            $items = $this->getDetails();
            if ($items->isEmpty())
                return '';
            $totalVal = number_format($this->total() / 100, 2);
            $orderId = "N/A";
        }

        $message = "Hola Monte Carmelo, mi pedido {$orderId}:\n";

        foreach ($items as $item) {
            // Check if item is array (from order DB) or object (from session cart)
            $name = is_array($item) ? $item['name'] : $item->name;
            $qty = is_array($item) ? $item['qty'] : $item->qty;
            $unit = is_array($item) ? $item['unit_type'] : $item->unit_type;

            $qtyDisplay = $qty;
            if ($unit === 'kg') {
                $qtyDisplay = $qty . 'g';
            } elseif ($unit === 'unit') {
                $qtyDisplay .= ' un.';
            }

            $message .= "- {$name} ({$qtyDisplay})\n";
        }

        $message .= "Total Estimado: \${$totalVal}";

        $phoneNumber = config('montecarmelo.contact.whatsapp_number');

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
