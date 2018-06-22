<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Rooms extends Controller
{
    private static $_Rooms;
    protected function __construct(){

    }
    /**
     * single example-ify
     * @version 1.0
     */
    public static function connect(){
      if(empty(self::$_Rooms)){
        self::$_Rooms = new Rooms();
      }
      return self::$_Rooms;
    }
    /**
     * get all room data
     * @version 1.0
     * @return object
     */
    public function getAllRoomData(){
      $roomsData = DB::table('user_rooms')
                    ->join('room_users' , 'user_rooms.id' , '=' , 'room_users.room_id')
                    ->select('room_users.*' , 'user_rooms.name' , 'user_rooms.description')
                    ->get();
      return $roomsData;
    }
    /**
     * insert user data to room
     * @version 1.0
     * @var int $room_id      Room ID
     * @var int $user_id      User ID
     * @var int $user_grade   User Grade  default 1 [for setting permissions]
     * @return bool|int
     */
    public function insertRoomUserData( $room_id , $user_id , $user_grade = 1 ){
      if(empty($room_id) || empty($user_id)){
        return false;
      }
      //get current date time
      $dateTime = date('Y-m-d H:i:s');
      $insertResult = DB::table('user_rooms')
                      ->insert([
                        'room_id'         =>  $room_id,
                        'user_id'         =>  $user_id,
                        'user_grade'      =>  $user_grade,
                        'user_read_time'  =>  $dateTime,
                        'created_at'      =>  $dateTime,
                        'updated_at'      =>  $dateTime
                      ]);
      return $insertResult;
    }
    /**
     * get the number of unread message in the designated user and the designated room
     * @version 1.0
     * @var int $user_id  User ID
     * @var int $room_id  Room ID
     * @return int
     */
    public function getUnreadMessageNumber( $user_id , $room_id ){
      if(empty($user_id) || empty($room_id)){
        return false;
      }
      //get the time of new read in the designated user and the designated room
      $readTime = DB::table('room_users')
                  ->where('user_id', '=' , $user_id)
                  ->where('room_id' , '=' , $room_id)
                  ->value('user_read_time');
      // DB::connection()->enableQueryLog();
      //get unread message the number
      $unreadMessageNumber = DB::table('chats')
                              ->where('room_id' , $room_id)
                              ->where('send_time' , '>' , $readTime)
                              ->count();
      // $sql = DB::getQueryLog();
      return $unreadMessageNumber;
    }
    /**
     * delete designated room and designated user the data
     * @version 1.0
     * @var int $room_id Room ID
     * @var int $user_id User ID
     * @return bool|int
     */
    public function deleteRoomUser( $room_id , $user_id ){
      if(empty($room_id) || empty($user_id)){
        return false;
      }
      $deleteResult = DB::table('room_users')
                      ->where('room_id' , $roomt_id)
                      ->where('user_id' , $user_id)
                      ->delete();
      return $deleteResult;
    }
    /**
     * update user read current room message time
     * @version 1.0
     * @var int $user_id User ID
     * @var int $room_id Room ID
     * @return bool|int
     */
    public function updateReadTime( $user_id , $room_id ){
      if(empty($user_id) || empty($room_id)){
        return false;
      }
      //get current date time
      $dateTime = date('Y-m-d H:i:s');
      //update read time and update time
      $updateResult = DB::table('room_users')
                      ->where('user_id' , '=' , $user_id)
                      ->where('room_id' , '=' , $room_id)
                      ->update([
                        'user_read_time' => $dateTime,
                        'updated_at'  =>  $dateTime
                      ]);
      //return update result
      return $updateResult;
    }
    /**
     * get designated room the user member
     * @version 1.0
     * @var int $room_id Room ID
     * @return mixed
     */
    public function getRoomUserMember( $room_id ){
      if(empty($room_id)){
        return [];
      }
      $userMember = DB::table('room_users')
                    ->where('room_id' , $room_id)
                    ->pluck('user_id');
      return $userMember;
    }

}
