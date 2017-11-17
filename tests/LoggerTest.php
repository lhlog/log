<?php
/**
 * @desc   测试类
 * @author hedonghong 2017/11/10 8:45
 */

namespace Tests;

use Lhlog\Storage\FileStorage;
use Lhlog\Storage\MysqlStorage;
use PHPUnit\Framework\TestCase;
use Lhlog\Logger;


class LoggerTest extends TestCase
{

    public function testFileLogger()
    {
        $config = [
            'logPath'     => '.',
            'logFileName' => 'test.log',
            'useBuffer'   => true,
            'bufferSize'  => 2,
            'cycle'       => FileStorage::CYCLE_DAY, #hour 2017-11-21-14 #day 2017-11-21-xxx.log  #month 2017-11 #year 2017
        ];
        $logger = new Logger(new FileStorage($config));
        $logger->notice("this is a test".range(1, 10), ['sss'=>111]);
        $logger->info("this is a test".range(1, 10), ['sss'=>111]);
        $logger->notice("this is a test".range(1, 10), ['sss'=>111]);
        $logger->info("this is a test".range(1, 10), ['sss'=>222]);
        $logger->info("this is a test".range(1, 10), ['sss'=>333]);
        $logger->alert("this is a test".range(1, 10), ['sss'=>333]);
        $logger->debug("this is a test".range(1, 10), ['sss'=>333]);
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
        $mysql->warning("this is a test".range(1, 10), ['ss'=>111]);
    }

     public function testFileRead()
     {
         $config = [
             'logPath'     => '.',
             'logFileName' => 'test.log',
             'useBuffer'   => true,
             'bufferSize'  => 2,
             'cycle'       => FileStorage::CYCLE_DAY, #hour 2017-11-21-14 #day 2017-11-21-xxx.log  #month 2017-11 #year 2017
         ];
        $logger = new Logger(new FileStorage($config));
        $logger->listLogs('', 'desc', 1, 3);
     }

    public function testMysqlRead()
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
        $mysql->listLogs('info', 'order by create_time desc', 1, 2);
    }
}
