<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ asset(get_system_setting('application_logo')) }}" class="logo" alt="{{ get_system_setting('application_name') }}">
@endif
</a>
</td>
</tr>
