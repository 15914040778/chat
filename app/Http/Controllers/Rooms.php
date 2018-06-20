<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Rooms extends Controller
{
    /**
     * get all room data
     * @version 1.0
     * @return array
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
    public function getUnreatMessageNumber( $user_id , $room_id ){
      if(empty($user_id) || empty($room_id)){
        return false;
      }
      DB::connection()->enableQueryLog();
      $readTime = DB::table('room_users')
                  ->join('chats' , 'chats.room_id' , '=' , 'room_users.room_id')
                  ->where('chats.send_time' , '>' , 'room_users.user_read_time')
                  ->where('room_users.user_id', '=' , $user_id)
                  ->where('room_users.room_id' , '=' , $room_id)
                  ->count();
      $sql = DB::getQueryLog();
      return $sql;
    }
    /**
     * delete designated room the user data
     */
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

}
