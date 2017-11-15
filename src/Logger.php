<?php
/**
 * @desc   日志处理器类
 * @author hedonghong 2017/11/10 8:35
 */

namespace Lhlog;

use Lhlog\IBase\IStorage;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use \Lhlog\Storage\FileStorage;

class Logger implements LoggerInterface
{
    //日志存储类
    protected $storage;

    public function __construct(IStorage $storage = null)
    {
        $this->storage = (null === $storage) ? new FileStorage() : $storage;
    }

    /**
     * @desc  简单展示日志
     * @param string $level 日志等级，如alert,info等等
     * @param string $order 排序 文件存储只接受时间的倒叙，和顺序，mysql存储可以接受任意字段的排序
     * @param int $page
     * @param int $size
     * @example
     * 文件存储
     * listLogs('', 'DESC', 1, 10)
     * 数据库存储
     * listLogs('info', 'create_time DESC', 1, 10)
     */
    public function listLogs($level, $order, $page=1, $size=100)
    {
        print_r($this->storage->read($level, $order, $page, $size));
    }

    /**
     * @desc  等级为emergency的日志信息，为最紧急日志
     * @param string $message 日志信息
     * @param array $context  日志额外信息
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @desc  等级为alert的日志信息
     * @param string $message 日志信息
     * @param array $context  日志额外信息
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @desc  等级为critical的日志信息
     * @param string $message 日志信息
     * @param array $context  日志额外信息
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @desc  等级为error的日志信息
     * @param string $message 日志信息
     * @param array $context  日志额外信息
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @desc  等级为warning的日志信息
     * @param string $message 日志信息
     * @param array $context  日志额外信息
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @desc  等级为notice的日志信息
     * @param string $message 日志信息
     * @param array $context  日志额外信息
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @desc  等级为info的日志信息
     * @param string $message 日志信息
     * @param array $context  日志额外信息
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @desc  等级为debug的日志信息
     * @param string $message 日志信息
     * @param array $context  日志额外信息
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @desc  日志写入方法
     * @param string $level   日志等级
     * @param string $message 日志信息
     * @param array  $context 日志额外信息
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $trace = debug_backtrace (DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $trace = !empty($trace) ? $trace[1] : [];
        $this->storage->process($level, $trace, $message, $context);
    }

    /**
     * @desc 日志处理器析构方法，处理掉还在缓冲中的数组中的日志
     */
    public function __destruct()
    {
        $this->storage && $this->storage->flushLogs();
    }
}