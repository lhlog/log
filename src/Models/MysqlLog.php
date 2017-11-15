<?php
/**
 * @desc   MYSQL记录日志模型
 * @author hedongong 2017-11-09 12:11
 */

namespace Lhlog\Models;

class MysqlLog extends Log
{
    public function  __construct($message, $location, $level, $content, $createTime)
    {
        parent::__construct($message, $location, $level, $content, $createTime);
    }

    /**
     * @desc   单独记录日志格式化
     * @param  string $logTableName 表名
     * @return array
     */
    public function getSingleSql($logTableName)
    {
        return
        [
            "
              INSERT INTO {$logTableName} 
              (`level`, location, message, content, create_time) 
              VALUES (:level,:location,:message,:content,:create_time);
            ",
            [
                ":level"       => $this->level,
                ":location"    => $this->location,
                ":message"     => $this->message,
                ":content"     => $this->content,
                ":create_time" => $this->create_time,
            ]
        ];
    }

    /**
     * @desc   批量插入日志格式化
     * @param  string $logTableName 表名
     * @param  array $mysqlLogs    mysql模型数据
     * @return array
     */
    public static function getBatchSql($logTableName, $mysqlLogs)
    {
        $sql   = "INSERT INTO {$logTableName} (`level`, location, message, content, create_time) VALUES ";
        $data  = [];
        foreach ($mysqlLogs as $key => $mysqlLog) {
            $sql .= "(:level{$key}, :location{$key}, :message{$key}, :content{$key}, :create_time{$key}),";
            $data[":level{$key}"]       = $mysqlLog->level;
            $data[":location{$key}"]    = $mysqlLog->location;
            $data[":message{$key}"]     = $mysqlLog->message;
            $data[":content{$key}"]     = $mysqlLog->content;
            $data[":create_time{$key}"] = $mysqlLog->create_time;
        }
        $sql = rtrim($sql, ",") . ";";
        return [$sql, $data];
    }

    /**
     * @desc  日志读方法
     * @param string $logTableName 日志存储的表名
     * @param string $level 要读取的日志级别
     * @param string $order 读取日志的排序
     * @param int    $page         页数
     * @param int    $size         每页总数
     * @return string
     */
    public static function getReadSql($logTableName, $level='', $order, $page, $size)
    {
        $offset = ($page - 1) * $size;
        $where  = " 1 = 1";
        if (!empty($level)) {
            $level  = addslashes($level);
            $where .= " AND `level`='{$level}'";
        }
        $where .= " {$order} limit {$offset}, {$size}";
        return "SELECT * FROM {$logTableName} WHERE" . $where;
    }
}