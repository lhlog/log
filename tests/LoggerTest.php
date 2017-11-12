<?php
/**
 * @desc
 * @author hedonghong 2017/11/10 8:45
 * @gts
 * @link
 */

namespace Tests;

require_once "../vendor/autoload.php";

use Lhlog\Storage\FileStorage;
use Lhlog\Storage\MysqlStorage;
use Lhlog\Storage\RedisStorage;
use PHPUnit\Framework\TestCase;
use Lhlog\Logger;

class PQtest extends \SplPriorityQueue
{
    public function compare($priority1, $priority2)
    {
        if ($priority1 === $priority2) return 0;
        return $priority1 < $priority2 ? -1 : 1;
    }
}

class LoggerTest extends TestCase
{
//    public function testLogger()
//    {
//        $logger = new Logger();
//        $logger->debug("hedonghong", ['sss'=>111]);
//    }


    public function testConfigLogger()
    {
        $config = [
            'logPath'     => '.',
            'logFileName' => 'test.log',
            'useBuffer'   => false,
            'cycle'       => FileStorage::CYCLE_DAY, #hour 2017-11-21-14 #day 2017-11-21-xxx.log  #month 2017-11 #year 2017
        ];
        $logger = new Logger(new FileStorage($config));
        $logger->info("hedonghong", ['sss'=>111]);
    }

    public function testMysqlLogger()
    {
        $config = [
            'host'          => '127.0.0.1',
            'userName'      => 'root',
            'password'      => '',
            'dbName'        => 'test',
            'logTableName'  => 'example_table_log',
            'charset'       => 'utf8',
        ];



        $mysql = new Logger(new MysqlStorage($config));

        $mysql->warning("hedonghong3", ['ss'=>111]);
    }

    public function testRead()
    {
//        $config = [
//            'logPath'    => '.',
//            'logFileName' => 'test.log',
//            'cycle'   => FileStorage::CYCLE_DAY,
//        ];
//        $logger = new Logger(new FileStorage($config));
//        $logger->listLogs(1, 1, 3, 3);

        $config = [
            'host'          => '127.0.0.1',
            'userName'      => 'root',
            'password'      => '',
            'dbName'        => 'test',
            'logTableName'  => 'example_table_log',
            'charset'       => 'utf8',
        ];



        $mysql = new Logger(new MysqlStorage($config));
        $mysql->listLogs('info', 'order by create_time desc', 1, 2);
    }

    public function testRedis(){
        $log = new Logger( new RedisStorage( [
            'callOnException' => function ( $e ){
                print_r( $e );
                exit;
            }
        ] ) );
        $log->info( "info-log", [ 'd' => 2 ] );
    }
}

( new LoggerTest )->testRedis();
