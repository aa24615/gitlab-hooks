<?php

namespace Zyan;

use Zyan\Client\DingTalk;
use Zyan\Client\WeWork;
use Zyan\Traits\InteractWithBody;

/**
 * Class GitLabHooks.
 *
 * @package Zyan
 *
 * @author 读心印 <aa24615@qq.com>
 */
class GitLabHooks
{
    use InteractWithBody;

    protected $body = null;

    /**
     * GitLabHooks constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * setBody.
     *
     * @param string $content
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function setBody(string $content): self
    {
        $this->body = $content;

        return $this;
    }

    /**
     * getBody.
     *
     * @return string
     *
     * @throws \Exception
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getBody(): string
    {
        if ($this->body) {
            return $this->getText($this->body);
        }
        throw new \Exception('No content set');
    }

    /**
     * send.
     *
     * @param
     *
     * @return
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function send(): \Psr\Http\Message\ResponseInterface
    {
    }

    /**
     * sendToWeWork.
     *
     * @param string $key
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function sendToWeWork(string $key): \Psr\Http\Message\ResponseInterface
    {
        $client = new WeWork();
        return $client->send($key, $this->getBody());
    }

    /**
     * sendToWeWork.
     *
     * @param string $key
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function sendToDingTalk(string $key): \Psr\Http\Message\ResponseInterface
    {
        $client = new DingTalk();
        return $client->send($key, $this->getBody());
    }
}
