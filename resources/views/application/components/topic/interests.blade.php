<div>
    <div class="text-block mb-3">
        <h3>{{ __('Selected Interests') }}</h3>
        @if ($selected_topics_max_reached)
            <div class="alert alert-danger mt-2" role="alert">
                <label class="label-absolute mr-3">
                    <i class="fa fa-exclamation-circle text-danger"></i>
                </label>
                <label class="pl-25px">{{ __('You can choose up to 15 topics.') }}</label>
            </div>
        @endif
        <ul class="list-inline">
            @if (count($selected_interests) > 0)
                @foreach ($selected_interests as $topic)
                    <li class="list-inline-item mb-2 {{ $this->model->hasAnyTopics($topic->id) ? '' : 'd-none' }}" wire:click="detach({{ $topic->id }})">
                        <span class="badge bg-primary p-2">{{ $topic->name }}</span>
                    </li>
                @endforeach 
            @else
                <p class="text-center">{{ __('No interests selected yet') }}</p>
            @endif
        </ul>
    </div>

    <div class="text-block mb-3">
        <div class="row d-flex align-items-center mb-3">
            <div class="col-sm-8">
                <h3 class="mb-0">{{ __('Recommended') }}</h3>
            </div>
            <div class="col-sm-4 text-end">
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="{{ __('Search') }}" wire:model="search" type="search">
                </div>
            </div>
        </div>
        <ul class="list-inline">
            @foreach ($recommended as $topic)
                <li class="list-inline-item mb-2 {{ $this->model->hasAnyTopics($topic->id) ? 'd-none' : '' }}" wire:click="attach({{ $topic->id }})">
                    <span class="badge bg-secondary p-2">{{ $topic->name }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
