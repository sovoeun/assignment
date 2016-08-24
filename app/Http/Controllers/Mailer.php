<?php

namespace App\Http\Controllers;

class Mailer
{
    private $sender;
    private $recipients;
    private $subject;
    private $body;

    public function __construct($sender)
    {
        $this->sender = $sender;
        $this->recipients = array();
    }

    public function addRecipients($recipient)
    {
        array_push($this->recipients, $recipient);
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function send()
    {
        foreach ($this->recipients as $recipient) 
        {
            $result = mail($recipient, $this->subject, $this->body, "From: ".$this->sender);

            if($result)
                return "Mail successfully sent to ". $recipient. "<br />";
        }
    }
}
