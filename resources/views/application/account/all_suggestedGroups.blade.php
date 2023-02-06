@extends('layouts.app', [
    'seo_title' => __('Suggested Groups'),
])

@section('content')
    <section class="py-4 py-lg-5">
        <div class="container">
            <h1>{{ __('Suggested Groups') }}</h1>
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
