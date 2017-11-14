<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 22:15
 */

namespace Lhlog\Models;

class MysqlLog extends Log
{
    public function  __construct($message, $location, $level, $content, $createTime)
    {
        parent::__construct($message, $location, $level, $content, $createTime);
    }

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