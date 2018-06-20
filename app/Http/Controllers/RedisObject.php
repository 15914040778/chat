<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedisObject extends Controller
{
  /**
   * 私有化构造函数
   * 原因：防止外界调用构造新的对象
   */
  private function __construct(){}
  private static $redisInstance;
  /**
   * 获取redis连接的唯一出口
   */
  static public function getRedisConn(){
      if(!self::$redisInstance instanceof self){
          self::$redisInstance = new self;
      }
      // 获取当前单例
      $temp = self::$redisInstance;
      // 调用私有化方法
      return $temp->connRedis();
  }
  /**
   * 连接ocean 上的redis的私有化方法
   * @return Redis
   */
  static private function connRedis()
  {
      try {
          $redis_ocean = new \Redis();
          $redis_ocean->connect('127.0.0.1',6379,1);//短链接，本地host，端口为6379，超过1秒放弃链接
          $redis_ocean->select(0);//选择redis库,0~15 共16个库
          // $redis_ocean->auth(G::$conf['redis-pass']);

      }catch (Exception $e){
          echo $e->getMessage().'<br/>';
      }

      return $redis_ocean;
  }
}
