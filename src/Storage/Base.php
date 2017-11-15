<?php
/**
 * @desc   日志处理器基础类
 * @author hedonghong 2017-11-09
 */
namespace Lhlog\Storage;

use Lhlog\IBase\IStorage;

abstract class Base implements IStorage
{
    use \Lhlog\Traits\Base;

    //缓冲数组
    public $queue      = [];

    //是否开启缓冲，暂只对file,mysql存储日志方式有效
    public $useBuffer  = false;

    //缓冲日志记录条数大小
    public $bufferSize = 100;

    // 日志级别
    public $logLevel;

    // 在发生异常时的回调
    public $callOnException;

    public function __construct($config = array())
    {
        $this->init($config);
    }

    /**
     * @desc   日志处理器处理初始化方法
     * @return void
     */
    public function init(array $config)
    {
        $this->initProperty( $config );
    }

    /**
     * @desc     日志记录异常生成异常时的回调
     * @author   luoyuxiong
     * @datetime 2017-11-12T10:54:33+0800
     * @param    object $e  异常对象
     * @param    array   $params 其它参数
     * @return   void
     */
    protected function onException( $e, array $params = [] ){
        $this->callOnException && call_user_func( $this->callOnException, $this, $e );
    }
}