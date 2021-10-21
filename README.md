

# zyan/gitlab-hooks

gitlab消息推送 转发给 企业微信与钉钉

- [x] 企业微信群机器人    
- [x] 钉钉群机器人

## 要求

1. php >= 7.2
2. Composer


## 安装

```shell
composer require zyan/gitlab-hooks -vvv
```

## 开始

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
        //转发给企业微信群机器人
        $res = $gitlab->sendToWeWork('您的企业微信群机器人key');
        
        //转发给钉钉群机器人
        //$res = $gitlab->sendToDingTalk('您的钉钉群机器人access_token');
        return return response()->json($res->getBody()->getContents());
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
                'key' => '群1_key'
            ],
            //把仓库2跟仓库3发送到群2
            [
                'project' => ['仓库3','仓库4'],
                'key' => '群2_key'
            ],
            //...
        ];
        
        $gitlab = new GitlabHooks($config);
        //转发给企业微信群机器人
        $res = $gitlab->app('wework')->send();
    
        //如果同时发送给多个群,则返顺多个送发结果
        return response()->json($res);
    }
}
```


## 参与贡献

1. fork 当前库到你的名下
2. 在你的本地修改完成审阅过后提交到你的仓库
3. 提交 PR 并描述你的修改，等待合并

## License

[MIT license](https://opensource.org/licenses/MIT)
