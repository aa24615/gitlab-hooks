<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zyan\GitlabHooks;
use Zyan\Provider\DingTalk;
use Zyan\Provider\WeWork;

class GitLabHooksTest extends TestCase
{
    public function test_app(){
        $gitlab = new GitLabHooks();
        $gitlab->app('wework');

        $this->assertInstanceOf(WeWork::class,$gitlab->getApp());

        $gitlab->app('dingtalk');

        $this->assertInstanceOf(DingTalk::class,$gitlab->getApp());

    }
    public function test_send_to_wework()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody('测试')->sendToWeWork('39b64d67-6d5d-43b7-b7bc-9cc443b57d4fe');
        var_dump($res->getBody()->getContents());
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class,$res);
        $this->assertInstanceOf("@2",$res->getBody()->getContents());
    }

    public function test_send(){

        $config = [
            'wework' => [
                [
                    'project'=> ['git1','git2'],
                    'key' => '222'
                ],
                [
                    'project'=> ['git1','git2'],
                    'key' => '222'
                ]
            ]
        ];

        $gitlab = new GitLabHooks($config);
        $res = $gitlab->setBody('测试')->sendToWeWork('39b64d67-6d5d-43b7-b7bc-9cc44b57d4fe');
        $this->assertTrue(true);
    }
}
