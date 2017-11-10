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
    public function testLogger()
    {
        $logger = new Logger();
        $logger->debug("hedonghong", ['sss'=>111]);
    }

//    public function testConfigLogger()
//    {
//        $config = [
//            'storage' => 'file',
//            'path'    => './test.log',
//        ];
//
//        $logger = new Logger();
//        $logger->setDefaultStorage(new FileStorage($config));
//        $logger->info("hedonghong", ['sss'=>111]);
//    }
}
