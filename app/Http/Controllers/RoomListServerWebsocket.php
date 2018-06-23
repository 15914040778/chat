<?php
/**
 * handle room list the server
 * @version 1.0
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Rooms;
// use

class RoomListServerWebsocket extends Controller
{
    public $server;
    public $users = array();
    public function __construct() {

        $this->server = new \swoole_websocket_server("0.0.0.0/roomList", 9501);
        $this->server->on('open', function (\swoole_websocket_server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $this->server->on('message', function (\swoole_websocket_server $server, $frame) {
            $Rooms = Rooms::connect();
            $message = json_decode($frame->data);
            switch ($message->action) {
              case 'open':
                //recoed User 'fd' Data
                $this->recordUserFdData($message->uid , $frame->fd);
                break;
              case 'updateReadTime':
                //Update user read chat room message time
                $Rooms->updateReadTime($message->uid , $message->room_id);
                break;
              // case ''
              default:

                break;
            }
            //return current room the state
            $this->pushDataToClient();

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
