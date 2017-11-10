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
        $logger->info("hedonghong", ['sss'=>111]);
    }

    public function testConfigLogger()
    {
        $config = [
            'path'    => __DIR__.'/test.log',
        ];

        $logger = new Logger( new FileStorage($config) );
        // $r = new Logger( new RedisStorage($config) );
        $logger->info("hedonghong", ['sss'=>111]);
    }
}
