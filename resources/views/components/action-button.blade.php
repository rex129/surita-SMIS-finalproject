@props([
    'type' => 'view',   // view | edit | delete
    'href' => null,     // when set renders a link, otherwise a submit button
    'title' => null,
])

@php
    $icons = [
        'view' => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>',
        'edit' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'delete' => '<path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/>',
    ];
    $label = $title ?? ucfirst($type);
@endphp

@if ($href)
    <a href="{{ $href }}" class="icon-btn {{ $type }}" title="{{ $label }}" aria-label="{{ $label }}">
        <svg class="i" viewBox="0 0 24 24">{!! $icons[$type] !!}</svg>
    </a>
@else
    <button type="submit" class="icon-btn {{ $type }}" title="{{ $label }}" aria-label="{{ $label }}">
        <svg class="i" viewBox="0 0 24 24">{!! $icons[$type] !!}</svg>
    </button>
@endif
