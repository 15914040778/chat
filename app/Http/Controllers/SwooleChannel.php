<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwooleChannel extends Controller
{
  private static $_SwooleChannel;
  protected function __construct(){

  }
  /**
   * single example-ify
   * @version 1.0
   */
  public static function connect(){
    if(empty(self::$_SwooleChannel)){
      self::$_SwooleChannel = new Swoole\Channel(2 * 1024 * 1024); //2M
    }
    $SwooleChannel = self::$_SwooleChannel;
    return $SwooleChannel;
  }

}
