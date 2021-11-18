

# zyan/gitlab-hooks

gitlab消息推送 转发给 企业微信与钉钉群机器人等

已支持平台

- [x] 企业微信群机器人    
- [x] 钉钉群机器人
- [x] 飞书群机器人

注:钉钉群请添加一个自定义机器人 关健词为: git

企业微信效果图

![企业微信效果图](wework.png)

钉钉效果图

![钉钉效果图](dingtalk.png)



## 要求

1. php >= 7.2
2. Composer


## 安装

```shell
composer require zyan/gitlab-hooks -vvv
```

## 入门

以laravel为例,创建一个新的控制器 路由为 /test/gitlab   
将url填到gitlab系统钩子

```php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Zyan\GitlabHooks;

class Test extends Controller
{
    public function gitlab()
    {
        $gitlab = new GitlabHooks();
        
        //快速发送给企业微信群机器人
        $res = $gitlab->sendToWeWork('您的企业微信群机器人key');
        
        //快速发送给钉钉群机器人
        //$res = $gitlab->sendToDingTalk('您的钉钉群机器人access_token');
        
        //快速发送给飞书群机器人
        //$res = $gitlab->sendToFeiShu('您的飞书群机器人key');
        
        return response()->json($res->getBody()->getContents());
    }
}
```

## 进阶

```php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Zyan\GitlabHooks;

class Test extends Controller
{
    public function gitlab()
    {
        //定制多个仓库发送不同的群
        $config =[
            //把仓库1跟仓库2发送到群1
            [   
                'project' => ['仓库1','仓库2'],
                'key' => '群1_key',
                'is_at_all' => false, //是否@全体成员 可选
                'at_mobiles' => [], //需要@成员的手机号 可选
                'at_userids' => [], //需要@成员的userid 可选
            ],
            //把仓库2跟仓库3发送到群2
            [
                'project' => ['仓库3','仓库4'],
                'key' => '群2_key',
                'is_at_all' => false, //是否@全体成员 可选
                'at_mobiles' => [], //需要@成员的手机号 可选
                'at_userids' => [], //需要@成员的userid 可选
            ],
            //注意: 飞书群机器人不支持@成员
            //...
        ];
        
        $gitlab = new GitlabHooks($config);
        
        //发送到企业微信群机器人
        $res = $gitlab->app('wework')->send();
        
        //发送到钉钉群机器人
        //$res = $gitlab->app('dingtalk')->send();
        
        
        //发送到飞书群机器人
        //$res = $gitlab->app('feishu')->send();
    
        //如果同时发送给多个群,则返顺多个送发结果
        return response()->json($res);
    }
}
```

### 过滤某些事件(白名单)

```php
$gitlab = new GitlabHooks($config);

$pushObjectKinds = [
    'push',
    'merge_request',
    'tag_push'
];
$gitlab->setPushObjectKinds($pushObjectKinds);

```

默认为

```php
[
    'push',
    'merge_request',
    'tag_push'
];
```
> 注意: 仅对 `send` 方法有效

## 高级

### 自定义提供者

1. 编写更多的群机器人接口,自己写一个类

```php
<?php
namespace Xxxx\Provider;

use Zyan\Client\HttpClient;
use Zyan\Contract\ProviderInterface;
//这里需要继承 HttpClient 与 ProviderInterface 接口
class XxxBot extends HttpClient implements ProviderInterface
{
     /**
     * 推送到某某机器人.
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

        $url = 'https://api.xxxx.com/robot/send?access_token='.$key;

        return $this->postJson($url, $data);
    }
}
```

2. 注册自定义提供者

```php
use Zyan\GitlabHooks;
use Zyan\Template\Markdown; //发送模板
use Xxx\XxxBot; //你自己写的类

//自定义提供者 仅限进阶模式,请配置您的发送信息
$config = [
    [   
        'project' => ['仓库1','仓库2'],
        'key' => '群1_key',
        'is_at_all' => false, //是否@全体成员 可选
        'at_mobiles' => [], //需要@成员的手机号 可选
        'at_userids' => [], //需要@成员的userid 可选
    ]
];
$gitlab = new GitlabHooks($config); 
$gitlab->setPorovider([
    'xxxbot' => XxxBot::class 
]);

$res = $gitlab->app('xxxbot')->send();

```

### 自定义消息模板
钉钉与企业微信默认使用 `Zyan\Template\Markdown` 消息模板   
由于飞书暂不支持 `markdown` 格式,所以飞书默认使用 `Zyan\Template\Text` 消息模板   
您可以编写自定义模板 或 覆盖默认的模板

1. 编写一个消息模板类

```php
<?php

namespace Zyan\Template;

use Zyan\Body;
use Zyan\Contract\TemplateInterface;

//这里需要继承 Body 与 TemplateInterface 接口

class Text extends Body implements TemplateInterface
{
    public function getMessage(): string
    {
        $objectKind = $this->getObjectKind();
        switch ($objectKind) {
            case 'push':
                $branch = $this->getBranch();
                break;
            case 'merge_request':
                $branch = $this->getSourceBranch() .'→'. $this->getTargetBranch();
                break;
            default:
                $branch = '';
                break;
        }

        $content = '# 代码推送通知  ';
        $content .= "\n--------------------------------------";
        $content .= "\n> 开发 : " . $this->getUserName() . "   ";
        $content .= "\n> 项目 : " . $this->getProjectName() . "   ";
        $content .= "\n> 分支 : " . $branch . "   ";
        $content .= "\n> 事件 : " . $objectKind . "   ";

        $state = $this->getState();
        if (!empty($state)) {
            $content .= "\n> 状态 : " . $state . "   ";
        }

        if (!empty($message)) {
            $content .= "\n";
            $content .= "\n " . $message ;
        }

        return $content;
    }
}
```

2. 注册自定义模板


```php
use Zyan\GitlabHooks;
use Zyan\Template\Text; //发送模板

//自定义提供者 仅限进阶模式,请配置您的发送信息
$config = [
    [   
        'project' => ['仓库1','仓库2'],
        'key' => '群1_key',
        'is_at_all' => false, //是否@全体成员 可选
        'at_mobiles' => [], //需要@成员的手机号 可选
        'at_userids' => [], //需要@成员的userid 可选
    ]
];
$gitlab = new GitlabHooks($config); 
$gitlab->setTemplate([
    'xxxbot' => Text::class, 
    //如果需要对其他机器人使用这个模板,请填写多个
    'bot2' => Text::class
    //...
]);

$res = $gitlab->app('xxxbot')->send();

```

## 参与贡献

1. fork 当前库到你的名下
2. 在你的本地修改完成审阅过后提交到你的仓库
3. 提交 PR 并描述你的修改，等待合并

## 关于作者

- 作者博客 [blog.php127.com](http://blog.php127.com)
- PHP交流群(有问必答) [324098841](https://jq.qq.com/?_wv=1027&k=uw4uy0r3)

## License

[MIT license](https://opensource.org/licenses/MIT)
