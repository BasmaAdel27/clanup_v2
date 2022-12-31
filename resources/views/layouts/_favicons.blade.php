@if (get_system_setting('application_favicon'))
    <link rel="icon" href="{{ get_system_setting('application_favicon') }}" sizes="any" />
    <link rel="shortcut icon" href="{{ get_system_setting('application_favicon') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ get_system_setting('application_favicon') }}" />
    <link rel="apple-touch-icon" sizes="152x152" href="{{ get_system_setting('application_favicon') }}" />
    <link rel="apple-touch-icon" sizes="167x167" href="{{ get_system_setting('application_favicon') }}" />
    <link rel="shortcut icon" sizes="128x128" href="{{ get_system_setting('application_favicon') }}" />
    <link rel="shortcut icon" sizes="196x196" href="{{ get_system_setting('application_favicon') }}" />
@endif
