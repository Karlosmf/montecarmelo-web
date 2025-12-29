<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use App\Models\Category;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

new 
#[Layout('components.layouts.admin')]
class extends Component {
    use Toast, WithFileUploads;

    public $categories = [];
    public bool $drawer = false;
    public $editingId = null;

    // Form
    #[Rule('required')]
    public string $name = '';
    
    #[Rule('nullable')]
    public string $slug = '';
    
    #[Rule('nullable')]
    public string $color = '';

    #[Rule('nullable|image|max:1024')]
    public $image;

    public function mount()
    {
        $this->refresh();
    }

    public function refresh()
    {
        $this->categories = Category::withCount('products')->get();
    }

    public function create()
    {
        $this->reset(['name', 'slug', 'color', 'image', 'editingId']);
        $this->drawer = true;
    }

    public function edit(Category $category)
    {
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->color = $category->color;
        $this->image = null; // Don't preload existing image into file input
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
            'color' => $this->color,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('categories', 'public');
        }

        if ($this->editingId) {
            Category::find($this->editingId)->update($data);
            $this->success('Categoría actualizada.');
        } else {
            Category::create($data);
            $this->success('Categoría creada.');
        }

        $this->drawer = false;
        $this->refresh();
    }

    public function delete($id)
    {
        Category::destroy($id);
        $this->refresh();
        $this->success('Categoría eliminada.');
    }
}; ?>

<div>
    <x-mary-header title="Categorías" subtitle="Gestiona las categorías de productos" separator>
        <x-slot:middle class="!justify-end">
            <x-mary-button icon="o-plus" class="btn-primary" label="Nueva Categoría" wire:click="create" />
        </x-slot:middle>
    </x-mary-header>

    <x-mary-card>
        <x-mary-table :rows="$categories" :headers="[['key' => 'id', 'label' => '#'], ['key' => 'image_path', 'label' => 'Imagen'], ['key' => 'name', 'label' => 'Nombre'], ['key' => 'products_count', 'label' => 'Productos'], ['key' => 'actions', 'label' => 'Acciones']]" striped>
            @scope('image_path', $category)
                @if($category->image_path)
                    <div class="avatar">
                        <div class="w-10 rounded">
                            <img src="{{ asset('storage/' . $category->image_path) }}" />
                        </div>
                    </div>
                @else
                    <x-mary-icon name="o-photo" class="w-8 h-8 text-gray-300" />
                @endif
            @endscope

            @scope('name', $category)
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full {{ $category->color }}"></div>
                    {{ $category->name }}
                </div>
            @endscope

            @scope('actions', $category)
                <div class="flex">
                    <x-mary-button icon="o-pencil" wire:click="edit({{ $category->id }})" class="btn-ghost btn-sm text-info" />
                    <x-mary-button icon="o-trash" wire:click="delete({{ $category->id }})" class="btn-ghost btn-sm text-error" onclick="return confirm('¿Seguro?') || event.stopImmediatePropagation()" />
                </div>
            @endscope
        </x-mary-table>
    </x-mary-card>

    <x-mary-drawer wire:model="drawer" title="{{ $editingId ? 'Editar Categoría' : 'Nueva Categoría' }}" right class="w-11/12 lg:w-1/3">
        <x-mary-form wire:submit="save">
            <x-mary-input label="Nombre" wire:model.live="name" />
            <x-mary-input label="Slug" wire:model="slug" />
            <x-mary-input label="Clases de Color (Tailwind)" wire:model="color" hint="Ej: bg-red-100 text-red-800" />
            
            <x-mary-file wire:model="image" label="Imagen" accept="image/*" crop-after-change>
                <img src="{{ $image ?? '' }}" class="h-40 rounded-lg" />
            </x-mary-file>

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.drawer = false" />
                <x-mary-button label="Guardar" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>
</div>
