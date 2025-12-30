<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Mary\Traits\Toast;
use Illuminate\Database\Eloquent\Builder;

new 
#[Layout('components.layouts.admin')]
class extends Component {
    use Toast, WithPagination, WithFileUploads;

    // State
    public bool $drawer = false;
    public string $search = '';
    public int $categoryFilter = 0;

    // Form Properties
    public ?Product $editingProduct = null;
    
    #[Rule('required|min:3')]
    public string $name = '';
    
    #[Rule('nullable')]
    public string $description = '';
    
    #[Rule('required|numeric|min:0')]
    public float $price = 0.00; // Proxy for converting to cents
    
    #[Rule('required|exists:categories,id')]
    public ?int $category_id = null;
    
    #[Rule('required|in:kg,unit,pack')]
    public string $unit_type = 'unit';
    
    #[Rule('boolean')]
    public bool $is_active = true;

    #[Rule('nullable|image|max:2048')] // 2MB Max
    public $photo;

    // Helpers
    public function mount()
    {
        $this->editingProduct = new Product();
    }

    public function with()
    {
        return [
            'products' => Product::query()
                ->with('category')
                ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%{$this->search}%"))
                ->when($this->categoryFilter, fn(Builder $q) => $q->where('category_id', $this->categoryFilter))
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'categories' => Category::all()
        ];
    }

    // Actions
    public function create()
    {
        $this->reset(['name', 'description', 'price', 'category_id', 'unit_type', 'is_active', 'photo']);
        $this->editingProduct = new Product();
        $this->drawer = true;
    }

    public function edit(Product $product)
    {
        $this->editingProduct = $product;
        
        // Load data
        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->price = $product->price / 100; // Convert cents to decimal for UI
        $this->category_id = $product->category_id;
        $this->unit_type = $product->unit_type;
        $this->is_active = $product->is_active;
        $this->photo = null; // Reset file input

        $this->drawer = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => (int) ($this->price * 100), // Convert decimal to cents
            'category_id' => $this->category_id,
            'unit_type' => $this->unit_type,
            'is_active' => $this->is_active,
        ];

        // Handle Image Upload
        if ($this->photo) {
            // Delete old image if exists and we are editing
            if ($this->editingProduct->exists && $this->editingProduct->image_path) {
                Storage::disk('public')->delete($this->editingProduct->image_path);
            }
            
            // Store new
            $data['image_path'] = $this->photo->store('products', 'public');
        }

        if ($this->editingProduct->exists) {
            $this->editingProduct->update($data);
            $this->success('Producto actualizado correctamente.');
        } else {
            Product::create($data);
            $this->success('Producto creado correctamente.');
        }

        $this->drawer = false;
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $this->success($product->is_active ? 'Producto activado.' : 'Producto desactivado.');
    }

    public function delete(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        $this->success('Producto eliminado.');
    }
}; ?>

<div>
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="heading-modern text-3xl">Catálogo de Productos</h1>
            <p class="text-text-muted mt-1 font-light tracking-wide">Gestiona el inventario, precios e imágenes.</p>
        </div>
        <x-mary-button label="Nuevo Producto" icon="o-plus" class="btn-primary font-serif tracking-widest" wire:click="create" />
    </div>

    {{-- FILTERS --}}
    <div class="glass-panel p-4 rounded-xl mb-6 flex flex-col md:flex-row gap-4">
        <div class="w-full md:w-1/3 relative">
            <x-mary-icon name="o-magnifying-glass" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" />
            <input 
                type="text" 
                wire:model.live.debounce="search" 
                placeholder="Buscar producto..." 
                class="bg-[#121212]/50 border border-white/10 rounded-lg py-2 pl-10 pr-4 w-full text-white placeholder-gray-600 focus:border-primary focus:ring-0 transition-all"
            >
        </div>
        
        <select wire:model.live="categoryFilter" class="bg-[#121212]/50 border border-white/10 rounded-lg py-2 px-4 text-white focus:border-primary focus:ring-0 cursor-pointer w-full md:w-1/4">
            <option value="0">Todas las Categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- PRODUCTS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($products as $product)
            <div wire:key="{{ $product->id }}" class="card-modern group flex flex-col h-full">
                
                {{-- Image Area --}}
                <div class="relative h-48 w-full overflow-hidden border-b border-white/5">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    @else
                        <div class="w-full h-full bg-[#121212] flex flex-col items-center justify-center text-gray-600">
                            <x-mary-icon name="o-photo" class="w-8 h-8 opacity-50 mb-1"/>
                            <span class="text-[10px] uppercase tracking-widest opacity-50">Sin Foto</span>
                        </div>
                    @endif
                    
                    {{-- Status Badge (Absolute) --}}
                    <div class="absolute top-3 right-3">
                        <button wire:click="toggleStatus({{ $product->id }})" class="backdrop-blur-md bg-black/40 border border-white/10 px-2 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider hover:bg-white/20 transition-colors {{ $product->is_active ? 'text-success border-success/30' : 'text-error border-error/30' }}">
                            {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                        </button>
                    </div>

                    {{-- Category Badge (Absolute) --}}
                    <div class="absolute bottom-3 left-3">
                         @if($product->category)
                            <span class="backdrop-blur-md bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/30 px-2 py-1 rounded text-[10px] uppercase font-bold tracking-wider shadow-lg">
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Content Area --}}
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-serif text-lg text-text-main leading-tight group-hover:text-primary transition-colors">
                            {{ $product->name }}
                        </h3>
                    </div>
                    
                    <p class="text-xs text-text-muted mb-4 line-clamp-2 flex-1">{{ $product->description }}</p>

                    <div class="flex items-end justify-between mt-auto pt-4 border-t border-white/5">
                        <div class="font-mono text-xl text-primary font-bold">
                            ${{ number_format($product->price / 100, 2) }}
                            <span class="text-[10px] text-text-muted font-sans font-normal lowercase align-middle">/ {{ $product->unit_type }}</span>
                        </div>

                        <div class="flex gap-2">
                            <x-mary-button icon="o-pencil" wire:click="edit({{ $product->id }})" class="btn-ghost btn-sm text-white hover:text-primary hover:bg-primary/10 px-2" />
                            <x-mary-button icon="o-trash" wire:click="delete({{ $product->id }})" class="btn-ghost btn-sm text-error/50 hover:text-error hover:bg-error/10 px-2" wire:confirm="¿Eliminar este producto?" />
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center p-12 glass-panel rounded-xl border-dashed border-2 border-white/10">
                <x-mary-icon name="o-cube" class="w-12 h-12 text-gray-600 mb-4" />
                <div class="text-text-muted text-lg font-serif">No hay productos encontrados</div>
                <div class="text-gray-600 text-sm mb-4">Prueba ajustar los filtros o crea uno nuevo.</div>
                <x-mary-button label="Crear Producto" icon="o-plus" class="btn-primary btn-sm" wire:click="create" />
            </div>
        @endforelse
    </div>

    <div class="mb-8">
        {{ $products->links() }}
    </div>

    {{-- DRAWER --}}
    <x-mary-drawer wire:model="drawer" title="{{ $editingProduct->exists ? 'Editar Producto' : 'Nuevo Producto' }}" right class="w-11/12 lg:w-1/3 glass-dark !bg-[#121212]/95 backdrop-blur-xl border-l border-white/10">
        
        <x-mary-form wire:submit="save" class="mt-4 space-y-4">
            
            {{-- IMAGE UPLOAD --}}
            <div class="flex flex-col items-center mb-8">
                <div class="relative group cursor-pointer w-40 h-40">
                    
                    {{-- Container Frame --}}
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#D4AF37]/20 to-transparent rounded-2xl blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    
                    <div class="relative w-full h-full rounded-2xl overflow-hidden border-2 border-white/10 group-hover:border-primary/50 transition-all duration-300 shadow-2xl bg-[#121212]">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-transform duration-700 ease-out" />
                        @elseif($editingProduct->image_path)
                            <img src="{{ asset('storage/' . $editingProduct->image_path) }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-transform duration-700 ease-out" />
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-white/5 text-gray-500 group-hover:text-primary transition-colors">
                                <x-mary-icon name="o-photo" class="w-10 h-10 mb-2 opacity-50 group-hover:opacity-80 transition-opacity"/>
                                <span class="text-[10px] uppercase tracking-widest opacity-50 font-serif">Subir Foto</span>
                            </div>
                        @endif

                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-[2px]">
                            <x-mary-icon name="o-camera" class="w-8 h-8 text-white drop-shadow-lg transform scale-50 group-hover:scale-100 transition-transform duration-300 delay-75" />
                        </div>
                    </div>

                    {{-- Hidden Input --}}
                    <label class="absolute inset-0 cursor-pointer z-10">
                        <input type="file" wire:model="photo" class="hidden" accept="image/*">
                    </label>
                </div>
                
                @if($photo)
                   <div class="mt-3 text-xs text-primary font-bold tracking-wide animate-pulse">Nueva imagen seleccionada</div> 
                @endif
            </div>

            {{-- BASIC INFO --}}
            <div class="glass-panel p-4 rounded-xl border border-white/5 space-y-4">
                <x-mary-input label="Nombre" wire:model="name" class="bg-[#121212] border-white/10 focus:border-primary" />
                <x-mary-textarea label="Descripción" wire:model="description" rows="2" class="bg-[#121212] border-white/10 focus:border-primary" />
            </div>

            {{-- PRICING & CONFIG --}}
            <div class="glass-panel p-4 rounded-xl border border-white/5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <x-mary-input label="Precio" prefix="$" wire:model="price" type="number" step="0.01" class="bg-[#121212] border-white/10 focus:border-primary font-mono text-primary" />
                    
                    <x-mary-select label="Unidad" wire:model="unit_type" :options="[['id'=>'kg','name'=>'Kilogramo'], ['id'=>'unit','name'=>'Unidad'], ['id'=>'pack','name'=>'Pack']]" class="bg-[#121212] border-white/10 focus:border-primary" />
                </div>

                <x-mary-select label="Categoría" wire:model="category_id" :options="$categories" option-label="name" option-value="id" placeholder="Seleccionar..." class="bg-[#121212] border-white/10 focus:border-primary" />
                
                <x-mary-toggle label="Producto Activo" wire:model="is_active" class="toggle-primary" right />
            </div>

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.drawer = false" class="btn-ghost" />
                <x-mary-button label="Guardar" class="btn-primary" type="submit" spinner="save" icon="o-check" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>
</div>
