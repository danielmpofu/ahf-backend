<?php

namespace App\Mail;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($data)
    {
        $this->invitation_data = $data;
    }

    public function build()
    {
        $inv_data = $this->invitation_data;//->id;
//        $invitation = UserInvitation::query()->findOrFail($invId);

        return $this->from('noreply@africahealing.org', 'Invitations Master')
            ->subject('Invitation to the System')
            ->view('mail.invitationemail', ['invitation' => $inv_data]);
    }
}
