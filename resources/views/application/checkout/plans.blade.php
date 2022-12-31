@extends('layouts.app', [
    'seo_title' => __('Subscribe to find people who share your interests'),
])

@section('content')
    <section class="container py-5">
        <h1 class="text-center">{{ __('Subscribe to find people who share your interests') }}</h1>
        <p class="text-center text-muted">{{ __('Choose a plan to start your group and your community.') }}</p>
        
        <div class="d-flex align-items-center justify-content-center mt-3">
            <label class="form-check-label fs-3 me-2" for="priceSwitch">{{ __('Monthly') }}</label>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input price-toggle" id="priceSwitch" onchange="switchtabs(this)" type="checkbox">
            </div>
            <label class="form-check-label fs-3" for="priceSwitch">{{ __('Yearly') }}</label>
        </div>

        <div class="row">
            <div class="col-12 mx-auto">
                <div class="tab-content">
                    <div class="tab-pane show active" id="monthly-plans" role="tabpanel">
                        <div class="row d-flex align-items-center">
                            @foreach (get_all_plans_available() as $plan)
                                <div class="col mt-4">
                                    @include('application.checkout._plan_card', ['plan' => $plan])
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane" id="yearly-plans" role="tabpanel">
                        <div class="row d-flex align-items-center">
                            @foreach (get_all_plans_available() as $plan)
                                <div class="col mt-4">
                                    @include('application.checkout._plan_card', ['plan' => $plan, 'yearly' => true])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('page_body_scripts')
    <script>
        function switchtabs(elem) {
            var isChecked = elem.checked;
            if (isChecked) {
                document.querySelector('#monthly-plans').classList.remove('show', 'active');
                document.querySelector('#yearly-plans').classList.add('show', 'active');
            } else {
                document.querySelector('#monthly-plans').classList.add('show', 'active');
                document.querySelector('#yearly-plans').classList.remove('show', 'active');
            }
        }
    </script>
@endpush
