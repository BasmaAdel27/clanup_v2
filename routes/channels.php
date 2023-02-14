<?php
\Illuminate\Support\Facades\Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});\
Illuminate\Support\Facades\Broadcast::channel('groups.{group}', function ($user,\App\Models\Group $group) {
  if ($user->id != auth()->id()) {
      return $group->hasUser($user->id);
  }
});

//\Illuminate\Support\Facades\Broadcast::channel('chat', function ($user) {
//    return true;
//});