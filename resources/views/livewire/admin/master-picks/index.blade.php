<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\MasterPick;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Mary\Traits\Toast;

new
    #[Layout('components.layouts.admin')]
    class extends Component {
    use Toast, WithFileUploads;

    // State
    public bool $drawer = false;
    public bool $deleteModal = false;
    public ?MasterPick $editingPick = null;
    public ?MasterPick $pickToDelete = null;

    // Form Properties
    #[Rule('required|exists:products,id')]
    public ?int $product_id = null;

    #[Rule('nullable|string|max:191')]
    public ?string $title = null;

    #[Rule('nullable|string|max:191')]
    public ?string $kicker = null;

    #[Rule('required|string|min:10')]
    public string $recommendation = '';

    #[Rule('boolean')]
    public bool $is_active = true;

    #[Rule('nullable|image|max:4096')]
    public $photo;

    // Helpers
    public function mount()
    {
        $this->editingPick = new MasterPick();
    }

    public function with()
    {
        return [
            'picks' => MasterPick::with('product')
                ->orderBy('order', 'asc')
                ->get(),
            'products' => Product::where('is_active', true)
                ->orderBy('name', 'asc')
                ->get()
                ->map(fn ($p) => ['id' => $p->id, 'name' => $p->name]),
        ];
    }

    // Actions
    public function create()
    {
        $this->reset(['title', 'kicker', 'recommendation', 'is_active', 'photo']);
        $this->product_id = null;
        $this->editingPick = new MasterPick();
        $this->drawer = true;
    }

    public function edit(MasterPick $pick)
    {
        $this->editingPick = $pick;

        $this->product_id = $pick->product_id;
        $this->title = $pick->title;
        $this->kicker = $pick->kicker;
        $this->recommendation = $pick->recommendation;
        $this->is_active = $pick->is_active;
        $this->photo = null;

        $this->drawer = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'product_id' => $this->product_id,
            'title' => $this->title,
            'kicker' => $this->kicker,
            'recommendation' => $this->recommendation,
            'is_active' => $this->is_active,
        ];

        // Handle image upload (imagen propia opcional)
        if ($this->photo) {
            if ($this->editingPick->exists && $this->editingPick->image_path) {
                Storage::disk('public')->delete($this->editingPick->image_path);
            }
            $data['image_path'] = $this->photo->store('master-picks', 'public');
        }

        if ($this->editingPick->exists) {
            $this->editingPick->update($data);
            $this->success('Selección actualizada correctamente.');
        } else {
            $maxOrder = MasterPick::max('order') ?? 0;
            $data['order'] = $maxOrder + 1;

            MasterPick::create($data);
            $this->success('Selección creada correctamente.');
        }

        $this->drawer = false;
    }

    public function confirmDelete(MasterPick $pick)
    {
        $this->pickToDelete = $pick;
        $this->deleteModal = true;
    }

    public function destroyPick()
    {
        if ($this->pickToDelete) {
            if ($this->pickToDelete->image_path) {
                Storage::disk('public')->delete($this->pickToDelete->image_path);
            }
            $this->pickToDelete->delete();
            $this->success('Selección eliminada.');
        }

        $this->deleteModal = false;
        $this->pickToDelete = null;
    }

    public function moveUp(MasterPick $pick)
    {
        $previous = MasterPick::where('order', '<', $pick->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previous) {
            $tmp = $pick->order;
            $pick->update(['order' => $previous->order]);
            $previous->update(['order' => $tmp]);
        }
    }

    public function moveDown(MasterPick $pick)
    {
        $next = MasterPick::where('order', '>', $pick->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($next) {
            $tmp = $pick->order;
            $pick->update(['order' => $next->order]);
            $next->update(['order' => $tmp]);
        }
    }
}; ?>

<div>
    {{-- HEADER --}}
    <x-mary-header title="Selección del Maestro" subtitle="Maridajes, recomendaciones y recetas curadas." separator>
        <x-slot:middle class="!justify-end">
             <x-mary-button label="Nuevo Maridaje" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:middle>
    </x-mary-header>

    {{-- PICKS LIST (Grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($picks as $pick)
            <x-mary-card wire:key="{{ $pick->id }}" class="!p-0 overflow-hidden">
                <figure class="aspect-[4/3] relative group">
                    @if($pick->displayImage())
                        <img src="{{ asset('storage/' . $pick->displayImage()) }}" alt="{{ $pick->displayTitle() }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110" />
                    @else
                        <div class="w-full h-full bg-base-200 flex items-center justify-center">
                            <x-mary-icon name="o-sparkles" class="w-10 h-10 opacity-40" />
                        </div>
                    @endif
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                         <x-mary-button icon="o-pencil" class="btn-circle btn-ghost text-white" wire:click="edit({{ $pick->id }})" />
                    </div>
                </figure>

                <div class="card-body p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            @if($pick->kicker)
                                <div class="text-[10px] uppercase tracking-widest text-primary opacity-70">{{ $pick->kicker }}</div>
                            @endif
                            <h2 class="card-title text-base">{{ $pick->displayTitle() }}</h2>
                        </div>
                        <x-mary-badge :value="$pick->is_active ? 'Activo' : 'Inactivo'" :class="$pick->is_active ? 'badge-success' : 'badge-error'" />
                    </div>
                    <p class="text-xs opacity-70 line-clamp-2">
                        <span class="text-primary">{{ $pick->product?->name }}</span> · {{ $pick->recommendation }}
                    </p>

                    <div class="card-actions justify-end mt-4 items-center gap-2">
                        <div class="join">
                             <x-mary-button icon="o-arrow-up" wire:click="moveUp({{ $pick->id }})" class="join-item btn-ghost btn-xs" />
                             <x-mary-button icon="o-arrow-down" wire:click="moveDown({{ $pick->id }})" class="join-item btn-ghost btn-xs" />
                        </div>
                        <x-mary-button icon="o-trash" wire:click="confirmDelete({{ $pick->id }})" class="btn-ghost btn-sm text-error" />
                    </div>
                </div>
            </x-mary-card>
        @empty
            <div class="col-span-full py-12 text-center opacity-50">
                 <x-mary-icon name="o-sparkles" class="w-12 h-12 mx-auto mb-2" />
                 <div>No hay maridajes creados todavía.</div>
            </div>
        @endforelse
    </div>

    {{-- DRAWER --}}
    <x-mary-drawer wire:model="drawer" title="{{ $editingPick->exists ? 'Editar Maridaje' : 'Nuevo Maridaje' }}" right
        class="w-11/12 lg:w-1/3">

        <x-mary-form wire:submit="save" class="mt-4 space-y-4">

            {{-- IMAGE UPLOAD (opcional) --}}
            <div class="flex flex-col items-center mb-6">
                <div
                    class="relative group cursor-pointer w-full h-48 rounded-xl overflow-hidden border-2 border-dashed border-base-300 hover:border-primary transition-all bg-base-200">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" />
                    @elseif($editingPick->image_path)
                        <img src="{{ asset('storage/' . $editingPick->image_path) }}" class="w-full h-full object-cover" />
                    @elseif($editingPick->exists && $editingPick->product?->image_path)
                        <img src="{{ asset('storage/' . $editingPick->product->image_path) }}" class="w-full h-full object-cover opacity-60" />
                        <div class="absolute bottom-2 left-2 text-[10px] uppercase tracking-widest bg-black/60 text-white px-2 py-1 rounded">Imagen del producto</div>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-500">
                            <x-mary-icon name="o-photo" class="w-10 h-10 mb-2 opacity-50" />
                            <span class="text-xs uppercase tracking-widest">Imagen propia (opcional)</span>
                        </div>
                    @endif

                    <label
                        class="absolute inset-0 cursor-pointer z-10 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-mary-icon name="o-camera" class="w-8 h-8 text-white" />
                        <input type="file" wire:model="photo" class="hidden" accept="image/*">
                    </label>
                </div>
                @error('photo') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <x-mary-select label="Producto del catálogo *" wire:model="product_id" :options="$products"
                option-value="id" option-label="name" placeholder="Elegí un producto..." />

            <div class="grid grid-cols-1 gap-4">
                <x-mary-input label="Título propio (opcional)" wire:model="title" placeholder="Si se deja vacío, usa el nombre del producto" />
                <x-mary-input label="Kicker / Etiqueta" wire:model="kicker" placeholder="Ej: Maridaje de Quesos" />
            </div>

            <x-mary-textarea label="Recomendación / Receta del Maestro *" wire:model="recommendation" rows="5" />

            <x-mary-toggle label="Activo" wire:model="is_active" class="toggle-primary border rounded-sm w-8" />

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.drawer = false"
                    class="btn-ghost text-white border border-white/10" />
                <x-mary-button label="Guardar" class="btn-primary" type="submit" spinner="save" icon="o-check" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>

    {{-- DELETE MODAL --}}
    <x-mary-modal wire:model="deleteModal" class="backdrop-blur-sm">
        <div class="mb-5">
            <h3 class="text-lg font-bold text-error">Eliminar Maridaje</h3>
            <p class="py-4 text-gray-500">¿Estás seguro que deseas eliminar la selección <span
                    class="font-bold">"{{ $pickToDelete?->displayTitle() }}"</span>? Esta acción no se puede
                deshacer.</p>
        </div>
        <x-slot:actions>
            <x-mary-button label="Cancelar" @click="$wire.deleteModal = false" />
            <x-mary-button label="Eliminar" wire:click="destroyPick" class="btn-error" />
        </x-slot:actions>
    </x-mary-modal>

</div>
