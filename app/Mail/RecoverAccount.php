<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

//Dependências adicionadas
use App\Models\User; //Model Usuario

class RecoverAccount extends Mailable
{
    use Queueable, SerializesModels;

    private $user, $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.recover-password', [
            'user' => $this->user,
            'token' => $this->token
        ]);
    }
}
