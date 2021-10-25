<?php

namespace Zyan;

use GuzzleHttp\Psr7\ServerRequest;
use Zyan\Provider\DingTalk;
use Zyan\Provider\WeWork;

/**
 * Class GitLabHooks.
 *
 * @package Zyan
 *
 * @author 读心印 <aa24615@qq.com>
 */
class GitLabHooks
{
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

    public function app(string $name)
    {
        if (!isset($this->porovider[$name])) {
            throw new \Exception("This app doesn't exist");
        }

        $this->app = new $this->porovider[$name]();
        return $this;
    }

    public function getApp()
    {
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
    public function getBody(): Body
    {
        if ($this->body) {
            return new Body($this->body);
        }

        $request = ServerRequest::fromGlobals();

        $this->body = $request->getBody()->getContents();

        if ($this->body) {
            return new Body($this->body);
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
    public function send(): array
    {
        if (is_null($this->app)) {
            throw new \Exception('No app set');
        }

        $body = $this->getBody();

        $rules = new Rules($body, $this->config);
        $list = $rules->getSnedList();

        $res = [];
        foreach ($list as $val) {
            $response = $this->app->send(
                $val['key'],
                $this->getBody()->getContents(),
                $val['is_at_all'],
                $val['at_mobiles'],
                $val['at_userids']
            );
            $res[] = $response->getBody()->getContents();
        }

        return $res;
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
        return $client->send($key, $this->getBody()->getContents());
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
        return $client->send($key, $this->getBody()->getContents());
    }
}
