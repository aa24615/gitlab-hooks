<?php

namespace Zyan\Client;

use GuzzleHttp\Client;

/**
 * Class DingTalk.
 *
 * @package Zyan\Client
 *
 * @author 读心印 <aa24615@qq.com>
 */
class DingTalk extends HttpClient implements ClientInterface
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
            "msgtype" => "markdown",
            "markdown" => [
                "title" => "gitLab通知",
                "text" => $text,
            ],
            "at" => [
                "atMobiles" => $atMobiles,
                "atUserIds" => $atUserIds,
                "isAtAll" => $isAtAll
            ]
        ];

        $url = 'https://oapi.dingtalk.com/robot/send?access_token='.$key;
        $http = new Client();
        $res = $http->post($url, [
            'body' => \json_encode($data)
        ]);

        return $res;
    }
}
