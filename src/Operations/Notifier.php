<?php

namespace SH\STOPS\Operations;

use SH\STOPS\Operations\Config;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Notifier
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \Exception
     */
    public static function notify(
        string $message,
        string $title = 'ST-OPS',
        string $type = 'INFO'
    ): int
    {
        $http = \Symfony\Component\HttpClient\HttpClient::createForBaseUri(Config::return('WDJ_API_URL'), [
            'auth_bearer' => Config::return('WDJ_API_NOTIFY'),
        ]);

        $response = $http->request('POST', '/api/notify', [
            'json' => [
                "notifications" => [
                    [
                        "title" => $title,
                        "type" => $type,
                        "message" => $message
                    ]
                ]
            ]
        ]);

        if ($response->getStatusCode() === 200){
            echo $response->getContent();
            return 0;
        }
        echo $response->getContent();
        return 0;
    }
}