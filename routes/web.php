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
use App\Http\Controllers\SwooleChannel;
use App\Http\Controllers\JobDaemonController;
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
  // $Rooms = Rooms::connect();
  // $allRoomsData = $Rooms->getRoomUserMember(1);
  // print_r($allRoomsData);
  // echo date('Y-m-d H:i:s');

  // print_r($allRoomsData[0]->user_id);
                                  //php作为客户端使用memcache
    //memcache占内存所，占CPU少
    //memcache是通过IP直接获取的数据的，没有任何的验证---不安全，轻则数据被人查看，重则服务器被攻击
    //最好放到内网访问，不对外公开 memcache -d -u root -l 192.168.1.111 -p 11211 只允许111服务器访问
    $mem=new Memcache();

    $mem->connect("localhost", 11211);   //connect持久链接
//  $mem->addServer("www.lamp.com", 11221);
//  $mem->addServer("192.167.1.112", 11211);

    $mem->add("mystr", "this is a memcache test!", MEMCACHE_COMPRESSED, 3600);
    //$mem->add("mystr", "this is a memcache test!", MEMCACHE_COMPRESSED, 3600);//这个值添加不进去，重复键名（memcache重复添加不了）
    $mem->set("mystr", "wwwwwwwwwwwwww", MEMCACHE_COMPRESSED, 3600);  //修改键名的值 也可以replace

    $mem->delete("mystr");//删除单个
    $mem->flush();        //删除所有

    $str=$mem->get("mystr");//获取设置的值
    echo "string: ".$str."<br>";

    $mem->add("myarr", array("aaa", "bbb", "ccc", "ddd")); //存数组
    print_r($mem->get("myarr"));




    echo '<br>';
    class Person {
        var $name="zhangsan";
        var $age=10;
    }
    $mem->add("myobj", new Person); //存对象
    var_dump($mem->get("myobj"));
    echo "<br>";



    // echo $mem->getVersion();//获取memcache的版本
    // echo '<pre>';
    // print_r($mem->getStats());//获取状态
    // echo '</pre>';
    // $mem->close();
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


Route::get('testChannel' , function(){

  $chan = new Swoole\Channel(2 * 1024 * 1024); //2M
  // print_r($chan);
  var_dump($chan);
  $chan->push(1234);
  $chan->push("hello world");
  // $chan->push(array(1234, 4567));
  while($r = $chan->pop())
  {
      var_dump($r);
  }
});

Route::get('testChannelPop' , function(){
  $chan = new Swoole\Channel(2 * 1024 * 1024); //2M
  print_r($chan);
  while($r = $chan->pop())
  {
      var_dump($r);
  }
});


Route::get('testDeclare' , function(){
  declare (ticks = 1); //这句这么写表示全局的脚本都做处理
  function foo( $signo ) { //注册的函数
      echo $signo;
  }

  // register_tick_function("foo"); //注册函数，后面可以跟第2个参数，表示函数的参数

  pcntl_signal(SIGUSR1 , 'foo');
  $pid = 4922;
  posix_kill($pid, SIGUSR1);
  echo $pid;



  // $a = 1;
  // for($i=0;$i<5;$i++) { //这里的循环也是语句，会做一次判断$i<5的判断执行
      // $b = 1;
      // echo $b;
  // }
});

Route::get('testPcntl' , function(){
  //使用ticks需要PHP 4.3.0以上版本
declare(ticks = 1);

//信号处理函数
function sig_handler($signo)
{

     switch ($signo) {
         case SIGTERM:
             // 处理SIGTERM信号
             exit;
             break;
         case SIGHUP:
             //处理SIGHUP信号
             break;
         case SIGUSR1:
             echo "Caught SIGUSR1...\n";
             break;
         default:
             // 处理所有其他信号
     }

}

echo "Installing signal handler...\n";

//安装信号处理器
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP,  "sig_handler");
pcntl_signal(SIGUSR1, "sig_handler");

// 或者在PHP 4.3.0以上版本可以使用对象方法
// pcntl_signal(SIGUSR1, array($obj, "do_something");

echo "Generating signal SIGTERM to self...\n";
$pid = 4923;
echo $pid  , '___________';
//向当前进程发送SIGUSR1信号
posix_kill($pid, SIGUSR1);
});


Route::get('testLibevent' , function(){
  // $content = file_get_contents('http://www.taobao.com');
  // file_put_contents('1.html' , $content);
  for($i = 0; $i < 100; $i++){
    $pid = pcntl_fork();
    //find process
    if($pid == 0){
      $content = file_get_contents('1.html');
      file_put_contents($i.'.html' , $content);
    // main process
    }else{
      pcntl_wait($status, WUNTRACED);
      if(pcntl_wifexited($status)){
        echo 'Normal end';
      }else{
        echo 'Not normal';
      }
      echo "OK \n";
    }
  }

});
