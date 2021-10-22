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

    /**
     * getBody.
     *
     * @return object
     *
     * @author 读心印 <aa24615@qq.com>
     */
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

    /**
     * getCommits.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getCommits()
    {
        $body = $this->body;

        $commits = $body->commits ?? [];

        $count = $body->total_commits_count ?? 0;

        if($count==0){
            return '';
        }
        $text = '共提交'.$count."次     ";
        foreach ($commits as $key => $val) {
            if ($key > 3) {
                $text .= "\n ...";
                break;
            }
            $text .= "\n ".$val->author->name.":".$val->message;
        }

        return $text;
    }

    public function getState()
    {
        $body = $this->body;

        $action = $body->object_attributes->action ?? '';

        $state = '';
        switch ($action) {
            case 'open':
                $state = '提交合并';
                break;
            case 'merge':
                $state = "完成";
                break;
        }

        return $state;
    }



    public function sendBody()
    {
        $body = $this->getBody();

        $object_kind = $body->object_kind ?? '';
        $user_name = $body->user->name ?? $body->user_name ?? '';
        $res_name = $body->project->name ?? '';

        switch ($object_kind) {
            case 'push':
                $branch = $this->color(end(explode('/', $body->ref ?? '')));
                break;
            case 'merge_request':
                $branch = $this->color($body->object_attributes->source_branch ?? '', '#D200D2')
                    .'→'.
                    $this->color($body->object_attributes->target_branch ?? '', '#5E005E');
                break;
            default:
                $branch = '';
                break;
        }



        $message = $this->getCommits();

        $content = '## 代码推送通知  ';
        $content .= "\n> 开发 : " . $user_name . "   ";
        $content .= "\n> 项目 : " . $res_name . "   ";
        $content .= "\n> 分支 : " . $branch . "   ";
        $content .= "\n> 事件 : " . $this->color($object_kind, '#4b0082') . "   ";

        $state = $this->getState();
        if (!empty($state)) {
            $content .= "\n> 状态 : " . $this->color($state, '#00CACA') . "   ";
        }

        $content .= "\n";
        $content .= "\n " . $message ;

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

    /**
     * color.
     *
     * @param string $text
     * @param string $color
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    private function color(string $text, $color = '#0066CC'): string
    {
        return '<font color="'.$color.'">**'.$text.'**</font>';
    }
}
