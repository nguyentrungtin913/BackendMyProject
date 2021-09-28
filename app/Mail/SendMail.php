<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public  $title;
    public $customer_details;
    public $order_details;
    public $otp;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public function __construct($title, $otp, $name)
    {
        $this->title = $title; 
        $this->otp   = $otp;
        $this->name  = $name;
    }

    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)
        ->view('customer_mail');
    }
}
