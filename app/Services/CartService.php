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
        // Validar cantidad positiva
        if ($qty <= 0) {
            throw new \InvalidArgumentException('La cantidad debe ser mayor a cero');
        }

        // Validar tipo de unidad válido
        if (!in_array($unit, ['kg', 'unit', 'pack'], true)) {
            throw new \InvalidArgumentException('Tipo de unidad inválido');
        }

        // Verificar que el producto existe y está activo
        $product = Product::where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            throw new \Exception('Producto no disponible');
        }

        $cart = Session::get($this->sessionKey, []);

        $cart[$id] = [
            'id' => $id,
            'qty' => $qty,
            'unit_type' => $unit,
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
        $productIds = array_keys($cart);

        // Eager load relationships to prevent N+1 queries
        $products = Product::with(['category', 'tags'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function ($item) use ($products) {
            $productId = $item['id'];

            if (!$products->has($productId)) {
                return null;
            }

            $product = $products->get($productId);
            $product->qty = $item['qty'];
            $product->unit_type = $item['unit_type'];
            $product->subtotal = $this->calculateSubtotal($product->price, $item['qty'], $item['unit_type']);

            return $product;
        })->filter();
    }

    public function total(): int
    {
        return (int) $this->getDetails()->sum('subtotal');
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

        // Map items to include necessary snapshot data
        $itemsSnapshot = $items->map(function ($item) {
            return [
                'product_id' => $item->id,
                'category_id' => $item->category_id,
                'name' => $item->name,
                'qty' => $item->qty,
                'unit_type' => $item->unit_type,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ];
        });

        $order = Order::create([
            'customer_name' => $customerData['name'] ?? 'Guest',
            'customer_phone' => $customerData['phone'] ?? null,
            'items' => $itemsSnapshot->toArray(),
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
