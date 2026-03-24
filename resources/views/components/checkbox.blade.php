@props([
    'id',
    'name',
    'label' => null,
    'theme' => 'primary',
    'title' => '',
])

@php
    $id ??= $name;
@endphp

<div class="icheck-{{ $theme }}" title="{{ $title }}">
    <input type="checkbox" id="{{ $id }}" name="{{ $name }}" {{ $attributes }}>
    @if($label)
        <label for="{{ $id }}">
            {{ $label }}
        </label>
    @endif
</div>
