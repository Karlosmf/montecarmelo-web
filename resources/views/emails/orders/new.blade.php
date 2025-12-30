<x-mail::message>
    # Nuevo Pedido #{{ $order->id }}

    Hola Admin,

    Has recibido un nuevo pedido a través de la web.

    **Cliente:** {{ $order->customer_name ?? 'Guest' }}
    **Teléfono:** {{ $order->customer_phone ?? 'N/A' }}
    **Total:** ${{ number_format($order->total / 100, 2) }}

    ## Detalle del Pedido

    @foreach($order->items as $item)
        - **{{ $item['name'] }}**: {{ $item['qty'] }} {{ $item['unit_type'] }}
    @endforeach

    <x-mail::button :url="url('/admin/orders')">
        Ver en Panel
    </x-mail::button>

    Gracias,<br>
    {{ config('app.name') }}
</x-mail::message>