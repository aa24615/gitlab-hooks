<?php

namespace Zyan\Client;

interface ClientInterface
{
    public function send(string $key, string $text, bool $isAtAll = false, array $atMobiles = [], array $atUserIds = []): \Psr\Http\Message\ResponseInterface;
}
