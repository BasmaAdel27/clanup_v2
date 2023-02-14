
<div>
    <div class="container pt-5 pb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="img-wrap ratio-16-9">
                    <div class="img-content">
                        <img class="rounded border" src="{{ $group->avatar }}" alt="{{ $group->name }}">
                    </div>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="row align-items-center mb-2">
                    <div class="col-auto">
                    <h1 style="font-size: xx-large;">{{ $group->name }}</h1>
                    </div>
                    <div class="col fs-3">
                        <button class="envelope"   data-toggle="modal" data-target="#exampleModal" id="open" >
                            <i class="fas fa-envelope" style="font-size: x-large;padding: 3px;"></i>
                        </button>
                    </div>
                </div>
                <div class="row align-items-center mb-2">
                    <div class="col-auto">
                        <span class="avatar bg-primary rounded-circle text-white">
                            <i class="fa fa-map-marker-alt"></i>
                        </span>
                    </div>
                    <div class="col fs-3">
                        {{ $group->address->name }}
                    </div>
                </div>

                <div class="row align-items-center mb-2">
                    <div class="col-auto">
                        <span class="avatar bg-primary rounded-circle text-white">
                            <i class="fa fa-user-friends"></i>
                        </span>
                    </div>
                    <div class="col fs-3">
                        {{ __(':count Members', ['count' => $group->member_count]) }} - <span class="fst-italic">{{ $group->isOpen() ? __('Public Group') : __('Closed Group') }}
                    </div>
                </div>

                <div class="row align-items-center mb-2">
                    <div class="col-auto">
                        <span class="avatar bg-primary rounded-circle text-white">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>
                    <div class="col fs-3">
                        @if($group->co_organizers()->count() == 1)
{{--                            // Organizer name clickable to profile--}}
                            {!! __('Organized by <strong>:organizer_name</strong>, and <strong>:co_organizer_name</strong>', [
                                'organizer_name' => $group->createdBy->full_name,
                                'co_organizer_name' => $group->co_organizers()->first()->full_name,
                            ]) !!}
                        @elseif($group->co_organizers()->count() > 1)
                            {!! __('Organized by <strong>:organizer_name</strong>, and <strong>:count others</strong>', [
                                'organizer_name' => $group->createdBy->full_name,
                                'count' => $group->co_organizers()->count(),
                            ]) !!}
                        @else
                            {!! __('Organized by <strong>:organizer_name</strong>', [
                                'organizer_name' => $group->createdBy->full_name,
                            ]) !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bg-white sticky-top border py-2">
    <div class="container d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <ul class="nav mobile-horizontal">
            <li class="nav-item">
                <a class="nav-link fs-3 ps-0 {{ $page == 'about' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.about', $group->slug) }}#about">{{ __('About') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-3 {{ $page == 'events' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.events', ['group' => $group->slug]) }}#events">{{ __('Events') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-3 {{ $page == 'members' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.members', $group->slug) }}#members">{{ __('Members') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-3 {{ $page == 'photos' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.photos', $group->slug) }}#photos">{{ __('Photos') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-3 {{ $page == 'discussions' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.discussions', $group->slug) }}#discussions">{{ __('Discussions') }}</a>
            </li>
        </ul>
        <div>
            @livewire('group.actions.join-group', ['group' => $group], key($group->slug))
        </div>
    </div>
</div>
<input type="hidden" value="{{$group->id}}" id="data" ref="data">
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container" style="margin: auto;">
                    <div class="card">
                        <div class="card-header" style="background-color: #E06F19;color: white;">{{$group->name}}</div>
                        <div class="card-body" style=" overflow: scroll;height: 289px;" id="datascroll">
                            <chat-messages v-for="(message, id) in messages "
                                           v-bind:key="id"
                                           v-bind:message = "message.message"
                                           v-bind:username = "message.user.username"
                                           v-bind:user_id = "message.user.id"
                                           v-bind:created_at = "message.created_at"
                                           :group="{{$group}}" :auth="{{\Illuminate\Support\Facades\Auth::user()}}"></chat-messages>
                        </div>
                        <div class="card-footer">
                                <form action="/messages/{{$group->id}}" method="post" v-on:submit='addMessage'>
                                    @csrf
                                    <div class="input-group">
                                    <input id="btn-input" type="text"   name="message" v-model="message" class="form-control input-sm" placeholder="Type your message here...">
                                 <input type="hidden" value="{{\Illuminate\Support\Facades\Auth::user()}}">
                                <span class="input-group-btn" style="margin: -10px">
                                <button type="submit" class="btn btn-primary btn-sm" id="btn-chat"
                                        style="margin: 10px;background-color: #E06F19;  scroll-behavior: smooth;
" >
                                    <i class="far fa-paper-plane" style="padding:9px;font-size: large"></i>
                                </button>
                            </span>
                                    </div>
                                </form>
                        </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

        </div>
    </div>

