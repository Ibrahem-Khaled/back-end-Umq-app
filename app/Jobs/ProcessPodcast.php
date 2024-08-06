<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Http\Controllers\Api\abdallah\FcmAbdallah;

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // /**
    //  * Execute the job.
    //  *
    //  * @return void
    //  */
    // public function handle()
    // {
    //     //
    // }


    
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 5;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public $delay = 5;

    public $seconds;

 
    public function handle( )
    {
//        $this->delay(5);

        //data
        $payload = array( );;
        $payload["type"] = "notify";
        $payload["topic"] = "android_id_16"; //just for debug in log
        $payload["title"] =  "Test Delay Title";
        $payload["message"] = "Test Delay Message";
        $payload["msg_database_id"] = 26;
        $payload["sender_id"] = 52;
        $payload["group_notification"] = 160052;

        $conFcm = new FcmAbdallah();
        $conFcm->push(  "android_id_16", "Test Delay", "Message Delay",  $payload  ) ;

            // throw new \Exception('test');
    }

}
