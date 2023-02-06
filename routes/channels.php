<?php
\Illuminate\Support\Facades\Broadcast::channel('chat', function ($user) {

    return \Illuminate\Support\Facades\Auth::check();
});
