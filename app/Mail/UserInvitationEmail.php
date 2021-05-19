<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($data)
    {
        $this->invitation_data = $data;
    }

    public function build()
    {
        $invId = $this->invitation_data->invitation_id;
        $invitation = \App\Models\UserInvitation::query()->findOrFail($invId);

        return $this->from('noreply@africahealing.org', 'Invitations Master')
            ->subject('Invitation to the System')
            ->view('mail.invitationemail', ['invitation' => $invitation]);
    }
}
