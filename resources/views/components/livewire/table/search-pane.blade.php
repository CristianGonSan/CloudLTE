@props([
    'modelSearch' => 'searchTerm',
    'modelPerPage' => 'perPage',
    'actionSearch' => 'search',
    'actionClearSearch' => 'clearSearch',
    'target' => '',
    'autofocus' => true,
])

@php
    $hasFilters = !$slot->isEmpty();
@endphp

<div class="row">
    <div class="col-md-{{ $hasFilters ? '9' : '10' }} col-sm-8 col-12 mb-1">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Presiona Enter para buscar..."
                wire:model="{{ $modelSearch }}" wire:keydown.enter="{{ $actionSearch }}" wire:loading.attr='readonly'
                wire:target="{{ $actionSearch }},{{ $actionClearSearch }}"
                @if ($autofocus) autofocus @endif>

            <div class="input-group-append">
                @if (filled($this->{$modelSearch}))
                    <button class="btn btn-outline-secondary" type="button" wire:click="{{ $actionClearSearch }}"
                        title="Limpiar búsqueda">
                        <i class="fas fa-times"></i>
                    </button>
                @endif

                <x-livewire.loading-button theme="outline-secondary" icon="magnifying-glass"
                    wire:click="{{ $actionSearch }}" wire:target="{{ $target }}" />
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-4 mb-1">
        <select class="custom-select border-secondary" wire:model="{{ $modelPerPage }}"
            wire:change="{{ $actionSearch }}">
            <option value="12">12 registros</option>
            <option value="24">24 registros</option>
            <option value="48">48 registros</option>
        </select>
    </div>

    @if ($hasFilters)
        <div class="col-md-1 col-12 mb-1">
            <button class="btn btn-outline-secondary btn-block" type="button" data-toggle="collapse"
                data-target="#collapseFilters_{{ $this->getId() }}" aria-expanded="false"
                aria-controls="collapseFilters_{{ $this->getid() }}" x-data="{ open: false }" data-toggle="collapse"
                data-target="#collapseFilters_{{ $this->getid() }}" x-on:click="open = !open">
                <i class="fas fw" :class="open ? 'fa-times' : 'fa-filter'"></i>
            </button>
        </div>
    @endif
</div>

@if ($hasFilters)
    <div id="collapseFilters_{{ $this->getid() }}" class="collapse" wire:ignore.self>
        {{ $slot }}
    </div>
@endif
