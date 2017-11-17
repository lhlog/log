<?php
/**
 * @desc   mysql存储日志类
 * @author hedonghong 2017/11/10 16:33
 */

namespace Lhlog\Storage;

use Lhlog\Models\MysqlLog;

class MysqlStorage extends Base
{
    use \Lhlog\Traits\Base;

    //数据库地址，默认本地
    public $host     = '127.0.0.1';

    //数据库用户名，默认root
    public $userName = 'root';

    //数据库密码，默认为空
    public $password = '';

    //数据库名称
    public $dbName;

    //日志表名
    public $logTableName;

    //数据库字符编码格式，默认utf-8
    public $charset = 'utf8';

    //数据库链接句柄
    protected static $conn;



    /**
     * @desc  日志处理器调度方法
     * @param string $level   日志等级
     * @param array  $trace   日志记录发生位置
     * @param string $message 日志消息
     * @param array  $context 日志额外信息
     * @return mixed
     */
    public function process($level, $trace, $message, $context)
    {
        $this->logLevel = $level;
        $logs = new MysqlLog(
            $message,
            !empty($trace) ? "file[{$trace['file']}]" . " line[{$trace['line']}]" : "",
            $level,
            !empty($context) ? json_encode($context) : "",
            date('Y-m-d H:i:s')
        );
        $this->write($logs);
    }

    /**
     * @desc  日志处理器初始化
     * @param array $config
     * @return mixed
     */
    public function init(Array $config)
    {
        parent::init($config);
        $dsn = "mysql:dbname={$this->dbName};host={$this->host};charset={$this->charset}";
        try {
            if (empty(self::$conn)) {
                self::$conn = new \PDO($dsn, $this->userName, $this->password);
            }
        } catch(\Exception $e) {
            $this->onException($e);
            return false;
        }
    }

    /**
     * @desc  日志写入方法
     * @param  LOG模型 $log
     * @return mixed
     */
    public function write($mysqlLog)
    {
        if ($this->useBuffer) {
            //缓存批量插入
            if (count($this->queue) >= $this->bufferSize) {
                $this->flushLogs();
            }
            $this->queue[] = $mysqlLog;
        } else {
            list($sql, $data) = $mysqlLog->getSingleSql($this->logTableName);
            $pdo = self::$conn->prepare($sql);
            $pdo->execute($data);
            return self::$conn->lastInsertId();
        }
    }

    /**
     * @desc   简单的日志读取方法
     * @return array
     */
    public function read($level='', $order='', $page=1, $size=100)
    {
        $sql = MysqlLog::getReadSql($this->logTableName, $level, $order, $page, $size);
        $pdo = self::$conn->prepare($sql);
        $pdo->execute();
        $results = $pdo->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * @desc   收尾方法
     * @return mixed
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * @desc   批次缓冲写入日志
     * @return mixed
     */
    public function flushLogs()
    {
        if (count($this->queue)) {
            list($sql, $data) = MysqlLog::getBatchSql($this->logTableName, $this->queue);
            $pdo = self::$conn->prepare($sql);
            $pdo->execute($data);
            $this->queue = [];
            return self::$conn->lastInsertId();
        }
    }
}