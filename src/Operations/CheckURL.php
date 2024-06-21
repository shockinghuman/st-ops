<?php

namespace SH\STOPS\Operations;

use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;

class CheckURL
{
    public $statusCode;
    public $url;

    /**
     * @param $statusCode
     * @param $url
     */
    public function __construct($statusCode, $url)
    {
        $this->statusCode = $statusCode;
        $this->url = $url;
    }

    public static function checkMany(array $urls): array
    {
        $http = HttpClient::create()->withOptions(['resolve' => ['1.1.1.1', '1.0.0.1']]);

        return array_map(function ($url) use ($http) {
            try {
                $response = $http->request('GET', trim("http://$url"));
                return new CheckURL($response->getStatusCode(), $url);
            } catch (TransportException $exception){
                return new CheckURL($exception->getMessage(), $url);
            }
        }, $urls);
    }

    public static function checkOne($url): CheckURL
    {
        $http = HttpClient::create()->withOptions(['resolve' => ['1.1.1.1', '1.0.0.1']]);
        $response = $http->request('GET', 'http://'.$url);
        return new CheckURL($response->getStatusCode(), $url);
    }
}