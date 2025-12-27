<?php

use function Livewire\Volt\{state};

state(['count' => 0]);

$increment = fn() => $this->count++;

?>

<div>
    <x-mary-header title="Welcome to Montecarmelo" separator progress-indicator>
        <x-slot:actions>
            <x-mary-button icon="o-plus" label="Increment" wire:click="increment" class="btn-primary" />
        </x-slot:actions>
    </x-mary-header>

    <x-mary-card title="Counter" subtitle="A simple MaryUI + Volt counter" shadow separator>
        <div class="text-3xl font-bold">
            {{ $count }}
        </div>
    </x-mary-card>
</div>
