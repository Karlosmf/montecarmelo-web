<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Confirm;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

new
    #[Layout('components.layouts.admin')]
    class extends Component {
    use Toast, WithFileUploads, WithPagination;

    // Filters
    public string $search = '';
    public $categoryFilter = '';
    public $activeFilter = '';

    // Drawer / Editing
    public bool $drawer = false;
    public $editingId = null;

    // Form Fields
    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public string $slug = '';

    #[Rule('nullable')]
    public string $description = '';

    #[Rule('required|numeric|min:0')]
    public $price = ''; // Visual price (float)

    #[Rule('required')]
    public string $unit_type = 'kg';

    #[Rule('required|exists:categories,id')]
    public $category_id = null;

    #[Rule('array')]
    public array $tags = []; // Selected tag IDs

    #[Rule('nullable|image|max:2048')]
    public $image;

    public bool $is_active = true;
    public bool $is_featured = false;

    // Data Sources
    public $categories = [];
    public $allTags = [];

    public function mount()
    {
        $this->categories = Category::all();
        $this->allTags = Tag::all();
    }

    public function with()
    {
        return [
            'products' => Product::query()
                ->with(['category'])
                ->when($this->search, fn(Builder $q) => $q->where('name', 'like', "%{$this->search}%"))
                ->when($this->categoryFilter, fn(Builder $q) => $q->where('category_id', $this->categoryFilter))
                ->when($this->activeFilter !== '', fn(Builder $q) => $q->where('is_active', $this->activeFilter))
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->drawer = true;
    }

    public function edit(Product $product)
    {
        $this->resetForm();
        $this->editingId = $product->id;

        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->description = $product->description ?? '';
        $this->price = $product->price / 100; // Convert cents to float
        $this->unit_type = $product->unit_type;
        $this->category_id = $product->category_id;
        $this->is_active = $product->is_active;
        $this->is_featured = $product->is_featured;

        $this->tags = $product->tags()->pluck('id')->toArray();
        $this->image = null; // Reset file input

        $this->drawer = true;
    }

    public function updatedName()
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'description' => $this->description,
            'price' => (int) ($this->price * 100), // Convert back to cents
            'unit_type' => $this->unit_type,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('products', 'public');
        }

        if ($this->editingId) {
            $product = Product::find($this->editingId);
            $product->update($data);
            $product->tags()->sync($this->tags);
            $this->success('Producto actualizado.');
        } else {
            $product = Product::create($data);
            $product->tags()->attach($this->tags);
            $this->success('Producto creado.');
        }

        $this->drawer = false;
    }

    public function toggleActive($id)
    {
        $product = Product::find($id);
        $product->update(['is_active' => !$product->is_active]);
        $this->success('Estado actualizado.');
    }

    #[Confirm('¿Estás seguro de eliminar este producto?')]
    public function delete($id)
    {
        Product::destroy($id);
        $this->success('Producto eliminado.');
    }

    private function resetForm()
    {
        $this->reset(['name', 'slug', 'description', 'price', 'unit_type', 'category_id', 'tags', 'image', 'editingId', 'is_active', 'is_featured']);
        $this->is_active = true;
        $this->unit_type = 'kg';
    }
}; ?>

<div>
    <x-mary-header title="Productos" subtitle="Administra el catálogo completo" separator>
        <x-slot:middle class="!justify-end">
            <x-mary-button icon="o-plus" class="btn-primary" label="Nuevo Producto" wire:click="create" />
        </x-slot:middle>
    </x-mary-header>

    {{-- FILTERS --}}
    <div class="flex flex-col md:flex-row gap-4 mb-4 items-center">
        <x-mary-input placeholder="Buscar..." wire:model.live.debounce="search" icon="o-magnifying-glass"
            class="w-full md:flex-1" />

        <x-mary-select placeholder="Categoría" :options="$categories" option-value="id" option-label="name"
            wire:model.live="categoryFilter" class="w-full md:w-64" />

        <x-mary-select placeholder="Estado" :options="[['id' => '1', 'name' => 'Activo'], ['id' => '0', 'name' => 'Inactivo']]" wire:model.live="activeFilter" class="w-full md:w-48" />
    </div>

    {{-- TABLE --}}
    <x-mary-card>
        <x-mary-table :rows="$products" :headers="[
        ['key' => 'image_path', 'label' => 'Foto', 'sortable' => false],
        ['key' => 'name', 'label' => 'Nombre', 'sortable' => true],
        ['key' => 'category.name', 'label' => 'Categoría'],
        ['key' => 'price', 'label' => 'Precio'],
        ['key' => 'is_active', 'label' => 'Estado'],
        ['key' => 'actions', 'label' => 'Acciones']
    ]" with-pagination>
            @scope('image_path', $product)
            @if($product->image_path)
                <div class="avatar">
                    <div class="w-10 rounded">
                        <img src="{{ asset('storage/' . $product->image_path) }}" />
                    </div>
                </div>
            @else
                <div class="avatar placeholder">
                    <div class="bg-neutral text-neutral-content rounded w-10">
                        <span class="text-xs">{{ substr($product->name, 0, 2) }}</span>
                    </div>
                </div>
            @endif
            @endscope

            @scope('price', $product)
            ${{ number_format($product->price / 100, 2) }}
            <span class="text-xs text-gray-400">/ {{ $product->unit_type }}</span>
            @endscope

            @scope('is_active', $product)
            <x-mary-toggle wire:click="toggleActive({{ $product->id }})"
                wire:model="products.{{ $loop->index }}.is_active" class="toggle-success toggle-sm"
                checked="{{ $product->is_active }}" />
            @endscope

            @scope('actions', $product)
            <div class="flex">
                <x-mary-button icon="o-pencil" wire:click="edit({{ $product->id }})"
                    class="btn-ghost btn-sm text-info" />
                <x-mary-button icon="o-trash" wire:click="delete({{ $product->id }})"
                    class="btn-ghost btn-sm text-error"
                    onclick="return confirm('¿Seguro?') || event.stopImmediatePropagation()" />
            </div>
            @endscope
        </x-mary-table>
    </x-mary-card>

    {{-- DRAWER FORM --}}
    <x-mary-drawer wire:model="drawer" title="{{ $editingId ? 'Editar Producto' : 'Nuevo Producto' }}" right
        class="w-11/12 lg:w-1/2">
        <x-mary-form wire:submit="save">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-mary-input label="Nombre" wire:model.live="name" />
                <x-mary-input label="Slug" wire:model="slug" />
            </div>

            <x-mary-textarea label="Descripción" wire:model="description" rows="3" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-mary-select label="Categoría" :options="$categories" option-value="id" option-label="name"
                    wire:model="category_id" />

                {{-- Tags Multi-Select using Choices --}}
                <x-mary-choices label="Etiquetas" wire:model="tags" :options="$allTags" option-value="id"
                    option-label="name" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                <x-mary-input label="Precio ($)" wire:model="price" type="number" step="0.01" prefix="$" />

                <x-mary-select label="Unidad de Medida" :options="[['id' => 'kg', 'name' => 'Kilogramo (kg)'], ['id' => 'unit', 'name' => 'Unidad (u)'], ['id' => 'pack', 'name' => 'Pack']]" wire:model="unit_type" />
            </div>

            <x-mary-file wire:model="image" label="Imagen Principal" accept="image/*" crop-after-change>
                <img src="{{ $image ?? '' }}" class="h-40 rounded-lg" />
            </x-mary-file>

            <div class="flex gap-8 mt-4">
                <x-mary-checkbox label="Activo en Catálogo" wire:model="is_active" />
                <x-mary-checkbox label="Destacado (Home)" wire:model="is_featured" />
            </div>

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.drawer = false" />
                <x-mary-button label="Guardar" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>
</div>