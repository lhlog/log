<?php
/**
 * @desc
 * @author hedonghong 2017/11/10 16:33
 * @gts
 * @link
 */

namespace Lhlog\Storage;

use Lhlog\Models\MysqlLog;

class MysqlStorage extends Base
{
    use \Lhlog\Traits\Base;

    public $host     = '127.0.0.1';

    public $userName = 'root';

    public $password = '';

    public $dbName;

    public $logTableName;

    public $charset = 'utf8';

    protected static $conn;



    /**
     * @desc
     * @param $level
     * @param $trace
     * @param $message
     * @param $context
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
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
     * @desc
     * @param array $config
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function init(Array $config)
    {
        parent::init($config);
        $dsn = "mysql:dbname={$this->dbName};host={$this->host};charset={$this->charset}";
        try{
            if (empty(self::$conn)) {
                self::$conn = new \PDO($dsn, $this->userName, $this->password);
            }
        }catch( \Exception $e ){
            $this->onException( $e );
            return false;
        }
        
    }

    /**
     * @desc
     * @param $log
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
     */
    public function write($logs)
    {
        $pdo = self::$conn->prepare($logs->getWriteSql($this->logTableName));
        $pdo->execute($logs->getWriteData());
        return self::$conn->lastInsertId();
    }

    /**
     * @desc
     * @return mixed
     * @author hedonghong
     * @gts
     * @link
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