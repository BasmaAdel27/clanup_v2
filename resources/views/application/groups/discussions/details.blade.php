@extends('layouts.app', [
    'seo_title' => $discussion->title . ' - ' . $group->name,
    'seo_description' => substr(strip_tags($discussion->content ), 0, 180),
    'seo_image' => $group->avatar,
    'fixed_header' => true
])

@section('content')
    <section>
        @include('application.groups._nav', ['page' => 'discussions'])

        <div id="discussionDetails" class="container">
            <div class="row d-flex justify-content-center py-5">
                @can('view', $discussion)
                    <div class="d-flex flex-column card rounded-sm col-lg-8 py-4">
                        <div class="d-flex flex-column p-3">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row">
                                    <h1>{{ $discussion->title }}</h1>
                                </div>
                                <div class="d-flex flex-row align-items-center align-content-center post-title">
                                    <span class="text-muted">{!! __('Started by <strong>:full_name</strong>', ['full_name' => $discussion->user->full_name]) !!}</span>
                                    <span class="mx-2 dot"></span>
                                    <time datetime="{{ $discussion->created_at->format('U') }}">{{ $discussion->created_at->diffForHumans() }}</time>
                                    @can('delete', $discussion)
                                        <span class="mx-2 dot"></span>
                                        <a class="text-danger delete-confirm" href="{{ route('groups.discussions.delete', ['group' => $group->slug, 'discussion' => $discussion->id]) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete discussion') }}</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-row align-items-center text-left p-3">
                            {!! $discussion->content !!}
                        </div>
                        <div id="comments" class="p-2 px-3">
                            @can('create', [App\Models\DiscussionComment::class, $group])
                                <form action="{{ route('groups.discussions.comments.store', ['group' => $group->slug, 'discussion' => $discussion->id]) }}" method="POST">
                                    @csrf
                                    <div class="d-flex flex-row py-4 border-top">
                                        <input type="text" class="form-control me-3" name="comment" placeholder="{{ __('Add comment') }}">
                                        <button class="btn btn-primary rounded-sm" type="submit">{{ __('Comment') }}</button>
                                    </div>
                                </form>
                            @endcan
                            
                            <div class="scrolling-pagination">
                                @foreach ($comments as $comment)
                                    <div class="border-top py-3">
                                        <div class="d-flex flex-row justify-content-between mb-3">
                                            <div class="d-flex flex-row align-items-center">
                                                <img class="avatar shadow-0 border avatar-border-white" src="{{ $comment->user->avatar }}" alt="{{ $comment->user->full_name }}">
                                                <span class="ms-2">
                                                    <h5 class="mb-0">{{ $comment->user->full_name }}</h5>
                                                    <small>{{ $comment->created_at->diffForHumans() }}</small>
                                                </span>
                                            </div>
                                            
                                            @can('delete', [$comment, $group])
                                                <a class="text-danger delete-confirm float-right" href="{{ route('groups.discussions.comments.delete', ['group' => $group->slug, 'discussion' => $comment->discussion->id, 'comment' => $comment->id]) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete comment') }}</a>
                                            @endif
                                        </div>
                                        <span class="p-2">
                                            {{ $comment->content }}
                                        </span>
                                    </div>
                                @endforeach

                                <div class="d-none">
                                    {{ $comments->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-8 py-4">
                        @include('application.components.visible-only-member')
                    </div>
                @endcan
            </div>
        </div>
    </section>
@endsection