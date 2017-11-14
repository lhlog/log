<?php
/**
 * @desc   文件记录日志模型
 * @author hedonghong 2017-11-08 11:12
 */

namespace Lhlog\Models;


class LineLog extends Log
{
    public function __construct($message, $location, $level, $content, $createTime)
    {
        parent::__construct($message, $location, $level, $content, $createTime);
    }

    /**
     * @desc   日志记录格式化
     * @return string
     */
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