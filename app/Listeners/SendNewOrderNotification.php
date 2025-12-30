<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\NewOrderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
        // Obtain admin email from config or user model. 
        // For now, let's hardcode or use a config if available, but usually it's the admin users.
        // Assuming single admin for MVP or defined in config.
        $adminEmail = config('mail.from.address'); // Or a specific 'admin_email' config.

        // Let's use a specific one if we want, or sending to the same as sender for testing.
        // Ideally: User::where('role', 'admin')->each(...)

        \Illuminate\Support\Facades\Mail::to('admin@montecarmelo.com')->send(new NewOrderMail($event->order));
    }
}
