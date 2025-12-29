<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use App\Models\Order;
use Mary\Traits\Toast;
use Illuminate\Database\Eloquent\Builder;

new 
#[Layout('components.layouts.admin')]
class extends Component {
    use Toast, WithPagination;

    // Filters
    public string $search = '';
    public string $statusFilter = '';

    // Drawer / Editing
    public bool $drawer = false;
    public Order $editingOrder;

    // Form Fields (Drawer)
    #[Rule('required')]
    public string $status = '';
    
    #[Rule('nullable')]
    public ?string $notes = '';

    public function mount()
    {
        $this->editingOrder = new Order();
    }

    public function with()
    {
        return [
            'orders' => Order::query()
                ->when($this->search, function (Builder $q) {
                    $q->where('customer_name', 'like', "%{$this->search}%")
                      ->orWhere('customer_phone', 'like', "%{$this->search}%");
                })
                ->when($this->statusFilter, fn(Builder $q) => $q->where('status', $this->statusFilter))
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ];
    }

    public function show(Order $order)
    {
        $this->editingOrder = $order;
        $this->status = $order->status;
        $this->notes = $order->notes;
        $this->drawer = true;
    }

    public function save()
    {
        $this->editingOrder->update([
            'status' => $this->status,
            'notes' => $this->notes
        ]);

        $this->success('Pedido actualizado.');
        $this->drawer = false;
    }

    public function getWhatsAppLink(Order $order)
    {
        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        return "https://wa.me/{$phone}";
    }
    
    public function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'badge-info',
            'contacted' => 'badge-warning',
            'completed' => 'badge-success',
            'cancelled' => 'badge-error',
            default => 'badge-ghost',
        };
    }

    public function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Nuevo',
            'contacted' => 'Contactado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => $status,
        };
    }
}; ?>

<div>
    <x-mary-header title="Pedidos" subtitle="Gestiona las intenciones de compra" separator />

    {{-- FILTERS --}}
    <div class="flex gap-4 mb-4 items-center">
        <x-mary-input placeholder="Buscar cliente..." wire:model.live.debounce="search" icon="o-magnifying-glass" class="w-full md:w-1/3" />
        
        <x-mary-select 
            placeholder="Estado" 
            :options="[
                ['id' => 'pending', 'name' => 'Nuevo'],
                ['id' => 'contacted', 'name' => 'Contactado'],
                ['id' => 'completed', 'name' => 'Completado'],
                ['id' => 'cancelled', 'name' => 'Cancelado']
            ]" 
            wire:model.live="statusFilter" 
            class="w-full md:w-1/4" 
        />
    </div>

    {{-- TABLE --}}
    <x-mary-card>
        <x-mary-table 
            :rows="$orders" 
            :headers="[
                ['key' => 'created_at', 'label' => 'Fecha'],
                ['key' => 'customer_name', 'label' => 'Cliente'],
                ['key' => 'total', 'label' => 'Total'],
                ['key' => 'status', 'label' => 'Estado'],
                ['key' => 'actions', 'label' => 'Acciones']
            ]"
            with-pagination
        >
            @scope('created_at', $order)
                {{ $order->created_at->format('d/m H:i') }}
            @endscope

            @scope('customer_name', $order)
                <div class="font-bold">{{ $order->customer_name }}</div>
                <div class="text-xs text-gray-400">{{ $order->customer_phone }}</div>
            @endscope

            @scope('total', $order)
                <span class="font-mono text-primary font-bold">
                    ${{ number_format($order->total / 100, 2) }}
                </span>
            @endscope

            @scope('status', $order)
                <div class="badge {{ $this->getStatusColor($order->status) }}">
                    {{ $this->getStatusLabel($order->status) }}
                </div>
            @endscope

            @scope('actions', $order)
                <div class="flex gap-2">
                    @if($order->customer_phone)
                        <x-mary-button 
                            icon="o-chat-bubble-left-right" 
                            link="{{ $this->getWhatsAppLink($order) }}" 
                            external 
                            class="btn-ghost btn-sm text-success" 
                            tooltip="WhatsApp"
                        />
                    @endif
                    <x-mary-button icon="o-eye" wire:click="show({{ $order->id }})" class="btn-ghost btn-sm" tooltip="Ver Detalles" />
                </div>
            @endscope
        </x-mary-table>
    </x-mary-card>

    {{-- DRAWER DETAILS --}}
    <x-mary-drawer wire:model="drawer" title="Detalle del Pedido #{{ $editingOrder->id }}" right class="w-11/12 lg:w-1/3">
        @if($editingOrder->id)
            <div class="space-y-6">
                
                {{-- CLIENT INFO --}}
                <div class="bg-base-200 p-4 rounded-lg">
                    <div class="font-bold text-lg">{{ $editingOrder->customer_name }}</div>
                    <div class="flex items-center gap-2 text-sm opacity-70">
                        <x-mary-icon name="o-phone" class="w-4 h-4"/> {{ $editingOrder->customer_phone }}
                    </div>
                    <div class="flex items-center gap-2 text-sm opacity-70 mt-1">
                        <x-mary-icon name="o-calendar" class="w-4 h-4"/> {{ $editingOrder->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                {{-- STATUS EDIT --}}
                <x-mary-form wire:submit="save">
                    <x-mary-select 
                        label="Estado del Pedido" 
                        :options="[
                            ['id' => 'pending', 'name' => 'Nuevo'],
                            ['id' => 'contacted', 'name' => 'Contactado'],
                            ['id' => 'completed', 'name' => 'Completado'],
                            ['id' => 'cancelled', 'name' => 'Cancelado']
                        ]" 
                        wire:model="status" 
                    />

                    <x-mary-textarea label="Notas Internas" wire:model="notes" placeholder="Ej: Entregar el viernes..." rows="3" />
                    
                    <div class="flex justify-end mt-2">
                        <x-mary-button label="Guardar Cambios" class="btn-primary btn-sm" type="submit" spinner="save" />
                    </div>
                </x-mary-form>

                {{-- ITEMS LIST --}}
                <div>
                    <h3 class="font-bold text-sm uppercase tracking-wider mb-3 border-b pb-1">Productos Solicitados</h3>
                    <div class="space-y-3">
                        @foreach($editingOrder->items as $item)
                            <div class="flex items-center gap-3 p-2 hover:bg-base-200 rounded transition">
                                {{-- Placeholder Icon or Image if we had it in JSON --}}
                                <div class="w-10 h-10 bg-base-300 rounded flex items-center justify-center text-gray-500">
                                    <x-mary-icon name="o-shopping-bag" class="w-5 h-5"/>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="font-bold text-sm">{{ $item['name'] }}</div>
                                    <div class="text-xs opacity-70">
                                        {{-- Handle unit type display if available in JSON --}}
                                        Cant: {{ $item['qty'] }} 
                                        @if(isset($item['unit_type']) && $item['unit_type'] == 'kg') g @elseif(isset($item['unit_type']) && $item['unit_type'] == 'unit') u. @endif
                                    </div>
                                </div>

                                <div class="font-mono text-sm">
                                    ${{ number_format(($item['subtotal'] ?? 0) / 100, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center mt-6 pt-4 border-t border-dashed">
                        <span class="font-bold">TOTAL</span>
                        <span class="font-mono text-xl text-primary font-bold">
                             ${{ number_format($editingOrder->total / 100, 2) }}
                        </span>
                    </div>
                </div>

            </div>
        @endif
        
        <x-slot:actions>
            <x-mary-button label="Cerrar" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-mary-drawer>
</div>
