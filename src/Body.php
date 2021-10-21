<?php

namespace Zyan;

/**
 * Class Body.
 *
 * @package Zyan
 *
 * @author 读心印 <aa24615@qq.com>
 */
class Body
{
    protected $body = null;

    /**
     * Body constructor.
     * @param string $body
     * @throws \Exception
     */
    public function __construct(string $body)
    {
        $this->body = json_decode($body);
        if (!$this->body) {
            throw new \Exception('Body Invalid format');
        }
    }

    public function getBody()
    {
        return $this->body;
    }

    /**
     * getProject.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getProject(): string
    {
        return $this->body->project->name ?? '';
    }

    /**
     * setProject.
     *
     * @param string $value
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function setProject(string $value): self
    {
        $this->body->project->name = $value;
        return $this;
    }

    public function sendBody()
    {
        $body = $this->getBody();

        $event_name = $body->event_type ?? $body->event_name ?? '';
        $user_name = $body->user->name ?? $body->user_name ?? '';
        $res_name = $body->project->name ?? '';
        $branch = $body->project->default_branch ?? '';
        $message = $body->commits[0]->message ?? '';

        $content = '### 代码推送通知  ';
        $content .= " \n>开发者 : **" . $user_name . "**   ";
        $content .= " \n>项目 : " . $res_name . " (<font color=\"warning\">" . $branch . "</font>)    ";
        $content .= " \n>事件 : <font color=\"#4b0082\">" . $event_name . "</font>   ";
        $content .= " \n>内容 : " . $message ."   " ;

        return $content;
    }
    /**
     * getContents.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getContents(): string
    {
        return $this->sendBody();
    }
}
