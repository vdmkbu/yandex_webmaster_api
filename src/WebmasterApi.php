<?php
namespace Yandex\Webmaster;

use Http\Message\MessageFactory\DiactorosMessageFactory;
use Psr\Http\Client\ClientInterface;

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
        $response = $this->get($uri);

        $data = json_decode($response->getBody());

        return $data->user_id;
    }

    public function getHosts($user_id)
    {
        $uri = self::API_URL . "/user/{$user_id}/hosts/";
        $response = $this->get($uri);

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
        $response = $this->get($uri);

        $data = json_decode($response->getBody());

        return $data->original_texts;
    }

    public function addOriginalText($user_id, $host_id, $content)
    {
        $uri = self::API_URL . "/user/{$user_id}/hosts/{$host_id}/original-texts/";
        $response = $this->post($uri, ['content' => $content]);

        $data = json_decode($response->getBody());

        return $data;
    }

    protected function get($uri)
    {
        $headers = $this->getHttpHeaders();
        $response = $this
                        ->client
                        ->sendRequest(
                            (new DiactorosMessageFactory())
                                ->createRequest('GET', $uri, $headers)
                        );

        return $response;
    }

    protected function post($uri, array $body)
    {
        $headers = $this->getHttpHeaders();
        $response = $this
                        ->client
                        ->sendRequest(
                            (new DiactorosMessageFactory())
                                ->createRequest('POST', $uri, $headers, json_encode($body))
                        );

        return $response;
    }
}