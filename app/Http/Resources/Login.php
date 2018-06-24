<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\Users;
use App\Http\Controllers\RedisObject;

class Login extends JsonResource
{
      /**
       * Transform the resource into an array.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return array
       */
      public function toArray($request)
      {
        // return $this;
        //start session
        session_start();
        $Users = new Users();
        // return $this;
        if(isset($this->userName) && isset($this->password) && !empty($this->userName) && !empty($this->password)){
          $user_info = $Users->login($this->userName , $this->password);
          if(!empty($user_info->name)){
            // login success
            $current_time = time();
            //生成token : md5(ip . user_name . current_time) 并存入cookie
            $token = md5($request->getClientIp() . $user_info->name . $current_time);
            //Set login token
            setcookie('login_token' , $token , $current_time + 3600 * 24 , '/');
            //将current_time存入Redis中(用于验证是否登陆)
            $Redis = RedisObject::connect();
            $Redis->setex($user_info->name.'login_time' , 3600 * 24 , $current_time);
            //将用户信息存到Session中(用于验证是否登陆)
            $_SESSION['login_user_info'] = $user_info;
            return true;
          }else{
            //login fail
            return false;
          }
        }else{
          //login fail
          return false;
        }


    }
}
