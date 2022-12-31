@extends('layouts.app', [
    'seo_title' => __(':group Photos', ['group' => $group->name]),
    'seo_description' => substr(strip_tags($group->describe ), 0, 180),
    'seo_image' => $group->avatar,
    'fixed_header' => true,
])

@section('content')
    <section>
        @include('application.groups._nav', ['page' => 'photos'])

        <div id="photos" class="container py-5">
            <div class="row">
                @include('layouts._form_errors')

                <div class="row mb-4">
                    <div class="col">
                        <h2>{{ __('Photos') }}</h2>
                    </div>
                    @can('store_photo', $group)
                        <div class="col col-auto">
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addImageModal">{{ __('Upload new') }}</button>
                        </div>
                    @endcan
                </div>
                
                <div class="row gallery">
                    @can('view_photos', $group)
                        <div class="scrolling-pagination">
                            <div class="row row-cards">
                                @foreach ($photos as $photo)
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="card card-sm">
                                            <a class="d-block" href="{{ asset($photo->getFullUrl()) }}" data-fancybox="gallery" data-title="{{ $photo->name }}">
                                                <img class="card-img-top" src="{{ asset($photo->getFullUrl()) }}" alt="{{ $photo->name }}">
                                            </a>
                                            <div class="card-body border-top">
                                                <div class="d-flex align-items-center">
                                                    @if ($photo->created_by)
                                                        <span class="avatar border rounded-circle me-2" style="background-image: url({{ $photo->created_by->avatar }})"></span>
                                                        <div>
                                                            <div>{{ $photo->created_by->full_name }}</div> 
                                                            <div class="text-muted">{{ $photo->created_at->diffForHumans() }}</div>
                                                        </div>
                                                    @endif
                                                    <div class="ms-auto">
                                                        @can('delete_photo', $group)
                                                            <button class="btn btn-link p-0 text-danger delete-confirm" href="{{ route('groups.photos.delete', ['group' => $group->slug, 'photo' => $photo->id]) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="d-none">
                                    {{ $photos->links() }}
                                </div>
                            </div>
                        </div>
            
                        @if (count($photos) <= 0)
                            <div class="col-12 d-flex flex-column align-items-center bg-light rounded p-5 mb-4">
                                <i class="far fa-images fs-2"></i>
                                <p class="fs-4 mb-0 mt-2">{{ __('No photos yet') }}</p>
                            </div>
                        @endif
                    @else
                        <div class="col-12">
                            @include('application.components.visible-only-member')
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </section>
@endsection

@push('modals')
    @can('store_photo', $group)
        <div class="modal fade" id="addImageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('groups.photos.store', ['group' => $group->slug]) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title text-uppercase">{{ __('Upload Image') }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="form-label" for="title">{{ __('Title') }}</label>
                                <input class="form-control" name="title" type="text" placeholder="{{ __('Title') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="file">{{ __('Select photo') }}</label>
                                <input class="form-control" name="file" type="file" accept="image/png, image/jpeg" required>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-end bg-gray-100">
                            <button class="btn btn-primary" type="submit">{{ __('Upload') }}</button>
                            <button class="btn btn-outline-muted" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
@endpush