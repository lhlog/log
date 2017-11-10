<?php
/**
 * @desc
 * @author hedonghong 2017/11/10 16:33
 * @gts
 * @link
 */

namespace Lhlog\Storage;

use Lhlog\IBase\IStorage;

class MysqlStorage implements IStorage
{
    use \Lhlog\Traits\Base;

    public $host = '127.0.0.1';

    public $userName = 'root';

    public $password = '';

    public $dbName;

    public $logTableName;

    public $charset = 'utf8';

    public static $conn;

    public $logLevel;

    public function __construct($config = array())
    {
        $this->init($config);
    }

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
        // TODO: Implement process() method.
        $this->logLevel = $level;
        $logs = [
            $level,
            !empty($trace) ? "file[{$trace['file']}]" . " line[{$trace['line']}]" : "",
            $message,
            !empty($context) ? json_encode($context) : "",
        ];
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
        // TODO: Implement init() method.
        $this->initProperty($config);
        $dsn = "mysql:dbname={$this->dbName};host={$this->host};charset={$this->charset}";
        try {
            if (empty(self::$conn)) {
                self::$conn = new \PDO($dsn, $this->userName, $this->password);
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
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
        // TODO: Implement write() method.
        $pdo = self::$conn->prepare("
          INSERT INTO {$this->logTableName} (level, location, message, content, create_time) 
          VALUES (?,?,?,?, now());
        ");
        //@todo 这个时间可以 用户定义的吗
        $pdo->execute($logs);
        return self::$conn->lastInsertId();
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