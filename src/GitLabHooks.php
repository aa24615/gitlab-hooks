<?php

namespace Zyan;

use GuzzleHttp\Psr7\ServerRequest;
use Zyan\Provider\DingTalk;
use Zyan\Provider\FeiShu;
use Zyan\Provider\WeWork;
use Zyan\Template\Markdown;
use Zyan\Template\Text;

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
    protected $name = null;

    protected $porovider = [
        'dingtalk' => DingTalk::class,
        'wework' => WeWork::class,
        'feishu' => FeiShu::class
    ];

    protected $templates = [
        'dingtalk' => Markdown::class,
        'wework' => Markdown::class,
        'feishu' => Text::class
    ];

    protected $pushObjectKinds = [
        'push',
        'merge_request',
        'tag_push'
    ];

    /**
     * setPushObjectKinds.
     *
     * @param array $pushObjectKinds
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function setPushObjectKinds(array $pushObjectKinds): self
    {
        $this->pushObjectKinds = $pushObjectKinds;
        return $this;
    }

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

        $this->name = $name;
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
     * setPorovider.
     *
     * @param array $porovider
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function setPorovider(array $porovider): self
    {
        $this->porovider = $porovider + $this->porovider;
        return $this;
    }
    /**
     * setTemplate.
     *
     * @param array $template
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function setTemplate(array $template): self
    {
        $this->templates = $template + $this->templates;
        return $this;
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
     * @return \Zyan\Contract\TemplateInterface
     *
     * @throws \Exception
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getBody(): \Zyan\Contract\TemplateInterface
    {
        if (!$this->body) {
            $request = ServerRequest::fromGlobals();
            $this->body = $request->getBody()->getContents();
        }

        if ($this->body) {
            if (isset($this->templates[$this->name])) {
                return new $this->templates[$this->name]($this->body);
            }

            return new Markdown($this->body);
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

        if(in_array($body->getObjectKind(),$this->pushObjectKinds)){
            return [];
        }

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
        $this->name = 'wework';
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
        $this->name = 'dingtalk';
        $client = new DingTalk();
        return $client->send($key, $this->getBody()->getContents());
    }

    /**
     * sendToFeiShu.
     *
     * @param string $key
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function sendToFeiShu(string $key): \Psr\Http\Message\ResponseInterface
    {
        $this->name = 'feishu';
        $client = new FeiShu();
        return $client->send($key, $this->getBody()->getContents());
    }
}
