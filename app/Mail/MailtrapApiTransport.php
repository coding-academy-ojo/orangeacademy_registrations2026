<?php

namespace App\Mail;

use Illuminate\Support\Str;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Stringable;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

class MailtrapApiTransport implements Stringable, TransportInterface
{
    protected $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        $email = $message instanceof Email ? $message : $this->createEmailFromRaw($message);

        $from = $email->getFrom();
        $fromAddress = $from[0]?->getAddress() ?: 'noreply@orangeacademy.com';
        $fromName = $from[0]?->getName() ?: 'Orange Academy';

        $mailtrapEmail = (new MailtrapEmail())
            ->from(new Address($fromAddress, $fromName))
            ->subject($email->getSubject());

        foreach ($email->getTo() as $address) {
            $mailtrapEmail->to(new Address($address->getAddress(), $address->getName()));
        }

        foreach ($email->getCc() as $address) {
            $mailtrapEmail->cc(new Address($address->getAddress(), $address->getName()));
        }

        foreach ($email->getBcc() as $address) {
            $mailtrapEmail->bcc(new Address($address->getAddress(), $address->getName()));
        }

        if ($email->getTextBody()) {
            $mailtrapEmail->text($email->getTextBody());
        }

        if ($email->getHtmlBody()) {
            $mailtrapEmail->html($email->getHtmlBody());
        }

        try {
            $response = MailtrapClient::initSendingEmails(
                apiKey: $this->apiKey
            )->send($mailtrapEmail);
            
            \Illuminate\Support\Facades\Log::info('Mailtrap API Key used: ' . $this->apiKey);
            \Illuminate\Support\Facades\Log::info('Mailtrap response: ' . json_encode(ResponseHelper::toArray($response)));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mailtrap error - API Key: ' . $this->apiKey . ' - Error: ' . $e->getMessage());
            throw $e;
        }

        return new SentMessage($message, $envelope ?? Envelope::create($message));
    }

    protected function createEmailFromRaw(RawMessage $message): Email
    {
        $email = new Email();
        $str = Str::of($message->toString());
        
        if ($str->contains('Subject:')) {
            $subject = $str->after('Subject:')->before("\r\n")->trim()->toString();
            $email->subject($subject);
        }
        
        if ($str->contains("\r\n\r\n")) {
            $body = $str->after("\r\n\r\n")->toString();
            $email->text($body);
        }
        
        return $email;
    }

    public function __toString(): string
    {
        return 'mailtrap-api';
    }
}
