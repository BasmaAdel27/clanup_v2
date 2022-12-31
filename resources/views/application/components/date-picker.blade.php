<div
    wire:ignore
    x-data="{ value: @entangle($attributes->wire('model')) }"
    x-on:change="value = $event.target.value"
    x-init='flatpickr($refs.input, { defaultDate: "{{ $defaultDate }}", dateFormat: "Y-m-d", altInput: true, altFormat: "F j, Y", minDate: "{{ $minDate }}" })'
>
    <input 
        {{ $attributes->whereDoesntStartWith('wire:model') }} 
        x-ref="input"
        x-bind:value="value" 
        type="text" 
        class="{{ $classes }}"
    />
</div>