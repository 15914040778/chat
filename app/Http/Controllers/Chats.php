<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Chats extends Controller
{
  private static $_Chats;
  protected function __construct(){

  }
  /**
   * single example-ify
   * @version 1.0
   */
  public static function connect(){
    if(empty(self::$_Chats)){
      self::$_Chats = new Chats();
    }
    return self::$_Chats;
  }

  /**
   * get designated room the chat record
   * @version 1.0
   * @var int $room_id Room ID
   * @return mixed Chat record data
   */
  public function getChatRecord( $room_id ){
    if(empty($room_id)){
      return [];
    }
    $chatRecord = DB::table('chats')
                  ->join('users' , 'users.id' , '=' , 'chats.user_id')
                  ->where('room_id' , $room_id)
                  ->select('chats.user_id as uid' , 'chats.content' , 'chats.send_time' , 'users.name as uname')
                  ->get();
    return $chatRecord;
  }

}
