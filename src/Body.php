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

    /**
     * getContents.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getContents(): string
    {
        return json_encode($this->getBody());
    }
}
