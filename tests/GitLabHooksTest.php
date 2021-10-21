<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zyan\GitlabHooks;
use Zyan\Provider\DingTalk;
use Zyan\Provider\WeWork;

class GitLabHooksTest extends TestCase
{
    public function test_app()
    {
        $gitlab = new GitLabHooks();
        $gitlab->app('wework');

        $this->assertInstanceOf(WeWork::class, $gitlab->getApp());

        $gitlab->app('dingtalk');

        $this->assertInstanceOf(DingTalk::class, $gitlab->getApp());
    }
    public function test_send_to_wework()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody('{"project":{"name":"test"}}')->sendToWeWork('39b64d67-6d5d-43b7-b7bc-9cc44b57d4fe');
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $res);
        $this->assertSame('{"errcode":0,"errmsg":"ok"}', $res->getBody()->getContents());
    }

    public function test_send()
    {
        $config = [
            [
                'project' => ['git1','git2'],
                'key' => '123'
            ],
            [
                'project' => ['git1','git2'],
                'key' => '456'
            ],
            [
                'project' => ['git4','git2'],
                'key' => '789'
            ]
        ];

        $gitlab = new GitLabHooks($config);
        $res = $gitlab->app('wework')->setBody('{"project":{"name":"test"}}')->send();
        $this->assertSame(0, count($res));

        $gitlab = new GitLabHooks($config);
        $res = $gitlab->app('wework')->setBody('{"project":{"name":"git1"}}')->send();
        $this->assertSame(2, count($res));

        $gitlab = new GitLabHooks($config);
        $res = $gitlab->app('wework')->setBody('{"project":{"name":"git2"}}')->send();
        $this->assertSame(3, count($res));
    }
}
