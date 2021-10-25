<?php

namespace Zyan;

class Rules
{
    protected $body = null;
    protected $config = null;

    public function __construct(Body $body, array $config)
    {
        $this->body = $body;
        $this->config = $config;
    }

    public function getSnedList(): array
    {
        $project = $this->body->getProject();
        $list = [];
        foreach ($this->config as $val) {
            if (in_array($project, $val['project'])) {
                $list[] = [
                    'key' => $val['key'],
                    'is_at_all' => $val['is_at_all'] ?? false,
                    'at_userids' => $val['at_userids'] ?? [],
                    'at_mobiles' => $val['at_mobiles'] ?? [],
                ];
            }
        }

        return $list;
    }
}
