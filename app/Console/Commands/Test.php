<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Server;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

      $pid = pcntl_fork();
      $currentTime = time();
      //find process
      if($pid == 0){
        $i = 1;
        $result = 1;
        for($i; $i < 1000000000; $i++){
          $result = $result + $i;
        }
        echo $result;
      //main process
      }else{
        // $pid = pcntl_wait($status, WUNTRACED); //取得子进程结束状态
        // if (pcntl_wifexited($status)) {
        //   echo 'Normal end';
        // }
        // echo $pid;
        echo '~~~~';
        echo time() - $currentTime;
      }

    }
}
