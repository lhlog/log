<?php
/**
 * @desc
 * @author hedonghong 2017/11/10 9:06
 * @gts
 * @link
 */

namespace Lhlog\Storage;

class FileStorage extends Base
{
    /**
     * 日志文件的周期
     */
    // 月 2017-11-21-14
    const CYCLE_HOUR = 'hour';
    // 天 017-11-21-xxx.log
    const CYCLE_DAY = 'day';
    // 月 2017-11
    const CYCLE_MONTH = 'month';
    // 年 2017
    const CYCLE_YEAR = 'year';

    // 日志名
    public $logFileName = 'lhlog.log';
    // 保存的目录
    public $logPath = '.' ;
    // 级别
    public $logLevel;
    // 周期
    public $cycle = self::CYCLE_DAY;

    // 周期类型
    const CYCLE_TYPE_MAP = [
        self::CYCLE_HOUR => 'Y-m-d-H',
        self::CYCLE_DAY => 'Y-m-d',
        self::CYCLE_MONTH => 'Y-m',
        self::CYCLE_YEAR => 'Y',
    ];

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function init(array $config)
    {
        parent::init( $config );
        if( !array_key_exists( $this->cycle, self::CYCLE_TYPE_MAP ) ) throw new \Exception( 'Invalid `cycle` value '.$this->cycle );
        // 全路径
        $this->logPath = $this->logPath.'/'.date( self::CYCLE_TYPE_MAP[ $this->cycle ] ).'_'.$this->logFileName;
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function process($level, $trace, $message, $context=array())
    {
        $this->logLevel = $level;
        // TODO: Implement process() method.
        $t    = \DateTime::createFromFormat("U.u", microtime(true))->format('Y-m-d H:i:s.u');
        $log = new \Lhlog\Models\Log(
            $t. '  ' .$message,
            empty($trace) ? "" : " file[{$trace['file']}]  line[{$trace['line']}]",
            $level,
            $context
        );

        $this->write($log);
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
        return file_put_contents($this->logPath, json_encode( $log ), FILE_APPEND);
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