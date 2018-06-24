<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


declare(ticks = 1);
class JobDaemonController extends Yaf_Controller_Abstract{

    use Trait_Redis;

    private $maxProcesses = 800;
    private $child;
    private $masterRedis;
    private $redis_task_wing = 'task:wing'; //待处理队列

    public function init(){
        // install signal handler for dead kids
        pcntl_signal(SIGCHLD, array($this, "sig_handler"));
        set_time_limit(0);
        ini_set('default_socket_timeout', -1); //队列处理不超时,解决redis报错:read error on connection
    }

    private function redis_client(){
        $rds = new Redis();
        $rds->connect('redis.master.host',6379);
        return $rds;
    }

    public function process(swoole_process $worker){// 第一个处理
        $GLOBALS['worker'] = $worker;
        swoole_event_add($worker->pipe, function($pipe) {
            $worker = $GLOBALS['worker'];
            $recv = $worker->read();            //send data to master

            sleep(rand(1, 3));
            echo "From Master: $recv\n";
            $worker->exit(0);
        });
        exit;
    }

    public function testAction(){
        for ($i = 0; $i < 10000; $i++){
            $data = [
                'abc' => $i,
                'timestamp' => time().rand(100,999)
            ];
            $this->masterRedis->lpush($this->redis_task_wing, json_encode($data));
        }
        exit;
    }

    public function runAction(){
        while(1){
//            echo "\t now we de have $this->child child processes\n";
            if ($this->child < $this->maxProcesses){
                $rds = $this->redis_client();
                $data_pop = $rds->brpop($this->redis_task_wing, 3);//无任务时,阻塞等待
                if (!$data_pop){
                    continue;
                }
                echo "\t Starting new child | now we de have $this->child child processes\n";
                $this->child++;
                $process = new swoole_process([$this, 'process']);
                $process->write(json_encode($data_pop));
                $pid = $process->start();
            }
        }
    }

    private function sig_handler($signo) {
//        echo "Recive: $signo \r\n";
        switch ($signo) {
            case SIGCHLD:
                while($ret = swoole_process::wait(false)) {
//                    echo "PID={$ret['pid']}\n";
                    $this->child--;
                }
        }
    }



}
