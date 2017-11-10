<?php
/**
 * @desc
 * @author hedonghong 2017/11/10 8:35
 * @gts
 * @link
 */

namespace Lhlog;

use Lhlog\IBase\IStorage;
use Lhlog\Storage\FileStorage;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{
    protected $storage;

    public function __construct( IStorage $storage = null )
    {
        $this->storage = null === $storage ? new FileStorage : $storage;
    }

    /**
     * @desc
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function emergency($message, array $context = array())
    {
        // TODO: Implement emergency() method.
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @desc
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function alert($message, array $context = array())
    {
        // TODO: Implement alert() method.
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @desc
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function critical($message, array $context = array())
    {
        // TODO: Implement critical() method.
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @desc
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function error($message, array $context = array())
    {
        // TODO: Implement error() method.
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @desc
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function warning($message, array $context = array())
    {
        // TODO: Implement warning() method.
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @desc
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function notice($message, array $context = array())
    {
        // TODO: Implement notice() method.
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @desc
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function info($message, array $context = array())
    {
        // TODO: Implement info() method.
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @desc
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function debug($message, array $context = array())
    {
        // TODO: Implement debug() method.
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @desc
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function log($level, $message, array $context = array())
    {
        // TODO: Implement log() method.
        $this->storage->process($level, $message, $context);
        $this->aa();
    }
    
    public function aa(){
        file_put_contents( __DIR__.'/../tests/trace.log', print_r( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ), true ) );

    }

}