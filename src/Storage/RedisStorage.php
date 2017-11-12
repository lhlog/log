<?php
namespace Lhlog\Storage;

use Predis\Client as RedisClient;
use Lhlog\Models\RedisLog;

class RedisStorage extends Base
{
    use \Lhlog\Traits\Base;

    protected static $priorityLevel;

    // 主机
    public $host = '127.0.0.1';
    // 端口
    public $port = 6379;
    // 协议
    public $scheme = 'tcp';

    protected $listName = '';

    // redis 对象
    protected static $redis;

    public function init( array $config = [] ){
        parent::init( $config );
        if( !static::$redis ){
        	static::$redis = new RedisClient([
			    'scheme' => $this->scheme,
			    'host'   => $this->host,
			    'port'   => $this->port,
			]);
        }
    }

    /**
     * 处理日志
     * @author luoyuxiong
     * @datetime 2017-11-11T22:49:37+0800
     * @param    [type]                   $level   [级别]
     * @param    [type]                   $trace   [调用跟踪]
     * @param    [type]                   $message [日志信息]
     * @param    array                    $context [其它信息]
     * @return   [type]                            [description]
     */
    public function process($level, $trace, $message, $context=array()){
    	// $message, $location, $level, $content = ''
    	return $this->write( new RedisLog(
    		$message,
    		$trace ? "{$trace['file']} : {$trace[ 'line' ]}" : '',
    		$level,
    		$context ? json_encode( $context ) ?: $context : ''
    	) );
    }

    /**
     * 写入日志
     * @author luoyuxiong
     * @datetime 2017-11-11T23:40:13+0800
     * @param    RedisLog                 $log [日志对象]
     * @return   [type]                        [description]
     */
    public function write( $log ){
    	$me = $this;
    	static::$redis->transaction( function ( $tx ) use ( $me, $log ) {
    		$name = $me->generateHashName();
    		$params = $log->buildHashParams();
    		array_unshift($params, $name);
    		// hash
    		call_user_func_array( [ $tx, 'hset' ], $params );
    		// list
    		$tx->rpush( $me->generateListName(), $name );
    	} );
    	return true;
    }

    /**
     * 随机生成一个 hash 变量名
     * @author luoyuxiong
     * @datetime 2017-11-12T10:07:52+0800
     * @return   [type]                   [description]
     */
    public function generateHashName(){
    	return 'lhog_'.microtime( true ).mt_rand( 0, 9999999 );
    }

    /**
     * 生成列表的变量名
     * @author luoyuxiong
     * @datetime 2017-11-12T10:16:25+0800
     * @return   [type]                   [description]
     */
    public function generateListName(){
    	return 'lhlog_list';
    }

    public function read($level, $order = '', $page = 1, $size = 100 ){

    }

    public function close(  ){

    }

    public function flushLogs(){}


} 