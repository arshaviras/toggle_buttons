<?php

namespace App\Listeners;

use App\Events\SendLoginLinkEvent;
use App\Models\User;
use C6Digital\PasswordlessLogin\Mail\LoginLink;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLoginLink
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
    public function handle(SendLoginLinkEvent $event): void
    {
        Mail::to($event->student->email)
            ->send(new LoginLink($event->student));

        $event->student->is_sent = True;
        $event->student->save();

        Notification::make()
            ->title(__('E-mail was sent!'))
            ->icon('heroicon-m-envelope')
            ->color(Color::Purple)
            ->iconColor(Color::Purple)
            ->seconds(5)
            ->body(__('E-mail was sent to :username from group :group with E-mail :email !', ['username' => $event->student->username, 'group' => $event->student->group->name, 'email' => $event->student->email]))
            ->send()
            ->sendToDatabase(User::find(2));
    }
}
