<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('cashier.{cashierId}', function ($user, $cashierId) {
    return (int) $user->id === (int) $cashierId;
});

Broadcast::channel('cashier.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Change parameter name to match your event
// In your routes/channels.php file
Broadcast::channel('supervisor.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user->role === 0;
});
