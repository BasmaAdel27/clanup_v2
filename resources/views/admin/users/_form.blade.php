<div class="card p-3 mb-4">
    <div class="row">
        <div class="col-12 card-body">
            <div class="row mb-4">
                <div class="col">
                    <div class="form-group required">
                        <label for="first_name">{{ __('First Name') }}</label>
                        <input name="first_name" type="text" class="form-control" placeholder="{{ __('First Name') }}" value="{{ $user->first_name }}" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group required">
                        <label for="last_name">{{ __('Last Name') }}</label>
                        <input name="last_name" type="text" class="form-control" placeholder="{{ __('Last Name') }}" value="{{ $user->last_name }}" required>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="form-group required">
                        <label for="email">{{ __('Email') }}</label>
                        <input name="email" type="email" class="form-control" placeholder="{{ __('Email') }}" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="phone">{{ __('Phone') }}</label>
                        <input name="phone" type="text" class="form-control" placeholder="{{ __('Phone') }}" value="{{ $user->phone }}">
                    </div>
                </div>
            </div>

            <div class="form-group required mb-4">
                <label for="role">{{ __('Role') }}</label>
                <select name="role"  class="form-control" required>
                    <option value="user" {{ $user->role == 'user' ? 'selected=""' : ''}}>{{ __('User') }}</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected=""' : ''}}>{{ __('Admin') }}</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="plan_id">{{ __('Subscribed Plan') }}</label> 
                <select name="plan_id" class="form-control">
                    <option value="">{{ __('No subscribed plan') }}</option>
                    @foreach(get_all_plans_available() as $option)
                        <option value="{{ $option->id }}" {{ $subscribed_to_plan == $option->id ? 'selected=""' : ''}}>{{ $option->name }}</option>
                    @endforeach
                </select>
                <small>{{ __('Remove plan selection if you want to unsubscribe user') }}</small>
            </div>

            <hr>
            <div class="row mb-5">
                <div class="col-12">
                    <h3>{{ __('Change password') }}</h3>
                    <div class="alert alert-dark mb-3" role="alert">
                        <i class="fas fa-exclamation-circle pe-2"></i>
                        {{ __('Leave password fields empty if you do not want to change user\'s password') }}
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                        <input name="password_confirmation" type="password" class="form-control" placeholder="{{ __('Confirm Password') }}">
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                @if ($user->id)
                    <a class="btn btn-outline-danger delete-confirm" href="{{ route('admin.users.delete', $user->id) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete') }}</a>
                @endif
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
