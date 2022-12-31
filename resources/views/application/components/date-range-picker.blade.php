<div
    wire:ignore
    x-data="{ value: @entangle($attributes->wire('model')) }"
    x-on:change="value = $event.target.value"
    x-init='flatpickr($refs.input, { mode: "range", dateFormat: "Y-m-d", defaultDate: ["{{ $defaultDateFrom }}", "{{ $defaultDateTo }}"], altInput: true, altFormat: "M j", minDate: "today" })'
    class="me-2 drp-container" 
>
    <input 
        {{ $attributes->whereDoesntStartWith('wire:model') }} 
        x-ref="input"
        x-bind:value="value" 
        type="text" 
        class="{{ $classes }}"
        placeholder="{{ __('Any date') }}"
    />
</div>