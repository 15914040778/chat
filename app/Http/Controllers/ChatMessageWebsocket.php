<?php
/**
 * handle chat message the server
 * @version 1.0
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Rooms;
use App\Http\Controllers\Chats;
use App\Http\Controllers\RedisObject;
class ChatMessageWebsocket extends Controller
{
  public $server;
  public $i = 0;
  public function __construct() {
      $this->server = new \swoole_websocket_server("0.0.0.0/chatMessage", 9502);
      $this->server->on('open', function (\swoole_websocket_server $server, $request) {
          // echo "server: handshake success with fd{$request->fd}\n";
          $this->i = 0;

      });
      $this->server->on('message', function (\swoole_websocket_server $server, $frame) {
        $message = json_decode($frame->data);
        $Rooms = Rooms::connect();
        //Get room user member

        $roomUserMember = $Rooms->getRoomUserMember($message->room_id);
        //Record user and fd the relation(User ID is key)
        $this->insertRedisData( 'user_fd' , $message->uid , $frame->fd );
        //Record fd and Room ID the relation
        $this->insertRedisData( 'fd_room' , $frame->fd , $message->room_id );
        //Record fd and user the relation(User ID is key)
        $this->insertRedisData( 'fd_user', $frame->fd, $message->uid);

        switch ($message->action) {
          case 'open':

            //Load history recoed
            $this->loadHistoryRecoed($message->uid , $message->room_id);
            break;
          case 'send':
            //push message to current room the all user member
            $this->sendMessage($message , $roomUserMember);
            break;
          
          default:
            // code...
            break;
        }




      });
      $this->server->on('close', function ($ser, $fd) {
          $Rooms = Rooms::connect();
          //get all fd relation room id the data
          $allFdRoomData = $this->getRedisData('fd_room');
          //get all fd relation user the data
          $allFdUserData = $this->getRedisData('fd_user');
          if(!empty($allUserRoom[$fd]) && !empty($allFdUserData[$fd])){
            //Update user designated room the message read time
            $Rooms->updateReadTime($allFdUserData[$fd] , $allFdRoomData[$fd]);

            //Delete current user the relevant info
            //...

          }
          echo "client {$fd} closed\n";
      });
      $this->server->on('request', function ($request, $response) {
          // 接收http请求从get获取message参数的值，给用户推送
          // $this->server->connections 遍历所有websocket连接用户的fd，给所有用户推送
          foreach ($this->server->connections as $fd) {
              $this->server->push($fd, $request->get['message']);
          }
      });
      $this->server->start();
  }
  /**
   * Load history recoed
   * @version 1.0
   * @var array $uid          User ID
   * @var int   $room_id      Room ID
   * @return bool
   */
  public function loadHistoryRecoed( $uid , $room_id ){
    if(empty($room_id)){
      return false;
    }

    //Load history recoed
    if($this->i == 0){
      $users = $this->getRedisData( 'user_fd' );
      $Chats = Chats::connect();
      $chatRecord = $Chats->getChatRecord($room_id);
      foreach( $chatRecord as $key=>$value ){
        $historyMessage = json_encode($value);

        if(!empty($users[$uid])){

          $this->server->push($users[$uid], $historyMessage);

        }

      }
    }
    $this->i++;
  }
  /**
   * Send message
   * @version 1.0
   * @var object $sendMessageContent Send Message Content
   * @var mixed  $roomUserMember     Room user member
   * @return bool
   */
  public function sendMessage( $sendMessageContent , $roomUserMember ){
    $Chats = Chats::connect();
    $users = $this->getRedisData( 'user_fd' );
    //Send message content insert to MySql database
    $Chats->insert($sendMessageContent);
    //Push message to a designated user
    foreach ($roomUserMember as $key=>$value) {
      // $this->server->push($fd , json_encode($roomUserMember));
      if(!empty($users[$value])){
        $this->server->push($users[$value], json_encode($sendMessageContent));
      }
    }
  }
  /**
   * Get store in Redis the data
   * @version 1.0
   * @var string $key hash key
   * @return array
   */
  public function getRedisData( $key ){
    $redis = redisObject::connect();
    $UsersFdData = $redis->hgetall($key);
    return $UsersFdData;
  }
  /**
   * Insert data to redis
   * @version 1.0
   * @var string $key     Redis hash key
   * @var int    $field   Redis hash field
   * @var int    $value   Redis hash value
   * @return bool
   */
  public function insertRedisData( $key , $field , $value ){
    if(empty($key) || empty($field) || empty($value)){
      return false;
    }
    $redis = RedisObject::connect();
    //When user switch chat room window
    //Current time as a in current user and prior chat room the new read message the time
    if($key == 'fd_room' && $redis->hexists($key , $field)){
      $allFdUserData = $this->getRedisData( 'fd_user' );
      if(!empty($allFdUserData[$field])){
        $Rooms = Rooms::connect();
        //update user designated room the message read time
        $Rooms->updateReadTime($allFdUserData[$field] , $value);
      }
    }
    $recordResult = $redis->hset($key , $field , $value);
    return $recordResult;
  }
  /**
   * Delete desiganted user the fd data
   * @version 1.0
   * @var int $userID User ID
   * @return bool
   */
  public function deleteUserFdData( $userID ){
    if(empty($userID)){
      return false;
    }
    $redis = RedisObject::connect();
    $deleteResult = $redis->hdel('members' , $userID);
    return $deleteResult;
  }
}
