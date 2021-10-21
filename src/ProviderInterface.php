<?php

namespace Zyan;

interface ProviderInterface
{
    public function send(string $key, string $text, bool $isAtAll = false, array $atMobiles = [], array $atUserIds = []): \Psr\Http\Message\ResponseInterface;
}
