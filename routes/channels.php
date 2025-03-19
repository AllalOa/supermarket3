<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('cashier.{cashierId}', function ($user, $cashierId) {
    return (int) $user->id === (int) $cashierId;
});

Broadcast::channel('cashier.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
