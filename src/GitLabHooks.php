<?php

namespace Zyan;

use Zyan\Provider\DingTalk;
use Zyan\Provider\WeWork;
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
    protected $app = null;

    protected $porovider = [
        'dingtalk' => DingTalk::class,
        'wework' => WeWork::class
    ];

    /**
     * GitLabHooks constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function app(string $name){
        if(!isset($this->porovider[$name])){
            throw new \Exception("This app doesn't exist");
        }
        $this->app = new $this->porovider[$name];
        return $this;
    }

    public function getApp(){
        if ($this->app) {
            return $this->app;
        }

        throw new \Exception('No app set');
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
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function send(): \Psr\Http\Message\ResponseInterface
    {
        if (is_null($this->app)) {
            throw new \Exception('No app set');
        }

        $this->app->send($key, $this->getBody());
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
