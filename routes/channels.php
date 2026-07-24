<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin.chat', function ($user) {
    return $user && ($user->is_admin === true || $user->is_admin === 1);
});
