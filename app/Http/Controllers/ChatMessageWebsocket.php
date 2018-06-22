<?php
/**
 * handle chat message the server
 * @version 1.0
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Rooms;

class ChatMessageWebsocket extends Controller
{
  public $server;
  public $users = array();
  public function __construct() {
      $this->server = new \swoole_websocket_server("0.0.0.0/chatMessage", 9502);
      $this->server->on('open', function (\swoole_websocket_server $server, $request) {
          echo "server: handshake success with fd{$request->fd}\n";
      });
      $this->server->on('message', function (\swoole_websocket_server $server, $frame) {
          $message = json_decode($frame->data);
          $Rooms = Rooms::connect();

          $roomUserMember = $Rooms->getRoomUserMember($message->room_id);

          //Record user "fd" the data
          $this->recordUserFdData($message->uid , $frame->fd);

          foreach ($roomUserMember as $key=>$value) {
              // $this->server->push($fd , json_encode($roomUserMember));
              if(!empty($this->users[$value])){
                $this->server->push($this->users[$value], $frame->data);
              }
          }
      });
      $this->server->on('close', function ($ser, $fd) {
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
   * Record designated user the fd data
   * @version 1.0
   * @var int $userID User ID
   * @var int $fd     User designated the fd
   * @return array    Data after recorded
   */
  public function recordUserFdData( $userID , $fd ){
    if(empty($userID) || empty($fd)){
      return false;
    }
    $this->users[$userID] = $fd;
    return $this->users;
  }
  /**
   * Delete desiganted user the fd data
   * @version 1.0
   * @var int $userID User ID
   * @return array data After deleting
   */
  public function deleteUserFdData( $userID ){
    if(empty($userID)){
      return false;
    }
    unset($this->users[$userID]);
    return $this->users;
  }
  /**
   * Push room list data to Client
   * @var int $userID  User ID
   * @var mixed $roomList Room list data
   * @version 1.0
   * @return null
   */
  public function pushDataToClient(){
    $Rooms = Rooms::connect();
    $RoomListData = $Rooms->getAllRoomData();
    foreach( $RoomListData as $key=>$value ){
      if(!empty($this->users[$value->user_id])){
        $userUnreadMessage = $Rooms->getUnreadMessageNumber($value->user_id , $value->room_id);
        $value->userUnreadMessage = $userUnreadMessage;
        $this->server->push($this->users[$value->user_id] , json_encode($value));
      }
    }
  }
}
