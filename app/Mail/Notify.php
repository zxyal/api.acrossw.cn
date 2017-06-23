<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Notify extends Mailable
{
    use Queueable, SerializesModels;

    protected $userInfo;

    /**
     *
     * Notify constructor.
     * @param array $userInfo
     */
    public function __construct(Array $userInfo)
    {
        $this->userInfo = $userInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.notify.notify')
                    ->with([
                        'userName' => $this->userInfo['name'],
                    ]);
    }
}
