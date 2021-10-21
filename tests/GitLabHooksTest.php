<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zyan\GitlabHooks;

class GitLabHooksTest extends TestCase
{
    public function testSendToWeWork()
    {
        $gitlab = new GitLabHooks();
        $res = $gitlab->setBody('测试')->sendToWeWork('39b64d67-6d5d-43b7-b7bc-9cc44b57d4fe');
        $this->assertTrue(true);
    }
}
