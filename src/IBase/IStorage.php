<?php
/**
 * @desc
 * @author hedonghong 2017/11/10 8:56
 * @gts
 * @link
 */

namespace Lhlog\IBase;

interface IStorage
{
    //调度方法
    public function process($level, $message, $context);

    //初始化方法
    public function init(Array $config);

    //写入方法
    public function write($log);

    //读取方法
    public function read();

    //收尾工作
    public function close();
}