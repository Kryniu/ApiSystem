<?php


namespace App\Util\Sms;


use App\Exception\NotFoundSmsConfigurationException;
use Symfony\Component\Notifier\Bridge\Smsapi\SmsapiTransport;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SmsNotificator
{
    private HttpClientInterface $client;
    private EventDispatcherInterface $dispatcher;

    public function __construct(HttpClientInterface $client, EventDispatcherInterface $dispatcher)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws NotFoundSmsConfigurationException
     * @throws TransportExceptionInterface
     */
    public function send(string $phone, string $subject): SentMessage
    {
        $authToken = $_ENV['SMSAPI_AUTH_TOKEN'];
        $from = $_ENV['SMSAPI_FROM'];
        if (empty($authToken) || empty($from)) {
            throw new NotFoundSmsConfigurationException();
        }
        $smsapiTransport = new SmsapiTransport($authToken, $from, $this->client, $this->dispatcher);

        return $smsapiTransport->send(new SmsMessage($phone, $subject));
    }
}