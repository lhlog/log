<?php
/**
 * @desc   redis日志存储类
 * @author luyuxiong 2017-11-13
 */
namespace Lhlog\Storage;

use Predis\Client as RedisClient;
use Lhlog\Models\RedisLog;

class RedisStorage extends Base
{
    use \Lhlog\Traits\Base;

    // 主机
    public $host = '127.0.0.1';
    // 端口
    public $port = 6379;
    // 协议
    public $scheme = 'tcp';

    protected $listName = '';

    // redis 对象
    protected static $redis;

    /**
     * @desc  日志处理器初始化
     * @param array $config
     * @return mixed
     */
    public function init( array $config = [] )
    {
        parent::init( $config );
        if ( !static::$redis ) {
            static::$redis = new RedisClient([
                'scheme' => $this->scheme,
                'host'   => $this->host,
                'port'   => $this->port,
            ]);
        }
    }

    /**
     * @desc    处理日志
     * @author   luoyuxiong
     * @datetime 2017-11-11T22:49:37+0800
     * @param    string   $level   级别
     * @param    array    $trace   调用跟踪
     * @param    string   $message 日志信息
     * @param    array    $context 其它信息
     * @return   boolean
     */
    public function process($level, $trace, $message, $context=array())
    {
        return $this->write(new RedisLog(
            $message,
            $trace ? "{$trace['file']} : {$trace[ 'line' ]}" : '',
            $level,
            $context ? json_encode( $context ) ? : $context : ''
        ));
    }

    /**
     * @desc  日志写入方法
     * @param  LOG模型 $log
     * @return mixed
     */
    public function write($log)
    {
        $me = $this;
        static::$redis->transaction(function ($tx) use ($me, $log) {
            $name = $me->generateHashName();
            $params = $log->buildHashParams();
            array_unshift($params, $name);
            // hash
            call_user_func_array([ $tx, 'hmset'], $params);
            // list
            $tx->rpush($me->generateListName(), $name);
        } );
        return true;
    }

    /**
     * @desc     随机生成一个 hash 变量名
     * @author   luoyuxiong
     * @datetime 2017-11-12T10:07:52+0800
     * @return   string
     */
    public function generateHashName()
    {
        return 'lhog_'.microtime(true).mt_rand(0, 9999999);
    }

    /**
     * @desc     生成列表的变量名
     * @author   luoyuxiong
     * @datetime 2017-11-12T10:16:25+0800
     * @return   string
     */
    public function generateListName()
    {
        return 'lhlog_list';
    }

    /**
     * @desc   简单的日志读取方法
     * @return array
     */
    public function read($level, $order = '', $page = 1, $size = 100 )
    {

    }


    /**
     * @desc   收尾方法
     * @return mixed
     */
    public function close()
    {

    }

    /**
     * @desc   批次缓冲写入日志
     * @return mixed
     */
    public function flushLogs()
    {

    }
} 