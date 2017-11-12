<?php
namespace Lhlog\Models;

class RedisLog extends Log
{

	/**
	 * 生成 hash 的参数
	 * @author luoyuxiong
	 * @datetime 2017-11-12T10:12:13+0800
	 * @return   [type]                   [description]
	 */
	public function buildHashParams(){
		return [
			// 日志内容
			'message' , $this->message,
			// 位置
			'location' , $this->location,
			// 级别
			'level' , $this->level,
			// 添加时间 
			'create_time' , $this->create_time,
			// 额外信息
			'content' , $this->content,
		];
	}
}