<?php
/**
 * @desc 公共日志trait
 */
namespace Lhlog\Traits;

trait Base {
    /**
     * @desc     根据传入的参数来初始化属性
     * @author   luoyuxiong
     * @datetime 2017-11-10T15:10:29+0800
     * @param    array   $config [description]
     * @return   object
     */
    protected function initProperty( $config = [] ){
        $r = new \ReflectionClass( $this );
        $ex = $this->getExcludeInitProperty();
        foreach( $config as $p => $v ){
            if( in_array( $p, $ex ) ) continue;
            if( !$r->getProperty( $p )->isPublic() ) throw new \Exception( 'Property `'. $p .'` not exists' );
            $this->$p = $v;
        }
        return $this;
    }

    /**
     * @desc     返回不需要使用 $this->initProperty() 初始化的属性
     * @author   luoyuxiong
     * @datetime 2017-11-10T15:24:55+0800
     * @return   array
     */
    protected function getExcludeInitProperty(){
        return [ ];
    }
}