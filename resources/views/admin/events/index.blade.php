@extends('layouts.admin', ['page' => 'events'])

@section('title', __('Events'))
    
@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Events') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @include('admin.events._filters')
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Events') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Group') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr> 
                                </thead> 
                                <tbody class="list" id="events">
                                    @foreach ($events as $event)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $event->id }}</p>
                                            </td>
                                            <td>
                                                @if (!$event->group->deleted_at and $event->group->slug)
                                                    <a class="mb-0" target="_blank" href="{{ route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]) }}">
                                                        {{ $event->title }}
                                                    </a>
                                                @else
                                                    <p class="mb-0">
                                                        {{ $event->title }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="mb-0 {{ $event->group->deleted_at ? 'text-decoration-line-through' : '' }}">{{ $event->group->name }}</p>
                                            </td>
                                            <td>
                                                @if ($event->deleted_at)
                                                    <p class="mb-0 text-danger">{{ __('Deleted') }}</p>
                                                @else
                                                    @if ($event->isCancelled())
                                                        <p class="mb-0 text-warning">{{ __('Cancelled') }}</p>
                                                    @elseif($event->isPast())
                                                        <p class="mb-0 text-danger">{{ __('Past') }}</p>
                                                    @elseif($event->isDraft())
                                                        <p class="mb-0 text-muted">{{ __('Draft') }}</p>
                                                    @else
                                                        <p class="mb-0 text-success">{{ __('Upcoming') }}</p>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <a href="#" class="link-secondary" data-bs-toggle="dropdown" aria-expanded="true">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @if ($event->group->id)
                                                            <a class="dropdown-item" href="{{ route('impersonate', optional($event->group->organizer)->id) }}">
                                                                {{ __('Login & Manage') }}
                                                            </a>
                                                        @endif
                                                        @if (!$event->deleted_at)
                                                            <a class="dropdown-item text-danger delete-confirm" href="{{ route('admin.events.delete', $event->id) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                                {{ __('Delete') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($events->hasPages())
                            <div class="card-footer d-flex align-items-center justify-content-center">
                                {{ $events->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
