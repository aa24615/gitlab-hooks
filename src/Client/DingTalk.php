<?php


namespace Zyan\Client;


use GuzzleHttp\Client;

class DingTalk implements ClientInterface
{
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
    public function send(string $key, string $text,$isAtAll = false, array $atMobiles = [], array $atUserIds = []): string
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

        return $res->getBody()->getContents();
    }
}