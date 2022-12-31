@if (\App\Models\SystemSetting::isAnySocialLoginActive())
    <div class="hr-text">or</div>
    <div class="card-body">
        <div class="row">
            @if (\App\Models\SystemSetting::isFacebookLoginActive())
                <div class="col-12 mt-2">
                    <a class="btn btn-white w-100" href="{{ route('social_login.redirect', ['provider' => 'facebook']) }}">
                        <i class="fab fa-facebook me-2"></i>
                        {{ __('Login with Facebook') }}
                    </a>
                </div>
            @endif

            @if (\App\Models\SystemSetting::isGoogleLoginActive())
                <div class="col-12 mt-2">
                    <a class="btn btn-white w-100" href="{{ route('social_login.redirect', ['provider' => 'google']) }}">
                        <i class="fab fa-google me-2"></i>
                        {{ __('Login with Google') }}
                    </a>
                </div>
            @endif

            @if (\App\Models\SystemSetting::isTwitterLoginActive())
                <div class="col-12 mt-2">
                    <a class="btn btn-white w-100" href="{{ route('social_login.redirect', ['provider' => 'twitter']) }}">
                        <i class="fab fa-twitter me-2"></i>
                        {{ __('Login with Twitter') }}
                    </a>
                </div>
            @endif

            @if (\App\Models\SystemSetting::isLinkedinLoginActive())
                <div class="col-12 mt-2">
                    <a class="btn btn-white w-100" href="{{ route('social_login.redirect', ['provider' => 'linkedin']) }}">
                        <i class="fab fa-linkedin me-2"></i>
                        {{ __('Login with Linkedin') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif
