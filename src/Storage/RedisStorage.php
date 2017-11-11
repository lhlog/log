<?php
namespace Lhlog\Storage;
// use 
class RedisStorage extends Base{
    // redis 对象
    public $redis;

    public function init( array $config = [] ){
        parent::init( $config );
        
    }
} 