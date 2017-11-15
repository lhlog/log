<?php
/**
 * @desc   日志存储接口
 * @author hedonghong 2017/11/10 8:56
 */

namespace Lhlog\IBase;

interface IStorage
{
    /**
     * @desc  调度方法，调度初始化方法等等
     * @param $level   日志级别
     * @param $trace   日志发生位置
     * @param $message 日志信息
     * @param $context 日志额外信息
     * @return mixed
     */
    public function process($level, $trace, $message, $context);

    /**
     * @desc  初始化方法 对日志参数设置进行初始化
     * @param array $config 日志配置信息
     * @return mixed
     */
    public function init(Array $config);

    /**
     * @desc  日志写入方法
     * @param $log 日志信息
     * @return mixed
     */
    public function write($log);

    /**
     * @desc  日志读取方法
     * @param $level 读取的日志级别
     * @param string $order 排序，倒叙或者顺序等等
     * @param int $page 页数
     * @param int $size 每页总数
     * @return mixed
     */
    public function read($level, $order='', $page=1, $size=100);

    /**
     * @desc  日志操作之后的收尾方法
     * @return mixed
     */
    public function close();
}