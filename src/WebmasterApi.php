<?php
namespace Yandex\Webmaster;

use Psr\Http\Client\ClientInterface;
use Yandex\Webmaster\Helpers\Request;

class WebmasterApi
{
    CONST API_URL = "https://api.webmaster.yandex.net/v4";

    protected $client;
    protected $token;

    protected function __construct(ClientInterface $client, $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    public static function init(ClientInterface $client, $token)
    {
        return new static($client, $token);
    }

    protected function getHttpHeaders()
    {
        return [
            'Authorization' => 'OAuth ' . $this->token,
            'Accept' => 'application/json',
            'Content-type' => 'application/json'
        ];
    }

    public function getUserId()
    {
        $uri = self::API_URL . "/user/";
        $headers = $this->getHttpHeaders();

        $request = Request::init($headers)->get($uri);
        $response = $this->client->sendRequest($request);

        $data = json_decode($response->getBody(), true);

        return $data['user_id'];
    }
}