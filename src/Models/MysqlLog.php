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

    public function getFormatSql($logTableName)
    {
        return
        "
          INSERT INTO {$logTableName} 
          (`level`, location, message, content, create_time) 
          VALUES (:level,:location,:message,:content,:create_time);
        ";
    }

    public function getFormatData()
    {
        return [
            ":level"       => $this->level,
            ":location"    => $this->location,
            ":content"     => $this->content,
            ":create_time" => $this->create_time,
        ];
    }
}