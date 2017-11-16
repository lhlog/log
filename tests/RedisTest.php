<?php
namespace Tests;

use Lhlog\Logger;
use Lhlog\Storage\RedisStorage;
use PHPUnit\Framework\TestCase;


class LoggerTest extends TestCase
{
    /**
     * 添加日志
     * @author luoyuxiong
     * @datetime 2017-11-16T11:54:38+0800
     * @return   [type]                   [description]
     */
    public function testCreate(){
        $log = new Logger( new RedisStorage( [
            'callOnException' => function ( $e ){
                print_r( $e );
                exit;
            }
        ] ) );
        $log->info( "info-log", [ 'd' => 2 ] );
    }
}