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
        $this->assertSame('test', $body->getProjectName());

        $body->setProjectName('git123');

        $this->assertSame('git123', $body->getProjectName());
    }
}
