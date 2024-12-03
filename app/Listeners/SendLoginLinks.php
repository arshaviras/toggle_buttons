<?php

namespace App\Listeners;

use App\Events\SendLoginLinksEvent;
use App\Models\Student;
use App\Models\User;
use App\Mail\LoginLinks;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLoginLinks
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
    public function handle(SendLoginLinksEvent $event): void
    {

        if ($event->student->is_leader) {

            $records = Student::where('group_id', '=', $event->student->group_id)->get();

            Mail::to($event->student->email)
                ->send(new LoginLinks($records));
            foreach ($records as $record) {
                $record->is_sent = True;
                $record->save();
            }

            Notification::make()
                ->title(__('E-mail was sent to Group Leader :group !', ['group' => $event->student->group->name]))
                ->icon('heroicon-m-envelope')
                ->color(Color::Purple)
                ->iconColor(Color::Purple)
                ->seconds(5)
                ->body(__('E-mail was sent to :email from Group Leader :group !', ['group' => $event->student->group->name, 'email' => $event->student->email]))
                ->send()
                ->sendToDatabase(User::find(2));
        } else {
            Notification::make()
                ->title(__('E-mail was NOT sent to Student, because he/she is NOT Group Leader of :group group!', ['group' => $event->student->group->name]))
                ->danger()
                ->seconds(5)
                ->body(__('E-mail was NOT sent to  Student with email :email , because he/she is NOT Group Leader of :group  group!', ['group' => $event->student->group->name, 'email' => $event->student->email]))
                ->send()
                ->sendToDatabase(User::find(2));
        }
    }
}
