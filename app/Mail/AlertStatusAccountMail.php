<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertStatusAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;
        switch ($this->type){
            case REJECT:
                return $this->subject($this->data['subject'])->view('email.reject-register', compact('data'));
            case CONFIRM:
                return $this->subject($this->data['subject'])->view('email.confirm-register', compact('data'));
            case STOP_USING:
                return $this->subject($this->data['subject'])->view('email.stop-register', compact('data'));
        }
    }
}
