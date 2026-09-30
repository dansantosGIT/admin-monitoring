@props([
    'variant' => 'secondary',
    'href' => null,
    'type' => 'button',
])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "ui-button ui-button--{$variant}"]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => "ui-button ui-button--{$variant}"]) }}>{{ $slot }}</button>
@endif
