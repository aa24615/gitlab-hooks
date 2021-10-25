<?php

namespace Zyan\Provider;

use Zyan\Client\HttpClient;
use Zyan\Contract\ProviderInterface;

/**
 * Class DingTalk.
 *
 * @package Zyan\Client
 *
 * @author 读心印 <aa24615@qq.com>
 */
class FeiShu extends HttpClient implements ProviderInterface
{
    /**
     * 推送到钉钉机器人.
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
    public function send(string $key, string $text, bool $isAtAll = false, array $atMobiles = [], array $atUserIds = []): \Psr\Http\Message\ResponseInterface
    {
        $data = [
            "msg_type" => "text",
            "content" => [
                "text" => $text,
            ],
            "at" => [
                "atMobiles" => $atMobiles,
                "atUserIds" => $atUserIds,
                "isAtAll" => $isAtAll
            ]
        ];

        $url = 'https://open.feishu.cn/open-apis/bot/v2/hook/'.$key;

        return $this->postJson($url, $data);
    }
}
