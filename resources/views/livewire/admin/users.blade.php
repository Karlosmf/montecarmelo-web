<?php

use Livewire\Volt\Component;
use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Attributes\Layout;

new 
#[Layout('components.layouts.admin')]
class extends Component {
    use Toast;

    public $users = [];

    public function mount()
    {
        $this->users = User::all();
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            $this->error("No puedes eliminarte a ti mismo.");
            return;
        }
        $user->delete();
        $this->users = User::all();
        $this->success("Usuario eliminado.");
    }
}; ?>

<div class="p-8">
    <x-mary-header title="Gestión de Usuarios" subtitle="Administra clientes y administradores" separator />

    <x-mary-card>
        <x-mary-table :rows="$users" :headers="[['key' => 'id', 'label' => '#'], ['key' => 'name', 'label' => 'Nombre'], ['key' => 'email', 'label' => 'Email'], ['key' => 'role', 'label' => 'Rol'], ['key' => 'actions', 'label' => 'Acciones']]" striped>
            @scope('actions', $user)
                <x-mary-button icon="o-trash" wire:click="deleteUser({{ $user->id }})" class="btn-ghost btn-sm text-error" />
            @endscope
        </x-mary-table>
    </x-mary-card>
</div>
