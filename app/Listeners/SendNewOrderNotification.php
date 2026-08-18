<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\NewOrderMail;

class SendNewOrderNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $admins = \App\Models\User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            return;
        }

        foreach ($admins as $admin) {
            \Illuminate\Support\Facades\Mail::to($admin->email)->send(new NewOrderMail($event->order));
        }
    }
}
