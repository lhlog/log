<?php
namespace Lhlog\Storage;

use Lhlog\IBase\IStorage;

abstract class Base implements IStorage
{
    use \Lhlog\Traits\Base;

    public $queue      = [];

    public $useBuffer  = false;

    public $bufferSize = 10;
    // 日志级别
    public $logLevel;

    // 在发生异常时的回调
    public $callOnException;

    public function __construct($config = array())
    {
        $this->init($config);
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function init(array $config)
    {
        $this->initProperty( $config );
    }
    public function compare($priority1, $priority2)
    {
        if ($priority1 === $priority2) return 0;
        return $priority1 < $priority2 ? -1 : 1;
    }

    /**
     * 生成异常时的回调
     * @author luoyuxiong
     * @datetime 2017-11-12T10:54:33+0800
     * @param    [type]                   $e      [异常对象]
     * @param    array                    $params [其它参数]
     * @return   [type]                           [description]
     */
    protected function onException( $e, array $params = [] ){
        $this->callOnException && call_user_func( $this->callOnException, $this, $e );
    }
}