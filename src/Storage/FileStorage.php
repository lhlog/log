<?php
/**
 * @desc
 * @author hedonghong 2017/11/10 9:06
 * @gts
 * @link
 */

namespace Lhlog\Storage;


use Lhlog\IBase\IStorage;

class FileStorage implements IStorage
{
    public $logFileName;

    public $logPath;

    public $logLevel;

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
    public function process($level, $message, $context=array())
    {
        // TODO: Implement process() method.
        $t    = \DateTime::createFromFormat("U.u", microtime(true))->format('Y-m-d H:i:s.u');
        $log  = "[{$t}] - [{$level}]";
        $log .= "  {$message}";
        !empty($context) ? $log .= " " . json_encode($context) : "";
        $log .= PHP_EOL.PHP_EOL;
        $this->write($log);
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function init(Array $config)
    {
        // TODO: Implement init() method.
        $this->logPath     = empty($config['path']) ? './lhlog.log' : $config['path'];
        $this->logFileName = basename($this->logPath);
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function write($log)
    {
        // TODO: Implement write() method.
        return file_put_contents($this->logPath, $log, FILE_APPEND);
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function read()
    {
        // TODO: Implement read() method.
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

}