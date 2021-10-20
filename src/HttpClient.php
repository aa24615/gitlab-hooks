<?php

namespace Zyan;

use GuzzleHttp\Client;

class HttpClient
{
    /**
     * 推送到企微机器人.
     *
     * @param string $key
     * @param string $text
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function sendWeWork(string $key, string $text): string
    {
        $data = [
            "msgtype" => "markdown",
            "markdown" => [
                "content" => $text
            ]
        ];

        $url = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key='.$key;
        $http = new Client();
        $res = $http->post($url, [
            'body' => \json_encode($data)
        ]);

        return $res->getBody()->getContents();
    }

    /**
     * 推送到钉钉机器人.
     *
     * @param string $key
     * @param string $text
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function sendDingTalk(string $key, string $text, array $atMobiles = [], array $atUserIds = []): string
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
                "isAtAll" => true
            ]
        ];

        $url = 'https://oapi.dingtalk.com/robot/send?access_token='.$key;
        $http = new Client();
        $res = $http->post($url, [
            'body' => \json_encode($data)
        ]);

        return $res->getBody()->getContents();
    }
}
