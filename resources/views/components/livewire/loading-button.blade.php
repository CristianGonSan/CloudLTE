@props([
    'type' => 'button',
    'label' => null,
    'icon' => 'save',
    'loadingIcon' => 'circle-notch',
    'theme' => 'outline-primary',
])

@php
    $wireTarget = $attributes->get('wire:target');
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-{$theme}"]) }} wire:loading.attr="disabled">

    {{-- Ícono normal --}}
    <i class="fas fa-fw fa-{{ $icon }} {{ $label ? 'mr-1' : '' }}" wire:loading.remove
        @if ($wireTarget) wire:target="{{ $wireTarget }}" @endif></i>

    {{-- Ícono cargando --}}
    <i class="fas fa-fw fa-{{ $loadingIcon }} fa-spin {{ $label ? 'mr-1' : '' }}" wire:loading
        @if ($wireTarget) wire:target="{{ $wireTarget }}" @endif></i>

    {{ $label }}
</button>
