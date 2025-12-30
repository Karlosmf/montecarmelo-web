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
                ->paginate(10),
            'pendingCount' => Order::where('status', 'pending')->count(),
            'todayCount' => Order::whereDate('created_at', now())->count(),
            'monthTotal' => Order::whereMonth('created_at', now()->month)->sum('total')
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
    
    public function getStatusClasses($status)
    {
        return match ($status) {
            'pending' => 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
            'contacted' => 'bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/30',
            'completed' => 'bg-green-500/20 text-green-300 border border-green-500/30',
            'cancelled' => 'bg-red-500/20 text-red-300 border border-red-500/30',
            default => 'bg-gray-500/20 text-gray-300 border border-gray-500/30',
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
    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="heading-modern text-3xl">Gestión de Pedidos</h1>
        <p class="text-text-muted mt-1 font-light tracking-wide">Monitorea tus oportunidades de venta en tiempo real.</p>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pendientes -->
        <div class="card-modern p-6">
            <div class="text-text-muted text-xs uppercase tracking-widest font-bold mb-2">Pendientes</div>
            <div class="text-4xl font-serif font-bold text-gradient-gold">
                {{ $pendingCount }}
            </div>
            <div class="text-xs text-white/40 mt-1">Requieren atención</div>
        </div>

        <!-- Hoy -->
        <div class="card-modern p-6">
            <div class="text-text-muted text-xs uppercase tracking-widest font-bold mb-2">Nuevos Hoy</div>
            <div class="text-4xl font-serif font-bold text-white">
                {{ $todayCount }}
            </div>
            <div class="text-xs text-white/40 mt-1">Oportunidades del día</div>
        </div>

        <!-- Total Mes -->
        <div class="card-modern p-6">
            <div class="text-text-muted text-xs uppercase tracking-widest font-bold mb-2">Total Estimado Mes</div>
            <div class="text-4xl font-serif font-bold text-primary drop-shadow-sm">
                ${{ number_format($monthTotal / 100, 0, ',', '.') }}
            </div>
            <div class="text-xs text-white/40 mt-1">Volumen bruto de pedidos</div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-col md:flex-row gap-4 mb-6 items-center justify-between">
        <div class="w-full md:w-1/3 relative">
            <x-mary-icon name="o-magnifying-glass" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" />
            <input 
                type="text" 
                wire:model.live.debounce="search" 
                placeholder="Buscar cliente..." 
                class="bg-[#121212]/50 border border-white/10 rounded-full py-2 pl-10 pr-4 w-full text-white placeholder-gray-600 focus:border-primary focus:ring-0 transition-all"
            >
        </div>
        
        <select wire:model.live="statusFilter" class="bg-[#121212]/50 border border-white/10 rounded-full py-2 px-4 text-white focus:border-primary focus:ring-0 cursor-pointer w-full md:w-auto">
            <option value="">Todos los Estados</option>
            <option value="pending">Nuevo</option>
            <option value="contacted">Contactado</option>
            <option value="completed">Completado</option>
            <option value="cancelled">Cancelado</option>
        </select>
    </div>

    {{-- TABLE --}}
    <div class="glass-panel p-1 rounded-xl overflow-hidden">
        <x-mary-table 
            :rows="$orders" 
            :headers="[
                ['key' => 'created_at', 'label' => 'Fecha'],
                ['key' => 'customer_name', 'label' => 'Cliente'],
                ['key' => 'total', 'label' => 'Total'],
                ['key' => 'status', 'label' => 'Estado'],
                ['key' => 'actions', 'label' => 'Acciones']
            ]"
            class="table-glass"
            with-pagination
        >
            @scope('created_at', $order)
                <span class="opacity-70 font-mono text-xs">{{ $order->created_at->format('d/m H:i') }}</span>
            @endscope

            @scope('customer_name', $order)
                <div class="font-bold text-text-main">{{ $order->customer_name }}</div>
                <div class="text-xs text-text-muted">{{ $order->customer_phone }}</div>
            @endscope

            @scope('total', $order)
                <span class="font-mono text-primary font-bold">
                    ${{ number_format($order->total / 100, 2) }}
                </span>
            @endscope

            @scope('status', $order)
                <div class="px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider inline-flex items-center gap-1 {{ $this->getStatusClasses($order->status) }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                    {{ $this->getStatusLabel($order->status) }}
                </div>
            @endscope

            @scope('actions', $order)
                <div class="flex gap-2 justify-end">
                    @if($order->customer_phone)
                        <x-mary-button 
                            icon="o-chat-bubble-left-right" 
                            link="{{ $this->getWhatsAppLink($order) }}" 
                            external 
                            class="btn-ghost btn-sm text-success hover:bg-success/10" 
                            tooltip="WhatsApp"
                        />
                    @endif
                    <x-mary-button icon="o-eye" wire:click="show({{ $order->id }})" class="btn-ghost btn-sm text-primary hover:bg-primary/10" tooltip="Ver Detalles" />
                </div>
            @endscope
        </x-mary-table>
    </div>

    {{-- DRAWER DETAILS --}}
    <x-mary-drawer wire:model="drawer" title="" right class="w-11/12 lg:w-1/3 glass-dark !bg-[#121212]/90 backdrop-blur-xl border-l border-white/10">
        @if($editingOrder->id)
            <div class="pt-6 space-y-8">
                
                {{-- DRAWER HEADER --}}
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div>
                         <div class="text-xs text-text-muted uppercase tracking-widest">Pedido #{{ $editingOrder->id }}</div>
                         <h2 class="font-serif text-2xl text-primary mt-1">{{ $editingOrder->customer_name }}</h2>
                    </div>
                    <div class="px-3 py-1 rounded-full text-xs font-bold border {{ $this->getStatusClasses($editingOrder->status) }}">
                        {{ $this->getStatusLabel($editingOrder->status) }}
                    </div>
                </div>

                {{-- INFO GRID --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="glass-panel p-3 rounded-lg border border-white/5">
                        <div class="text-[10px] text-text-muted uppercase tracking-wider mb-1">Teléfono</div>
                        <div class="flex items-center gap-2 text-sm text-white">
                            <x-mary-icon name="o-phone" class="w-4 h-4 text-primary"/> {{ $editingOrder->customer_phone }}
                        </div>
                    </div>
                    <div class="glass-panel p-3 rounded-lg border border-white/5">
                        <div class="text-[10px] text-text-muted uppercase tracking-wider mb-1">Fecha</div>
                        <div class="flex items-center gap-2 text-sm text-white">
                            <x-mary-icon name="o-calendar" class="w-4 h-4 text-primary"/> {{ $editingOrder->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>

                {{-- STATUS EDIT --}}
                <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                    <h3 class="text-xs text-primary uppercase tracking-widest font-bold mb-4">Gestión</h3>
                    <x-mary-form wire:submit="save">
                        <x-mary-select 
                            label="Actualizar Estado" 
                            :options="[
                                ['id' => 'pending', 'name' => 'Nuevo'],
                                ['id' => 'contacted', 'name' => 'Contactado'],
                                ['id' => 'completed', 'name' => 'Completado'],
                                ['id' => 'cancelled', 'name' => 'Cancelado']
                            ]" 
                            wire:model="status" 
                            class="bg-[#121212] border-white/10 text-sm"
                        />

                        <x-mary-textarea label="Notas Internas" wire:model="notes" placeholder="Detalles de entrega, pagos, etc..." rows="3" class="bg-[#121212] border-white/10 text-sm mt-2" />
                        
                        <div class="flex justify-end mt-4">
                            <x-mary-button label="Guardar Cambios" class="btn-primary btn-sm w-full font-serif uppercase tracking-widest" type="submit" spinner="save" />
                        </div>
                    </x-mary-form>
                </div>

                {{-- ITEMS LIST --}}
                <div>
                    <h3 class="font-serif text-lg text-white border-b border-white/10 pb-2 mb-4">Items del Pedido</h3>
                    <div class="space-y-3">
                        @foreach($editingOrder->items as $item)
                            <div class="flex items-start gap-4 p-3 rounded-xl bg-white/5 border border-white/5 hover:border-primary/30 transition-colors">
                                <div class="w-12 h-12 bg-[#121212] rounded-lg flex items-center justify-center text-primary border border-white/5">
                                    <x-mary-icon name="o-shopping-bag" class="w-6 h-6"/>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="font-serif text-white text-lg leading-tight">{{ $item['name'] }}</div>
                                    <div class="text-xs text-text-muted mt-1">
                                        {{ $item['qty'] }} 
                                        @if(isset($item['unit_type']) && $item['unit_type'] == 'kg') kg @elseif(isset($item['unit_type']) && $item['unit_type'] == 'unit') unidades @endif
                                        &times; ${{ number_format(($item['price'] ?? 0) / 100, 2) }}
                                    </div>
                                </div>

                                <div class="font-mono text-primary font-bold">
                                    ${{ number_format(($item['subtotal'] ?? 0) / 100, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center mt-8 p-4 bg-primary/10 rounded-xl border border-primary/20">
                        <span class="font-serif text-sm uppercase tracking-widest text-primary">Total Final</span>
                        <span class="font-mono text-3xl text-primary font-bold drop-shadow-lg">
                             ${{ number_format($editingOrder->total / 100, 2) }}
                        </span>
                    </div>
                </div>

            </div>
        @endif

        <x-slot:actions>
            <x-mary-button label="Cerrar" @click="$wire.drawer = false" class="btn-ghost" />
        </x-slot:actions>
    </x-mary-drawer>
</div>
