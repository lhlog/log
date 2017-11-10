<?php
namespace Lhlog\Models;
class Log {
    // 额外信息
    public $content;
    // 创建时间( 因为该字段同时为表字段,所以用 _ 分割 )
    public $create_time;
    // 日志级别
    public $level;
    // 日志信息
    public $message;
    // 位置
    public $location;

    public function __construct( $message, $location, $level, $content, $createTime = null )
    {
        $this->content = $content;
        $this->create_time = null === $createTime ? date( 'Y-m-d H:i:s' ) : '';
        $this->level = $level;
        $this->message = $message;
        $this->location = $location;
    }
}