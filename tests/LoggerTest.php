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
use PHPUnit\Framework\TestCase;
use Lhlog\Logger;

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
            'path'    => './',
            'logName' => 'test.log',
            'cycle'   => 'hour', #hour 2017-11-21-14 #day 2017-11-21-xxx.log  #month 2017-11 #year 2017
        ];

        $logger = new Logger(new FileStorage($config));
        $logger->info("hedonghong", ['sss'=>111]);
    }
}
