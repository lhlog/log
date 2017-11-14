<?php
/**
 * @desc
 * @author hedonghong 2017/11/10 9:06
 * @gts
 * @link
 */

namespace Lhlog\Storage;

use Lhlog\Models\LineLog;
use Psr\Log\InvalidArgumentException;
use SebastianBergmann\CodeCoverage\Report\PHP;

class FileStorage extends Base
{
    /**
     * 日志文件的周期
     */
    // 月 2017-11-21-14
    const CYCLE_HOUR  = 'hour';
    // 天 017-11-21-xxx.log
    const CYCLE_DAY   = 'day';
    // 月 2017-11
    const CYCLE_MONTH = 'month';
    // 年 2017
    const CYCLE_YEAR  = 'year';

    // 日志名
    public $logFileName = 'lhlog.log';
    // 保存的目录
    public $logPath     = '.' ;
    // 周期
    public $cycle       = self::CYCLE_DAY;

    // 周期类型
    const CYCLE_TYPE_MAP = [
        self::CYCLE_HOUR  => 'Y-m-d-H',
        self::CYCLE_DAY   => 'Y-m-d',
        self::CYCLE_MONTH => 'Y-m',
        self::CYCLE_YEAR  => 'Y',
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
        parent::init($config);
        if (!array_key_exists( $this->cycle, self::CYCLE_TYPE_MAP )) throw new InvalidArgumentException('Invalid `cycle` value '.$this->cycle);
        // 全路径
        $this->logPath = $this->logPath . DIRECTORY_SEPARATOR . date(self::CYCLE_TYPE_MAP[ $this->cycle ]) . '_' .$this->logFileName;
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
        $t   = \DateTime::createFromFormat("U.u", microtime(true))->format('Y-m-d H:i:s.u');

        $log = new LineLog(
            $message,
            empty($trace) ? "" : "file[{$trace['file']}]  line[{$trace['line']}]",
            $level,
            empty($this->content) ? "" : json_encode($this->content),
            $t
        );
        $this->write($log->format());
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
        if ($this->useBuffer) {
            //缓存批量插入
            if (count($this->queue) >= $this->bufferSize) {
                $this->flushLogs();
            }
            $this->queue[] = $log;
        } else {
            //实时插入
            return file_put_contents($this->logPath, $log, FILE_APPEND);
        }
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function read($level=null, $order='ASC', $page=1, $size=100)
    {
        $logs  = [];
        if (file_exists($this->logPath)) {
            $fp = new \SplFileObject($this->logPath, "rb");
            $fp->seek(PHP_INT_MAX);
            $totalLine          = $fp->key()+1;
            $totalPage          = ceil($totalLine/$size);
//            $logs['total_line'] = $totalLine;
//            $logs['order']      = $order;
//            $logs['page']       = $page;
//            $logs['size']       = $size;
            if (strtoupper($order) === 'ASC') {
                if ($totalPage >= $page) {
                    $startLine = ($page - 1) * $size;
                    $endLine   = $page * $size;
                    $endLine   = $endLine > $totalLine ? $totalLine : $endLine;
                    $count     = $endLine - $startLine;
                    $fp->seek($startLine);
                    for ($i = 0; $i < $count; ++$i) {
                        $logs[] = $fp->current();
                        $fp->next();
                    }
                }
            } elseif (strtoupper($order) === 'DESC') {
                if ($totalPage >= $page) {
                    $startLine = $totalLine - ($page * $size) + 1;
                    $startLine = $startLine < 0 ? 0 : $startLine;
                    $count     = $size;
                    $fp->seek($startLine);
                    for ($i = 0; $i < $count; ++$i) {
                        $logs[] = $fp->current();
                        $fp->next();
                    }
                    $logs = array_reverse($logs);
                }
            }
        }
        return $logs;
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

    public function flushLogs()
    {
        if (count($this->queue)) {
            $logs = '';
            foreach ($this->queue as $log) {
                $logs .= $log;
            }
            $this->queue = [];
            return file_put_contents($this->logPath, $logs, FILE_APPEND);
        }
    }
}