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
    public function getBody(): object
    {
        return $this->body;
    }

    /**
     * getProjectName.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getProjectName(): string
    {
        return $this->getBody()->project->name ?? '';
    }

    /**
     * setProjectName.
     *
     * @param string $value
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function setProjectName(string $value): self
    {
        $this->getBody()->project->name = $value;
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
        $body = $this->getBody();

        $commits = $body->commits ?? [];

        $count = $body->total_commits_count ?? 0;

        if ($count == 0) {
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

    /**
     * getState.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getState(): string
    {
        $body = $this->getBody();

        $action = $body->object_attributes->action ?? '';

        $state = '';
        switch ($action) {
            case 'open':
                $state = '提交合并';
                break;
            case 'merge':
                $state = "完成合并";
                break;
            case 'close':
                $state = "关闭合并";
                break;
        }

        return $state;
    }


    /**
     * getObjectKind.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getObjectKind(): string
    {
        return $this->getBody()->object_kind ?? '';
    }

    /**
     * getUserName.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getUserName(): string
    {
        return $this->getBody()->user->name ?? $this->getBody()->user_name ?? '';
    }

    /**
     * getSourceBranch.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getSourceBranch(): string
    {
        return $this->getBody()->object_attributes->source_branch ?? '';
    }

    /**
     * getTargetBranch.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getTargetBranch(): string
    {
        return $this->getBody()->object_attributes->target_branch ?? '';
    }

    /**
     * getBranch.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getBranch(): string
    {
        $refs = explode('/', $this->getBody()->ref ?? '');
        $branch = end($refs);
        return $branch;
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
        return $this->getMessage();
    }
}
