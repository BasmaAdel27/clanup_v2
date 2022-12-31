@extends('layouts.app', [
    'seo_title' => __(':group Discussions', ['group' => $group->name]),
    'seo_description' => substr(strip_tags($group->describe ), 0, 180),
    'seo_image' => $group->avatar,
    'seo_og_type' => 'articles',
    'fixed_header' => true,
])

@push('page_head_scripts')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            menubar: false,
            plugins: 'lists paste',
            paste_auto_cleanup_on_paste : true,
            paste_remove_styles: true,
            paste_remove_styles_if_webkit: true,
            paste_strip_class_attributes: true,
            toolbar: 'undo redo | bullist numlist',
        });
    </script>
@endpush

@section('content')
    <section>
        @include('application.groups._nav', ['page' => 'discussions'])

        <div id="discussions" class="container py-5">
            <div class="row">
                @include('layouts._form_errors')

                <div class="row mb-4">
                    <div class="col">
                        <h2>{{ __('Discussions') }}</h2>
                    </div> 
                    @can('create', [App\Models\Discussion::class, $group])
                        <div class="col col-auto">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addNewDiscussion">{{ __('Add new discussion') }}</button>
                        </div>
                    @endcan
                </div>
                
                @can('viewAny', [\App\Models\Discussion::class, $group])
                    <div class="row scrolling-pagination">
                        @foreach ($discussions as $discussion)
                            <div class="col-12 mb-4">
                                <a class="card card-link" href="{{ route('groups.discussions.details', ['group' => $group->slug, 'discussion' => $discussion->id]) }}#discussionDetails">
                                    <div class="card-body">
                                        <h3 class="card-title">{{ $discussion->title }}</h3>
                                        <div class="markdown text-truncate text-truncate-five-line">
                                            {!! $discussion->content !!}
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <span class="avatar border rounded-circle" style="background-image: url({{ $discussion->user->avatar }})"></span>
                                            </div>
                                            <div class="col">
                                                <p class="mb-0">{!! __('Started by <strong>:full_name</strong>', ['full_name' => $discussion->user->full_name]) !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach

                        <div class="d-none">
                            {{ $discussions->links() }}
                        </div>
                    </div>

                    @if (count($discussions) <= 0)
                        <div class="col-12 d-flex flex-column align-items-center bg-light rounded p-5 mb-4">
                            <i class="far fa-comments fs-2"></i>
                            <p class="fs-4 mb-0 mt-2">{{ __('No discussions yet') }}</p>
                        </div>
                    @endif
                @else
                    @include('application.components.visible-only-member')
                @endcan
            </div>
        </div>
    </section>
@endsection

@push('modals')
    @can('create', [App\Models\Discussion::class, $group])
        <div class="modal fade" id="addNewDiscussion" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('groups.discussions.store', ['group' => $group->slug]) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-uppercase">{{ __('Create a Discussion') }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="form-label" for="title">{{ __('Title') }}</label>
                                <input class="form-control" name="title" type="text" placeholder="{{ __('Title') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="content">{{ __('Content') }}</label>
                                <textarea class="form-control tinymce" name="content"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-end bg-gray-100">
                            <button class="btn btn-light" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button class="btn btn-primary" type="submit">{{ __('Create') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
@endpush
