<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Zyan\Body;
use Zyan\Rules;

/**
 * Class RulesTest.
 *
 * @package Tests
 *
 * @author 读心印 <aa24615@qq.com>
 */

class RulesTest extends TestCase
{
    public function test_get_send_list()
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
        $body = new Body('{"project":{"name":"test"}}');
        $rules = new Rules($body, $config);
        $this->assertSame([], $rules->getSnedList());

        $body = new Body('{"project":{"name":"git1"}}');
        $rules = new Rules($body, $config);
        $this->assertSame(['123','456'], $rules->getSnedList());

        $body = new Body('{"project":{"name":"git2"}}');
        $rules = new Rules($body, $config);
        $this->assertSame(['123','456','789'], $rules->getSnedList());
    }
}
