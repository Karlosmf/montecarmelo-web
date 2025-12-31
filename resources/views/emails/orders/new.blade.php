<x-mail::message>
    # Nuevo Pedido Recibido

    Se ha generado una nueva intención de compra en **Monte Carmelo**.

    **Cliente:** {{ $order->customer_name }}
    **Teléfono:** {{ $order->customer_phone ?? 'No proporcionado' }}
    **Total Estimado:** ${{ number_format($order->total / 100, 2) }}

    ## Resumen de Productos:
    @foreach($order->items as $item)
        - **{{ $item['name'] }}**
        ({{ $item['qty'] }}{{ $item['unit_type'] === 'kg' ? 'g' : ($item['unit_type'] === 'unit' ? ' un.' : '') }})
    @endforeach

    <x-mail::button :url="url('/admin/orders')">
        Ver Detalles en el Panel
    </x-mail::button>

    Gracias,<br>
    {{ config('app.name') }}
</x-mail::message>