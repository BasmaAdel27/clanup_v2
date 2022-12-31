@extends('layouts.app', [
    'seo_title' => __('My Groups'),
])

@section('content')
    <section class="py-4 py-lg-5">
        <div class="container">
            <h1>{{ __('My Groups') }}</h1>

            <div class="btn-group my-3">
                <a class="btn {{ $tab == 'organizer' ? 'btn-primary' : '' }}" href="{{ route('groups') }}">{{ __('Organizer') }}</a>
                <a class="btn {{ $tab == 'member' ? 'btn-primary' : '' }}" href="{{ route('groups', ['tab' => 'member']) }}">{{ __('Member') }}</a>
            </div>

            @if ($groups->count() > 0)
                <div class="scrolling-pagination mt-2">
                    @foreach ($groups as $group)
                        @include('application.groups._group_card', ['group' => $group])
                    @endforeach

                    <div class="d-none">
                        {{ $groups->withQueryString()->links() }}
                    </div>
                </div>
            @else 
                <p>{{ __('No groups yet.') }}</p>
            @endif
        </div>
    </section>
@endsection