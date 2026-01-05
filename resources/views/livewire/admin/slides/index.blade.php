<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\Slide;
use Illuminate\Support\Facades\Storage;
use Mary\Traits\Toast;

new
    #[Layout('components.layouts.admin')]
    class extends Component {
    use Toast, WithFileUploads;

    // State
    public bool $drawer = false;

    // Form Properties
    public ?Slide $editingSlide = null;

    #[Rule('required|min:3')]
    public string $title = '';

    #[Rule('required')]
    public string $description = '';

    #[Rule('nullable|string')]
    public ?string $button_text = null;

    #[Rule('nullable|string')]
    public ?string $button_url = null;

    #[Rule('boolean')]
    public bool $is_active = true;

    #[Rule('nullable|image|max:4096')] // 4MB Max
    public $photo;

    // Helpers
    public function mount()
    {
        $this->editingSlide = new Slide();
    }

    public function with()
    {
        return [
            'slides' => Slide::query()
                ->orderBy('order', 'asc')
                ->get()
        ];
    }

    // Delete Confirmation State
    public bool $deleteModal = false;
    public ?Slide $slideToDelete = null;

    // Actions
    public function create()
    {
        $this->reset(['title', 'description', 'button_text', 'button_url', 'is_active', 'photo']);
        $this->editingSlide = new Slide();
        $this->drawer = true;
    }

    public function edit(Slide $slide)
    {
        $this->editingSlide = $slide;

        $this->title = $slide->title;
        $this->description = $slide->description;
        $this->button_text = $slide->button_text;
        $this->button_url = $slide->button_url;
        $this->is_active = $slide->is_active;
        $this->photo = null;

        $this->drawer = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'button_text' => $this->button_text,
            'button_url' => $this->button_url,
            'is_active' => $this->is_active,
        ];

        // Handle Image Upload
        if ($this->photo) {
            if ($this->editingSlide->exists && $this->editingSlide->image_path) {
                Storage::disk('public')->delete($this->editingSlide->image_path);
            }
            $data['image_path'] = $this->photo->store('slides', 'public');
        } elseif (!$this->editingSlide->exists) {
            $this->addError('photo', 'La imagen es obligatoria.');
            return;
        }

        if ($this->editingSlide->exists) {
            $this->editingSlide->update($data);
            $this->success('Slide actualizado correctamente.');
        } else {
            // Set order to last + 1
            $maxOrder = Slide::max('order') ?? 0;
            $data['order'] = $maxOrder + 1;

            Slide::create($data);
            $this->success('Slide creado correctamente.');
        }

        $this->drawer = false;
    }

    public function confirmDelete(Slide $slide)
    {
        $this->slideToDelete = $slide;
        $this->deleteModal = true;
    }

    public function destroySlide() // Renamed to actual destroy action
    {
        if ($this->slideToDelete) {
            if ($this->slideToDelete->image_path) {
                Storage::disk('public')->delete($this->slideToDelete->image_path);
            }
            $this->slideToDelete->delete();
            $this->success('Slide eliminado.');
        }

        $this->deleteModal = false;
        $this->slideToDelete = null;
    }

    public function moveUp(Slide $slide)
    {
        $previousSlide = Slide::where('order', '<', $slide->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousSlide) {
            $tempOrder = $slide->order;
            $slide->update(['order' => $previousSlide->order]);
            $previousSlide->update(['order' => $tempOrder]);
        }
    }

    public function moveDown(Slide $slide)
    {
        $nextSlide = Slide::where('order', '>', $slide->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextSlide) {
            $tempOrder = $slide->order;
            $slide->update(['order' => $nextSlide->order]);
            $nextSlide->update(['order' => $tempOrder]);
        }
    }
}; ?>

<div>
    {{-- HEADER --}}
    <x-mary-header title="Gestión de Hero Slider" subtitle="Administra las imágenes y contenido del slider principal." separator>
        <x-slot:middle class="!justify-end">
             <x-mary-button label="Nuevo Slide" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:middle>
    </x-mary-header>

    {{-- SLIDES LIST (Grid) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($slides as $slide)
            <x-mary-card wire:key="{{ $slide->id }}" class="!p-0 overflow-hidden">
                <figure class="aspect-[16/9] relative group">
                    <img src="{{ asset('storage/' . $slide->image_path) }}" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110" />
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                         <x-mary-button icon="o-pencil" class="btn-circle btn-ghost text-white" wire:click="edit({{ $slide->id }})" />
                    </div>
                </figure>
                
                <div class="card-body p-4">
                    <div class="flex justify-between items-start">
                         <h2 class="card-title text-base">{{ $slide->title }}</h2>
                         <x-mary-badge :value="$slide->is_active ? 'Activo' : 'Inactivo'" :class="$slide->is_active ? 'badge-success' : 'badge-error'" />
                    </div>
                    <p class="text-xs opacity-70 line-clamp-2">{{ $slide->description }}</p>
                    
                    <div class="card-actions justify-end mt-4 items-center gap-2">
                        <div class="join">
                             <x-mary-button icon="o-arrow-up" wire:click="moveUp({{ $slide->id }})" class="join-item btn-ghost btn-xs" />
                             <x-mary-button icon="o-arrow-down" wire:click="moveDown({{ $slide->id }})" class="join-item btn-ghost btn-xs" />
                        </div>
                        <x-mary-button icon="o-trash" wire:click="confirmDelete({{ $slide->id }})" class="btn-ghost btn-sm text-error" />
                    </div>
                </div>
            </x-mary-card>
        @empty
            <div class="col-span-full py-12 text-center opacity-50">
                 <x-mary-icon name="o-photo" class="w-12 h-12 mx-auto mb-2" />
                 <div>No hay slides creados</div>
            </div>
        @endforelse
    </div>

    {{-- DRAWER --}}
    <x-mary-drawer wire:model="drawer" title="{{ $editingSlide->exists ? 'Editar Slide' : 'Nuevo Slide' }}" right
        class="w-11/12 lg:w-1/3">

        <x-mary-form wire:submit="save" class="mt-4 space-y-4">

            {{-- IMAGE UPLOAD --}}
            <div class="flex flex-col items-center mb-6">
                <div
                    class="relative group cursor-pointer w-full h-48 rounded-xl overflow-hidden border-2 border-dashed border-base-300 hover:border-primary transition-all bg-base-200">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" />
                    @elseif($editingSlide->image_path)
                        <img src="{{ asset('storage/' . $editingSlide->image_path) }}" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-500">
                            <x-mary-icon name="o-photo" class="w-10 h-10 mb-2 opacity-50" />
                            <span class="text-xs uppercase tracking-widest">Subir Imagen Background</span>
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

            <x-mary-input label="Título" wire:model="title" />
            <x-mary-textarea label="Descripción" wire:model="description" rows="3" />

            <div class="grid grid-cols-2 gap-4">
                <x-mary-input label="Texto Botón (Opcional)" wire:model="button_text" />
                <x-mary-input label="Link Botón (Opcional)" wire:model="button_url" />
            </div>

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
            <h3 class="text-lg font-bold text-error">Eliminar Slide</h3>
            <p class="py-4 text-gray-500">¿Estás seguro que deseas eliminar el slide <span
                    class="font-bold">"{{ $slideToDelete?->title }}"</span>? Esta acción no se puede
                deshacer.</p>
        </div>
        <x-slot:actions>
            <x-mary-button label="Cancelar" @click="$wire.deleteModal = false" />
            <x-mary-button label="Eliminar" wire:click="destroySlide" class="btn-error" />
        </x-slot:actions>
    </x-mary-modal>

</div>