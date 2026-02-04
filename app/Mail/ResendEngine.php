<?php

namespace Illuminate\Mail;

use Resend\Client;

class ResendEngine implements Mailer
{
    protected $resend;
    protected $from;

    public function __construct()
    {
        $apiKey = config('services.resend.key');
        $this->resend = new Client($apiKey);
        $this->from = config('mail.from.address');
    }

    public function send($view, array $data = [], $callback = null)
    {
        $message = $this->buildMessage($view, $data);

        $this->resend->emails->send([
            'from' => config('mail.from.address'),
            'to' => [$message['to']],
            'subject' => $message['subject'],
            'html' => $message['body']
        ]);
    }

    protected function buildMessage($view, $data)
    {
        // Extract message data from callback or view
        $message = new Message($this);

        if ($callback) {
            $callback($message);
        }

        return [
            'to' => implode(', ', array_keys($message->getTo())),
            'subject' => $message->getSubject(),
            'body' => view($view, $data)->render()
        ];
    }
}
