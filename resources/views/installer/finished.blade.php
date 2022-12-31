@extends('layouts.installer')

@section('title', __('Installation Finished'))

@section('content')
	@if(session('message')['dbOutputLog'])
		<p><strong><small>{{ __('Migration &amp; Seed Console Output:') }}</small></strong></p>
		<kbd>
			<pre class="bg-dark"><code>{{ session('message')['dbOutputLog'] }}</code></pre>
		</kbd>
	@endif

	<p><strong><small>{{ __('Application Console Output:') }}</small></strong></p>
	<kbd>
		<pre class="bg-dark"><code>{{ $finalMessages }}</code></pre>
	</kbd>

	<p><strong><small>{{ __('Installation Log Entry:') }}</small></strong></p>
	<kbd>
		<pre class="bg-dark"><code>{{ $finalStatusMessage }}</code></pre>
	</kbd>

	<a class="btn btn-primary px-4" href="{{ url('/') }}">
		{{ __('Click here to exit') }}
	</a>
@endsection
