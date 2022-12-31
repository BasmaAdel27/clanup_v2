@extends('layouts.app', [
    'seo_title' => __(':group Members', ['group' => $group->name]),
    'seo_description' => substr(strip_tags($group->describe ), 0, 180),
    'seo_image' => $group->avatar,
    'fixed_header' => true,
])

@section('content')
    <section>
        @include('application.groups._nav', ['page' => 'members'])
        
        <div id="members" class="container py-5">
            @livewire('group.members', ['group' => $group],  key($group->id))
        </div>
    </section>
@endsection
