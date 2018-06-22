<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\Users;
use App\Http\Resources\Users as UserResource;
use App\Http\Resources\Login;
use App\Http\Controllers\Client;
use App\Http\Resources\UploadImages;
use App\Http\Controllers\RedisObject;
use App\Http\Controllers\Rooms;
// use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

//register
Route::get('register' , function ( ) {
  // echo $request->getClientIp();
  $users = new Users();
  //if current action equal 'sumbit'
  if(isset($_GET['action']) && $_GET['action'] == 'submit'){
    //get user name
    $user_name = $_GET['user_name'];
    //get user password
    $password = $_GET['password'];
    //encryption the password
    $token = 'test_password';
    $password = md5($password . $token);
    //Inserting data into the database
    $result = $users->insert($user_name , $password , '');
    //redirect to register page
    header('location:'.'http://chenbingji.com/register');
  }
  $user_data = $users->get(1);
  return view('register' , ['user_data'=>$user_data]);
})->middleware('web');

//login
Route::match(['get' , 'post'] , 'login' , function () {
  return view('login');
})->middleware('web');
Route::post('loginServer/{userName}/{password}' , function ( $userName , $password ) {
  class login_info{
    public $userName = '15914040778';
    public $password = '8911001';
  };
  $login_info = new login_info();

  if(!empty($userName)){
    $login_info->userName = $userName;
  }
  if(!empty($password)){
    $login_info->password = $password;
  }
  return new Login($login_info);
})->middleware('web');
//chat
Route::get('chat' , function (){
  session_start();
  if(!empty($_SESSION['login_user_info'])){
    $userInfo = $_SESSION['login_user_info'];
  }else{
    $Client = new Client();
    $ip = $Client->ip();
    $newIp = str_replace('.' , '' , $ip);
    class userInfo{
      public $name = '';
      public $id = '';
    }
    $userInfo = new userInfo();
    $userInfo->name = $ip;
    $userInfo->id = $newIp;
  }
  return view('chat' , ['userInfo'=>$userInfo]);
})->middleware('web');


Route::get('test' , function () {
  $Rooms = Rooms::connect();
  $allRoomsData = $Rooms->getRoomUserMember(1);
  print_r($allRoomsData);
  // print_r($allRoomsData[0]->user_id);
});

/**
 * upload image
 * @var $imageObject  Image Object
 * @return Object     Image uploaded data(after uploading image the images data)
*/
Route::get('upload/images' , function ( ) {
  if(empty($_POST['imageObject'])){
    return false;
  }
  return new UploadImages($_POST['imageObejct']);
});


Route::get('testChat' , function () {
  // session_start();
  if(!empty($_SESSION['login_user_info'])){
    $userInfo = $_SESSION['login_user_info'];
  }else{
    $Client = new Client();
    $ip = $Client->ip();
    $newIp = str_replace('.' , '' , $ip);
    class userInfo{
      public $name = '';
      public $id = '';
    }
    $userInfo = new userInfo();
    $userInfo->name = $ip;
    $userInfo->id = $newIp;
  }
  return view('testChat' , ['userInfo'=>$userInfo]);
})->middleware('login');

Route::get('testRedis' , function () {
  $redis = RedisObject::getRedisConn();
  $redis->lpush('chatContents' , $redis->lpush('content' , 'CeShi'));
  $chatContents = $redis->lrange('chatContents' , 0 , -1);
  print_r($chatContents);
});
