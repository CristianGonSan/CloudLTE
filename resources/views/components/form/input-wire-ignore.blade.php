@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{--
        Copia de x-adminlte-input.
        Se añade un wrapper con wire:ignore para evitar que Livewire modifique el input.
    --}}

    <div class="w-100" wire:ignore>
        {{-- Input --}}
        <input id="{{ $id }}" name="{{ $name }}"
            value="{{ $getOldValue($errorKey, $attributes->get('value')) }}"
            {{ $attributes->merge(['class' => $makeItemClass()]) }}>
    </div>

@overwrite
