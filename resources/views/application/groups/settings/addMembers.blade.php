@extends('layouts.app', [
    'seo_title' => __('Add member'),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'addMembers'])

            <form class="col-12 col-lg-8 mb-5 mb-lg-0" action="{{route('groups.settings.addMembers.update', ['group' => $group->slug])}}" method="POST">
                @csrf
                <h1 class="mb-4">{{ __('Add members') }}</h1>

                <div class="form-group mb-4">
                    <label class="form-label" for="group_welcome_message">{{ __('Choose members') }}</label>
                    <select class="basic-multiple form-control" name="user_id[]" multiple="multiple">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id,isset($members) ? $members : [])
                             ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" value="{{$group->id}}" name="group_id">
                <input type="hidden" value="20" name="membership">
                <div class="float-end">
                    <button type="submit" class="btn btn-primary">{{ __('Update settings') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection
