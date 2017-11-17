## 简介
    这是一个纯php的日志记录类，本类库实现了PSR-3接口，符合PSR-3日志级别。通过composer，简单配置即可使用！暂时实现了日志用文件(默认)、MYSQL、Redis存储。代码简单易懂，若要扩展可以通过查看代码结构进行自行扩展。
    
## 目录结构
```
___
|-src源码文件夹
| |-IBase
| |  |-IStorage.php存储介质接口
| |-Models日志模型文件夹
| |  |-LineLog.php实现基类的文件类，格式化函数
| |  |-Log.php模型基类
| |  |-MysqlLog.php实现基类的Mysql类
| |  |-RedisLog.php实现基类的Redis类
| |-Storage日常存储实现
| |  |-Base.php实现IStorage.php的存储基类
| |  |-FileStorage.php继承Base.php文件存储实现类
| |  |-MysqlStorage.php继承Base.php数据库存储实现类
| |  |-RedisStorage.php继承Base.phpredis存储实现类
| |-Traits日志存储实现公共trait
| |  |-Base.php实现了简单参数配置
|-tests测试文件夹
|-composer.json
|-phpunit.xml
-README.MD
```

## 怎么使用？
    1.File存储日志
```
        $config = [
            'logPath'     => '.',#日志路径
            'logFileName' => 'test.log',#日志文件名称
            'useBuffer'   => true,#是否开启批量插入
            'bufferSize'  => 2,#批量插入日志阀值，为日志条数
            'cycle'       => 'day', #日志的切割方式，按天记录
            #hour 2017-11-21-14-xxx.log
            #day 2017-11-21-xxx.log  
            #month 2017-11-xxx.log 
            #year 2017-xxx.log
        ];
        $logger = new Logger(new FileStorage($config));
        $logger->notice("this is a test".range(1, 10), ['sss'=>111]);
```
    2.Mysql存储日志
```
请先建立日志表
        CREATE TABLE `example_table_log` (
        	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
        	`level` CHAR(10) NOT NULL DEFAULT '' COMMENT '日志等级',
        	`location` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '产生日志位置',
        	`message` TEXT NOT NULL COMMENT '日志信息',
        	`content` TEXT NOT NULL COMMENT '日志其他信息',
        	`create_time` DATETIME NOT NULL DEFAULT '1971-01-01 00:00:00' COMMENT '日志创建时间',
        	PRIMARY KEY (`id`),
        	INDEX `idx_level` (`level`),
        	INDEX `idx_create_time` (`create_time`)
        )
        COMMENT='日志表-例子表'
        COLLATE='utf8_general_ci'
        ENGINE=MyISAM;

        $config = [
            'host'          => '127.0.0.1',
            'userName'      => 'root',
            'password'      => '',
            'dbName'        => 'test',
            'logTableName'  => 'example_table_log',
            'charset'       => 'utf8',
            'callOnException' => function ($e) {
                print_r($e);
                exit;
            }
            #错误回调，接收MYSQL异常错误
        ];

        $mysql = new Logger(new MysqlStorage($config));
        $mysql->warning("this is a test".range(1, 10), ['ss'=>111]);
```
    3.Redis存储日志
```
        $config = [
            'host'   => '127.0.0.1',
            'port'   => 6379,
            'scheme' => 'tcp',
        ];
        $log = new Logger(new RedisStorage($config));
        $log->info("info-log", [ 'd' => 2 ]);
```

## 作者信息
    hedonghong<782101031@qq.com>
    luoyuxiong<971366114@qq.com>