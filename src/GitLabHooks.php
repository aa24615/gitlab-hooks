<?php

namespace Zyan;

use Zyan\Traits\InteractWithBody;

class GitLabHooks extends HttpClient
{
    use InteractWithBody;

    protected $key;
    protected $body = null;

    public function __construct(string $key = '39b64d67-6d5d-43b7-b7bc-9cc44b57d4fe')
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
        return $this->sendWeWork($key, $this->getBody());
    }
}
