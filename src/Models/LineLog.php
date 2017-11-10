<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 21:27
 */

namespace Lhlog\Models;


class LineLog extends Log
{
    public function __construct($message, $location, $level, $content, $createTime)
    {
        parent::__construct($message, $location, $level, $content, $createTime);
    }

    public function format()
    {
        return vsprintf("[%s] %s [%s] %s %s" . PHP_EOL . PHP_EOL,
            [$this->create_time,
            $this->location,
            $this->level,
            $this->message,
            $this->content]
        );
    }
}