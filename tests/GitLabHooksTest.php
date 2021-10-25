<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zyan\GitlabHooks;
use Zyan\Provider\DingTalk;
use Zyan\Provider\FeiShu;
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

        $gitlab->app('feishu');

        $this->assertInstanceOf(FeiShu::class, $gitlab->getApp());
    }

    public function test_send_to_wework_push()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody(file_get_contents(__DIR__.'/files/push.json'))->sendToWeWork('fface22f-c574-4154-941b-6d128535e156');
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $res);
        $this->assertSame('{"errcode":0,"errmsg":"ok"}', $res->getBody()->getContents());
    }


    public function test_send_to_wework_merge_request()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody(file_get_contents(__DIR__.'/files/merge_request.json'))->sendToWeWork('fface22f-c574-4154-941b-6d128535e156');
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $res);
        $this->assertSame('{"errcode":0,"errmsg":"ok"}', $res->getBody()->getContents());
    }

    public function test_send_to_dingtalk_push()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody(file_get_contents(__DIR__.'/files/push.json'))->sendToDingTalk('d1a574564774f9680953aee7df47819dbb0baba29457f7934df883a1d784d99b');
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $res);
        $this->assertSame('{"errcode":0,"errmsg":"ok"}', $res->getBody()->getContents());
    }

    public function test_send_to_dingtalk_merge_request()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody(file_get_contents(__DIR__.'/files/merge_request.json'))->sendToDingTalk('d1a574564774f9680953aee7df47819dbb0baba29457f7934df883a1d784d99b');
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $res);
        $this->assertSame('{"errcode":0,"errmsg":"ok"}', $res->getBody()->getContents());
    }

    public function test_send_to_feishu_push()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody(file_get_contents(__DIR__.'/files/push.json'))->sendToFeiShu('4dfc3077-1d55-4897-8e90-0c08031e5179');
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $res);
        $this->assertSame('{"Extra":null,"StatusCode":0,"StatusMessage":"success"}', $res->getBody()->getContents());
    }

    public function test_send_to_feishu_merge_request()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody(file_get_contents(__DIR__.'/files/merge_request.json'))->sendToFeiShu('4dfc3077-1d55-4897-8e90-0c08031e5179');
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $res);
        $this->assertSame('{"Extra":null,"StatusCode":0,"StatusMessage":"success"}', $res->getBody()->getContents());
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
