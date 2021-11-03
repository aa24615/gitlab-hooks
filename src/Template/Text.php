<?php

namespace Zyan\Template;

use Zyan\Body;
use Zyan\Contract\TemplateInterface;

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
        $objectKind != 'tag_push' && $content .= "\n> 分支 : " . $branch . "   ";
        $objectKind == 'tag_push' && $content .= "\n> 标签 : " . $this->getTagName() . "   ";
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
