<?php

namespace App\SMS;

use App\Exceptions\WrongSmsApiResponseException;

class SmsSender implements SenderInterface
{
    private const DEFAULT_RESPONSE_FORMAT = 'json';
    private const API_URL = 'https://api.smsapi.pl/sms.do';
    private const API_BACKUP_URL = 'https://api2.smsapi.pl/sms.do';

    protected $sender;
    protected $format;
    private $tokenApiOauth;
    private $config;

    public function __construct(string $tokenApiOauth, string $sender)
    {
        $this->tokenApiOauth = $tokenApiOauth;
        $this->sender = $sender;
        $this->format = self::DEFAULT_RESPONSE_FORMAT;
        $this->config = $this->getConfig();
    }

    public function send(string $message, string $to): void
    {
        try {
            $this->doSend(self::API_URL, $message, $to);
        } catch (WrongSmsApiResponseException $exception) {
            $this->doSend(self::API_BACKUP_URL, $message, $to);
        }
    }

    private function doSend(string $url, string $message, string $to): void
    {
        $this->config['message'] = $message;
        $this->config['to'] = $to;

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $this->config);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $this->tokenApiOauth"
        ));

        $content = curl_exec($c);
        $http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);
        if ($http_status !== 200) {
            throw new WrongSmsApiResponseException();
        }
        curl_close($c);
    }

    private function getConfig(): array
    {
        return array(
            'from' => $this->sender,
            'format' => $this->format,
        );
    }
}
