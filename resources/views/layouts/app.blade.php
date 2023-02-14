<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (!Auth::guest())
        <meta name="user-id" content="{{ Auth::user()->id }}" >
    @endif
    @include('layouts._seo')
    @include('layouts._favicons')
    @include('layouts._css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .chat {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .chat li {
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #B3A9A9;
        }

        .chat li .chat-body p {
            margin: 0;
            color: #777777;
        }

        .panel-body {
            overflow-y: scroll;
            height: 350px;
        }

        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar {
            width: 12px;
            background-color: #F5F5F5;
        }

        ::-webkit-scrollbar-thumb {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #555;
        }
    </style>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            'pusherCluster' => config('broadcasting.connections.pusher.options.cluster'),
        ]) !!};
    </script>
</head>

<body>
    @impersonating($guard = null)
        <div class="notification-top-bar notification-top-bar bg-dark">
            <p>{{ __('You are impersonating one of the user') }} <small><a href="{{ route('impersonate.leave') }}" class="text-primary">{{ __('Leave impersonation') }}</a></small></p>
        </div>
    @endImpersonating

    <div class="page">
        @include('layouts._header')

        <div class="page-wrapper" id="app">
            @yield('content')

            @include('layouts.._footer')
        </div>
    </div>

    @stack('modals')
    @livewire('common.share-modal')
{{--    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>--}}
    <script src="/js/app.js"></script>
    @include('layouts._js')
    @include('layouts._autocomplete', ['types' => []])
    @include('layouts._flash')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.basic-multiple').select2({
                placeholder: "{{__('select please')}}",
                allowClear: true
            });
        });
    </script>
{{--    <script>--}}
{{--        $('.group').click(function () {--}}
{{--            $('.group').removeClass('active');--}}
{{--            $(this).addClass('active');--}}
{{--            group_id = $(this).attr('id');--}}

{{--            $.ajax({--}}
{{--                type: "get",--}}
{{--                url: "/messages/"+ group_id,--}}
{{--                data: "",--}}
{{--                cache: false,--}}
{{--                success: function (data) {--}}
{{--                        console.log(data)--}}
{{--                }--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
<script>
    $('#notificationsDropdown').click(function () {
        console.log(  $(this).find('.bellLight'))

        $(this).find('.bg-danger').remove();
        $.ajax({
            type: "get",
            url: "/isRead" ,
            cache: false,
            success: function (data) {
                document.getElementById('notify').innerHTML='';
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        const pmbox = document.getElementById('datascroll')
        const exampleModal=document.getElementById('exampleModal');
        if (exampleModal) {
            exampleModal.addEventListener('show.bs.modal', refreshScroll)
        }

        var objDiv = document.getElementById('datascroll')
        objDiv.scrollTop = objDiv.scrollHeight
        document.querySelector('#open').addEventListener('click', function (e) {
            e.preventDefault()
            setTimeout(() => {
                refreshScroll()
            }, 200);
        })

        function refreshScroll () {
            var objDiv = document.getElementById('datascroll')
            if (objDiv) objDiv.scrollTop = objDiv.scrollHeight
        }
    });
</script>

</body>
</html>
