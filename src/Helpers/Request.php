<?php

namespace Yandex\Webmaster\Helpers;

use Http\Message\MessageFactory\DiactorosMessageFactory;

class Request
{
    protected $headers;

    protected function __construct($headers)
    {
        $this->headers = $headers;
    }
    public static function init($headers)
    {
        return new static($headers);
    }

    public function get($uri)
    {
        return (new DiactorosMessageFactory())->createRequest('GET', $uri, $this->headers);
    }

    public function post($uri, $body)
    {
        return (new DiactorosMessageFactory())->createRequest('POST', $uri, $this->headers, $body);
    }
}