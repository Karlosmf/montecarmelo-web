<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\GalleryImage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Mary\Traits\Toast;

new
    #[Layout('components.layouts.admin')]
    class extends Component {
    use WithFileUploads, Toast;

    public $showModal = false;
    
    public $image_id = null;
    public $title = '';
    public $alt_text = '';
    public $category = '';
    public $order = 0;
    public $is_active = true;
    public $photo; // For new upload
    public $current_image_path = null; // For display

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'order' => 'integer',
            'is_active' => 'boolean',
            'photo' => $this->image_id ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ];
    }

    public function with(): array
    {
        return [
            'images' => GalleryImage::orderBy('order')->get(),
            'headers' => [
                ['key' => 'image_path', 'label' => 'Imagen', 'sortable' => false],
                ['key' => 'title', 'label' => 'Título'],
                ['key' => 'category', 'label' => 'Categoría'],
                ['key' => 'order', 'label' => 'Orden'],
                ['key' => 'is_active', 'label' => 'Estado'],
                ['key' => 'actions', 'label' => '', 'sortable' => false]
            ]
        ];
    }

    public function create()
    {
        $this->reset(['image_id', 'title', 'alt_text', 'category', 'order', 'is_active', 'photo', 'current_image_path']);
        $this->order = GalleryImage::max('order') + 1;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit(GalleryImage $image)
    {
        $this->resetValidation();
        $this->image_id = $image->id;
        $this->title = $image->title;
        $this->alt_text = $image->alt_text;
        $this->category = $image->category;
        $this->order = $image->order;
        $this->is_active = $image->is_active;
        $this->current_image_path = $image->image_path;
        $this->photo = null;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'alt_text' => $this->alt_text,
            'category' => $this->category,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        if ($this->photo) {
            if ($this->current_image_path) {
                Storage::disk('public')->delete($this->current_image_path);
            }
            $data['image_path'] = $this->photo->store('gallery', 'public');
        }

        if ($this->image_id) {
            GalleryImage::find($this->image_id)->update($data);
            $this->success('Imagen actualizada correctamente.');
        } else {
            GalleryImage::create($data);
            $this->success('Imagen creada correctamente.');
        }

        $this->showModal = false;
    }

    public function delete(GalleryImage $image)
    {
        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
        $this->success('Imagen eliminada correctamente.');
    }

    public function toggleActive(GalleryImage $image)
    {
        $image->update(['is_active' => !$image->is_active]);
        $this->success('Estado actualizado.');
    }
}; ?>

<div>
    <x-mary-header title="Galería de Imágenes" subtitle="Administra las imágenes de la galería">
        <x-slot:actions>
            <x-mary-button icon="o-plus" class="btn-primary" wire:click="create" label="Nueva Imagen" />
        </x-slot:actions>
    </x-mary-header>

    <x-mary-card>
        <x-mary-table :headers="$headers" :rows="$images" striped>
            
            @scope('cell_image_path', $image)
                <div class="avatar">
                    <div class="w-16 h-16 rounded">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}" />
                    </div>
                </div>
            @endscope

            @scope('cell_is_active', $image)
                <x-mary-toggle wire:click="toggleActive({{ $image->id }})" 
                               :checked="$image->is_active" />
            @endscope

            @scope('cell_actions', $image)
                <div class="flex gap-2 justify-end">
                    <x-mary-button icon="o-pencil" wire:click="edit({{ $image->id }})" class="btn-sm btn-ghost text-info" />
                    <x-mary-button icon="o-trash" wire:click="delete({{ $image->id }})" wire:confirm="¿Seguro que deseas eliminar esta imagen?" class="btn-sm btn-ghost text-error" />
                </div>
            @endscope

        </x-mary-table>
    </x-mary-card>

    <x-mary-modal wire:model="showModal" title="{{ $this->image_id ? 'Editar Imagen' : 'Nueva Imagen' }}" box-class="w-full max-w-2xl">
        <x-mary-form wire:submit="save">
            
            <x-mary-input label="Título" wire:model="title" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-mary-input label="Categoría" wire:model="category" />
                <x-mary-input label="Texto Alt" wire:model="alt_text" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-mary-input type="number" label="Orden" wire:model="order" />
                <div class="flex items-center pt-6">
                    <x-mary-toggle label="Activo" wire:model="is_active" />
                </div>
            </div>

            @if($current_image_path && !$photo)
                <div class="mt-4">
                    <span class="block text-sm font-medium mb-2">Imagen Actual</span>
                    <img src="{{ asset('storage/' . $current_image_path) }}" class="w-32 h-32 object-cover rounded-lg" />
                </div>
            @endif

            <x-mary-file wire:model="photo" label="Imagen" accept="image/png, image/jpeg, image/jpg, image/webp" hint="Max 2MB" />

            <x-slot:actions>
                <x-mary-button label="Cancelar" wire:click="$set('showModal', false)" class="btn-ghost" />
                <x-mary-button type="submit" label="Guardar" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-modal>
</div>
