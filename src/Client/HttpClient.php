<?php

namespace Zyan\Client;

use GuzzleHttp\Client;

/**
 * Class HttpClient.
 *
 * @package Zyan\Client
 *
 * @author 读心印 <aa24615@qq.com>
 */
class HttpClient
{
    /**
     * postJson.
     *
     * @param string $url
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function postJson(string $url, array $data): \Psr\Http\Message\ResponseInterface
    {
        $http = new Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
        $res = $http->post($url, [
            'body' => \json_encode($data)
        ]);
        return $res;
    }
}
