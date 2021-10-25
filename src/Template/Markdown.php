<?php

namespace Zyan\Template;

use Zyan\Body;
use Zyan\Contract\TemplateInterface;

class Markdown extends Body implements TemplateInterface
{
    /**
     * color.
     *
     * @param string $text
     * @param string $color
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    private function color(string $text, $color = '#0066CC'): string
    {
        return '<font color="'.$color.'">**'.$text.'**</font>';
    }

    public function getMessage(): string
    {
        $objectKind = $this->getObjectKind();
        switch ($objectKind) {
            case 'push':
                $branch = $this->color($this->getBranch());
                break;
            case 'merge_request':
                $branch = $this->color($this->getSourceBranch(), '#D200D2')
                    .'→'.
                    $this->color($this->getTargetBranch(), '#5E005E');
                break;
            default:
                $branch = '';
                break;
        }

        $content = '## 代码推送通知  ';
        $content .= "\n> 开发 : " . $this->getUserName() . "   ";
        $content .= "\n> 项目 : " . $this->getProjectName() . "   ";
        $content .= "\n> 分支 : " . $branch . "   ";
        $content .= "\n> 事件 : " . $this->color($objectKind, '#4b0082') . "   ";

        $state = $this->getState();
        if (!empty($state)) {
            $content .= "\n> 状态 : " . $this->color($state, '#00CACA') . "   ";
        }

        if (!empty($message)) {
            $content .= "\n";
            $content .= "\n " . $message ;
        }

        return $content;
    }
}
