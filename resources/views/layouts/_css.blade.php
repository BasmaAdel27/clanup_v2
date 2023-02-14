<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}?version={{ get_system_setting('version') }}">
<link rel="stylesheet" href="{{ asset('assets/css/app-vendors.min.css') }}?version={{ get_system_setting('version') }}">
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
@livewireStyles
<style>
    .envelope{
        background-color: #E06F19 !important;
        color: white !important;width: 3rem;
        height: 3rem;height: 3rem;border-radius: 50%;border: unset;
    }
</style>
@stack('page_head_scripts')

