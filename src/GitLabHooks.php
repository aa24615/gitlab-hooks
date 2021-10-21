<?php

namespace Zyan;

use Zyan\Client\DingTalk;
use Zyan\Client\WeWork;
use Zyan\Traits\InteractWithBody;

class GitLabHooks extends HttpClient
{
    use InteractWithBody;

    protected $key;
    protected $body = null;

    public function __construct($config)
    {
        $this->key = $key;
    }

    public function setBody(string $content): self
    {
        $this->body = $content;

        return $this;
    }

    public function getBody(): string
    {
        if ($this->body) {
            return $this->getText($this->body);
        }
        throw new \Exception('No content set');
    }


    public function sendToWeWork(string $key): string
    {
        $client = new WeWork();
        return $client->send($key, $this->getBody());
    }

    public function sendToDingTalk(string $key): string
    {
        $client = new DingTalk();
        return $client->send($key, $this->getBody());
    }
}
