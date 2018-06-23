<?php

namespace App\Http\Controllers;

//mysql database
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Client;


class Users extends Controller
{
    /**
     * get user data
     * @var  $user_id   User id
     * @version 1.0
     * @return array
    */
    public function get( $user_id )
    {
      if(empty($user_id)){
        return false;
      }
      $user_data = DB::table('users')
                  ->where('id' , $user_id)
                  ->first();
      return $user_data;
    }
    /**
     *  insert user data
     *  @var      $user_name       User name
     *  @var      $user_password   User password
     *  @var      $user_ip         User ip
     *  @version 1.0
     *  @return   int|bool
    */
    public function insert( $user_name , $user_password , $user_ip )
    {
      if(empty($user_name) || empty($user_password)){
        return false;
      }
      $date = date('Y-m-d');
      $insert_result = DB::table('users')
                      ->insert(
                        [
                          'name'=>$user_name,
                          'ip'=>$user_ip,
                          'password'=>$user_password,
                          'created_at'=>$date,
                          'updated_at'=>$date
                        ]
                      );
      return $insert_result;
    }
    /**
     * update user password data
     * @var     $user_id        User id
     * @var     $user_password  User PassWord
     * @version 1.0
     * @return  int|bool
    */
    public function update_password( $user_id , $user_password )
    {
      if(empty($user_id) || empty($user_password)){
        return false;
      }
      $time = time();
      $update_result = DB::table('users')
                      ->where('user_id',$user_id)
                      ->update([
                          'password'=>$user_password,
                          'updated_at'=>$time
                        ]);
      return $update_result;
    }
    /**
     * Login
     * Verification User name and User Password
     * @var     $userName   User name
     * @var     $password   User Password
     * @version 1.0
     * @return  bool|int
    */
    public function login( $userName , $password )
    {
      if( empty($userName) || empty($password) ){
        return false;
      }
      //encryption the password
      $token = 'test_password';
      $password = md5($password . $token);
      $loginResult = DB::table('users')
                        ->where('name' , $userName)
                        ->where('password' , $password)
                        ->first();
      return $loginResult;
    }
    /**
     * Verification whether login
     * @var   $request    \Illuminate\Http\Request
     * @version 1.0
    */
    public function whether_login( )
    {
      session_start();
      $Client = new Client();
      $Redis = RedisObject::connect();
      //get Client ip
      $Client_ip = $Client->ip();
      //Get the user info that is stored stay session
      if(!empty($_SESSION['login_user_info'])){
        $userInfo = $_SESSION['login_user_info'];

        //get current user login to time
        $currentUserLoginTime = $Redis->get($userInfo->name . 'login_time');
        $token = md5($Client_ip . $userInfo->name . $currentUserLoginTime);
        //get login token
        $login_token = $_COOKIE['login_token'];
        if($login_token == $token){
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }

    }

}
