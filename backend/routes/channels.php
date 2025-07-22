<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tasks.reorder', function ($user) {
    return $user !== null;
});
