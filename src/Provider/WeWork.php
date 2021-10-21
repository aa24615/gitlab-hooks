<?php

namespace Zyan\Provider;

use Zyan\Client\HttpClient;
use Zyan\ProviderInterface;

/**
 * Class WeWork.
 *
 * @package Zyan\Client
 *
 * @author 读心印 <aa24615@qq.com>
 */
class WeWork extends HttpClient implements ProviderInterface
{
    /**
     * 推送到企微机器人.
     *
     * @param string $key
     * @param string $text
     * @param bool $isAtAll
     * @param array $atMobiles
     * @param array $atUserIds
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function send(string $key, string $text, $isAtAll = false, array $atMobiles = [], array $atUserIds = []): \Psr\Http\Message\ResponseInterface
    {
        $data = [
            "msgtype" => "markdown",
            "markdown" => [
                "content" => $text,
                "mentioned_list" => $isAtAll ? ['@all'] : $atUserIds,
                "mentioned_mobile_list" => $isAtAll ? ['@all'] : $atMobiles,
            ]
        ];

        $url = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key='.$key;

        return $this->postJson($url, $data);
    }
}
