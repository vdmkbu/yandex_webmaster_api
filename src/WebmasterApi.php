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

        $data = json_decode($response->getBody());

        return $data->user_id;
    }

    public function getHosts($user_id)
    {
        $uri = self::API_URL . "/user/{$user_id}/hosts/";
        $headers = $this->getHttpHeaders();

        $request = Request::init($headers)->get($uri);
        $response = $this->client->sendRequest($request);

        $data = json_decode($response->getBody());

        return $data;
    }

    public function getHostId($hosts_data, $name)
    {

        foreach($hosts_data->hosts as $key => $object) {
            if(!strpos($object->host_id, $name) === false)
                return $object->host_id;
        }
    }

    public function getOriginalTexts($user_id, $host_id)
    {
        $uri = self::API_URL . "/user/{$user_id}/hosts/{$host_id}/original-texts/";
        $headers = $this->getHttpHeaders();

        $request = Request::init($headers)->get($uri);
        $response = $this->client->sendRequest($request);

        $data = json_decode($response->getBody());

        return $data->original_texts;
    }

    public function addOriginalText($user_id, $host_id, $content)
    {
        $uri = self::API_URL . "/user/{$user_id}/hosts/{$host_id}/original-texts/";
        $headers = $this->getHttpHeaders();

        $request = Request::init($headers)->post($uri, json_encode(['content' => $content]));
        $response = $this->client->sendRequest($request);

        $data = json_decode($response->getBody());

        return $data;
    }
}