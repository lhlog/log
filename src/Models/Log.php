<?php
/**
 * @desc   日志信息模型父类
 * @author luoyuxiong
 * @datetime 2017-11-11T22:24:35+0800
 */

namespace Lhlog\Models;

class Log
{
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

    /**
     * @desc     日志信息模型初始化方法
     * @author   luoyuxiong
     * @datetime 2017-11-11T22:24:35+0800
     * @param    string  $message    日志信息
     * @param    string  $location   记录的位置
     * @param    string  $level      日志级别
     * @param    string  $content    其实信息，需要格式化好
     * @param    string  $createTime 添加时间，需要处理好
     */
    public function __construct($message, $location, $level, $content = '', $createTime = null)
    {
        $this->content     = $content;
        $this->create_time = null === $createTime ? date( 'Y-m-d H:i:s' ) : $createTime;
        $this->level       = $level;
        $this->message     = $message;
        $this->location    = $location;
    }
}