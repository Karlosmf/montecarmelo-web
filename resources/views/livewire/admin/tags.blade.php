<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use App\Models\Tag;
use Mary\Traits\Toast;

new 
#[Layout('components.layouts.admin')]
class extends Component {
    use Toast;

    public $tags = [];
    public bool $drawer = false;
    public $editingId = null;

    #[Rule('required')]
    public string $name = '';
    
    #[Rule('required')]
    public string $color = 'bg-gray-500';

    // Predefined colors
    public $colors = [
        ['id' => 'bg-red-500', 'name' => 'Rojo'],
        ['id' => 'bg-orange-500', 'name' => 'Naranja'],
        ['id' => 'bg-yellow-500', 'name' => 'Amarillo'],
        ['id' => 'bg-green-500', 'name' => 'Verde'],
        ['id' => 'bg-blue-500', 'name' => 'Azul'],
        ['id' => 'bg-indigo-500', 'name' => 'Índigo'],
        ['id' => 'bg-purple-500', 'name' => 'Púrpura'],
        ['id' => 'bg-pink-500', 'name' => 'Rosa'],
        ['id' => 'bg-gray-500', 'name' => 'Gris'],
        ['id' => 'bg-black', 'name' => 'Negro'],
        ['id' => 'bg-gold-500', 'name' => 'Dorado'], // Custom
    ];

    public function mount()
    {
        $this->refresh();
    }

    public function refresh()
    {
        $this->tags = Tag::all();
    }

    public function create()
    {
        $this->reset(['name', 'color', 'editingId']);
        $this->color = 'bg-gray-500';
        $this->drawer = true;
    }

    public function edit(Tag $tag)
    {
        $this->editingId = $tag->id;
        $this->name = $tag->name;
        $this->color = $tag->color;
        $this->drawer = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'color' => $this->color,
        ];

        if ($this->editingId) {
            Tag::find($this->editingId)->update($data);
            $this->success('Etiqueta actualizada.');
        } else {
            Tag::create($data);
            $this->success('Etiqueta creada.');
        }

        $this->drawer = false;
        $this->refresh();
    }

    public function delete($id)
    {
        Tag::destroy($id);
        $this->refresh();
        $this->success('Etiqueta eliminada.');
    }
}; ?>

<div>
    <x-mary-header title="Etiquetas" subtitle="Gestiona las etiquetas de productos" separator>
         <x-slot:middle class="!justify-end">
            <x-mary-button icon="o-plus" class="btn-primary" label="Nueva Etiqueta" wire:click="create" />
        </x-slot:middle>
    </x-mary-header>

    <x-mary-card>
        <x-mary-table :rows="$tags" :headers="[['key' => 'id', 'label' => '#'], ['key' => 'name', 'label' => 'Nombre / Color'], ['key' => 'actions', 'label' => 'Acciones']]" striped>
            @scope('name', $tag)
                <span class="badge text-white border-none {{ $tag->color }}">
                    {{ $tag->name }}
                </span>
            @endscope

            @scope('actions', $tag)
                <div class="flex">
                    <x-mary-button icon="o-pencil" wire:click="edit({{ $tag->id }})" class="btn-ghost btn-sm text-info" />
                    <x-mary-button icon="o-trash" wire:click="delete({{ $tag->id }})" class="btn-ghost btn-sm text-error" onclick="return confirm('¿Seguro?') || event.stopImmediatePropagation()" />
                </div>
            @endscope
        </x-mary-table>
    </x-mary-card>

    <x-mary-drawer wire:model="drawer" title="{{ $editingId ? 'Editar Etiqueta' : 'Nueva Etiqueta' }}" right class="w-11/12 lg:w-1/3">
        <x-mary-form wire:submit="save">
            <x-mary-input label="Nombre" wire:model="name" />
            
            <x-mary-select label="Color" icon="o-swatch" :options="$colors" wire:model="color">
                <x-slot:optiontemplate>
                     <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-full bg-gray-200" :class="$option['id']"></div>
                        <span x-text="$option['name']"></span>
                    </div>
                </x-slot:optiontemplate>
            </x-mary-select>

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.drawer = false" />
                <x-mary-button label="Guardar" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>
</div>
