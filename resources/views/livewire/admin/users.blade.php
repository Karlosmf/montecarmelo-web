<?php

use Livewire\Volt\Component;
use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

new
    #[Layout('components.layouts.admin')]
    class extends Component {
    use Toast;

    public $users = [];
    public bool $drawer = false;

    // Filter State
    public string $filter = 'all'; // all, pending, commercial

    #[Rule('required|min:3')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    #[Rule('required|in:admin,user')]
    public string $role = 'user';

    // B2B Fields
    public ?string $company_name = null;
    public ?string $cuit = null;
    public ?string $phone = null;
    public ?string $address = null;

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $query = User::query();

        if ($this->filter === 'pending') {
            $query->pending();
        } elseif ($this->filter === 'commercial') {
            $query->whereNotNull('company_name');
        }

        $this->users = $query->orderBy('created_at', 'desc')->get();
    }

    public function updatedFilter()
    {
        $this->loadUsers();
    }

    public function create()
    {
        $this->reset(['name', 'email', 'password', 'role', 'company_name', 'cuit', 'phone', 'address']);
        $this->drawer = true;
    }

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'company_name' => $this->company_name,
            'cuit' => $this->cuit,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => true // Admin created users are active by default
        ]);

        $this->success("Usuario creado correctamente.");
        $this->drawer = false;
        $this->loadUsers();
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            $this->error("No puedes eliminarte a ti mismo.");
            return;
        }
        $user->delete();
        $this->loadUsers();
        $this->success("Usuario eliminado.");
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        $this->loadUsers();
        $this->success($user->is_active ? "Usuario activado." : "Usuario desactivado.");
    }
}; ?>

<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <x-mary-header title="Gestión de Usuarios" subtitle="Administra clientes y solicitudes B2B" separator />
            <div class="flex gap-2 mt-2">
                <x-mary-button label="Todos" wire:click="$set('filter', 'all')"
                    class="btn-sm {{ $filter === 'all' ? 'btn-neutral' : 'btn-ghost' }}" />
                <x-mary-button label="Pendientes" wire:click="$set('filter', 'pending')"
                    class="btn-sm {{ $filter === 'pending' ? 'btn-error text-white' : 'btn-ghost' }}">
                    @if(\App\Models\User::pending()->count() > 0)
                        <div class="badge badge-xs bg-white text-error border-0 ml-1">
                            {{\App\Models\User::pending()->count()}}</div>
                    @endif
                </x-mary-button>
                <x-mary-button label="Comerciales" wire:click="$set('filter', 'commercial')"
                    class="btn-sm {{ $filter === 'commercial' ? 'btn-neutral' : 'btn-ghost' }}" />
            </div>
        </div>
        <x-mary-button label="Nuevo Usuario" icon="o-plus" class="btn-primary" wire:click="create" />
    </div>

    <x-mary-card>
        <x-mary-table :rows="$users" :headers="[['key' => 'id', 'label' => '#'], ['key' => 'name', 'label' => 'Nombre / Empresa'], ['key' => 'email', 'label' => 'Contacto'], ['key' => 'role', 'label' => 'Rol'], ['key' => 'status', 'label' => 'Estado'], ['key' => 'actions', 'label' => 'Acciones']]" striped>

            @scope('name', $user)
            <div>
                <div class="font-bold">{{ $user->name }}</div>
                @if($user->company_name)
                    <div class="text-xs text-primary font-bold">{{ $user->company_name }}</div>
                    <div class="text-[10px] text-gray-500">CUIT: {{ $user->cuit }}</div>
                @endif
            </div>
            @endscope

            @scope('email', $user)
            <div>
                <div>{{ $user->email }}</div>
                @if($user->phone)
                    <div class="text-xs text-gray-400 flex items-center gap-1"><x-mary-icon name="o-phone"
                            class="w-3 h-3" /> {{ $user->phone }}</div>
                @endif
            </div>
            @endscope

            @scope('role', $user)
            <span class="badge {{ $user->role == 'admin' ? 'badge-primary' : 'badge-ghost' }}">{{ $user->role }}</span>
            @endscope

            @scope('status', $user)
            @if($user->is_active)
                <span class="badge badge-success badge-sm">Activo</span>
            @else
                <span class="badge badge-error badge-sm animate-pulse">Pendiente</span>
            @endif
            @endscope

            @scope('actions', $user)
            <div class="flex gap-1">
                @if(!$user->is_active)
                    <x-mary-button icon="o-check" wire:click="toggleActive({{ $user->id }})"
                        class="btn-success btn-sm text-white" tooltip="Aprobar Cuenta" />
                @else
                    <x-mary-button icon="o-no-symbol" wire:click="toggleActive({{ $user->id }})"
                        class="btn-ghost btn-sm text-gray-500" tooltip="Desactivar" />
                @endif
                <x-mary-button icon="o-trash" wire:click="deleteUser({{ $user->id }})"
                    class="btn-ghost btn-sm text-error" wire:confirm="¿Estás seguro de eliminar este usuario?" />
            </div>
            @endscope
        </x-mary-table>
    </x-mary-card>

    <x-mary-drawer wire:model="drawer" title="Nuevo Usuario" right class="w-11/12 lg:w-1/3">
        <x-mary-form wire:submit="save" class="space-y-4">
            <x-mary-input label="Nombre" wire:model="name" icon="o-user" />
            <x-mary-input label="Email" wire:model="email" icon="o-envelope" />
            <x-mary-password label="Contraseña" wire:model="password" />

            <x-mary-select label="Rol" wire:model="role" :options="[['id' => 'user', 'name' => 'Usuario'], ['id' => 'admin', 'name' => 'Administrador']]" />

            <div class="divider text-xs text-gray-500">Datos Comerciales (Opcional)</div>

            <x-mary-input label="Razón Social" wire:model="company_name" icon="o-briefcase" />
            <div class="grid grid-cols-2 gap-2">
                <x-mary-input label="CUIT" wire:model="cuit" icon="o-identification" />
                <x-mary-input label="Teléfono" wire:model="phone" icon="o-phone" />
            </div>
            <x-mary-input label="Dirección" wire:model="address" icon="o-map-pin" />

            <x-slot:actions>
                <x-mary-button label="Cancelar" @click="$wire.drawer = false" />
                <x-mary-button label="Guardar" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-drawer>
</div>