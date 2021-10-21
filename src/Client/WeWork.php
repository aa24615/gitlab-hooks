<?php


namespace Zyan\Client;

use GuzzleHttp\Client;

class WeWork implements ClientInterface
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
    public function send(string $key, string $text, $isAtAll = false, array $atMobiles = [], array $atUserIds = []): string
    {
        $data = [
            "msgtype" => "markdown",
            "markdown" => [
                "content" => $text,
                "mentioned_list" =>  $isAtAll ? ['@all'] : $atUserIds,
                "mentioned_mobile_list" => $isAtAll ? ['@all'] : $atMobiles,
            ]
        ];

        $url = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key='.$key;
        $http = new Client();
        $res = $http->post($url, [
            'body' => \json_encode($data)
        ]);

        return $res->getBody()->getContents();
    }
}