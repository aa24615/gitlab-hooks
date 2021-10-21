<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zyan\Body;

class BodyTest extends TestCase
{
    public function test_get_body()
    {
        $body = new Body('{"project":{"name":"test"}}');
        $obj = $body->getBody();
        $this->assertSame('test', $obj->project->name);
    }
    public function test_get_and_set_project()
    {
        $body = new Body('{"project":{"name":"test"}}');
        $this->assertSame('test', $body->getProject());

        $body->setProject('git123');

        $this->assertSame('git123', $body->getProject());
    }
}
