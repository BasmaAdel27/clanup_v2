@if(count($events) > 0)
    <div class="row">
        <div class="swiper-container swiper-init" data-swiper='{"slidesPerView":4,"spaceBetween":20,"roundLengths":true,"breakpoints":{"991":{"slidesPerView":2},"565":{"slidesPerView":1}},"navigation":{"nextEl": ".slideNext-btn","prevEl": ".slidePrev-btn"}}'>
            <div class="swiper-wrapper pb-5">
                @foreach ($events as $event)
                    <div class="swiper-slide h-auto px-2">
                        @include('application.groups.events._event_card', ['event' => $event, 'list_view' => false])
                    </div>
                @endforeach
            </div>
            <div class="slide-arrow slide-arrow__prev slidePrev-btn"><i class="fas fa-chevron-left"></i></div>
            <div class="slide-arrow slide-arrow__next slideNext-btn"><i class="fas fa-chevron-right"></i></div>
        </div>
    </div>
@else
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between border rounded px-5 py-4">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/homepage/no-events.svg') }}" width="150" />
                    <p class="fs-3 fw-bold ms-4">{{ __('There are no events right now. Find one today.') }}</p>
                </div>
                <a href="{{ route('find', ['source' => 'EVENTS']) }}" class="btn btn-primary">{{ __('Discover Events') }}</a>
            </div>
        </div>
    </div>
@endif