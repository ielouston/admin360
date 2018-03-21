<?php

namespace Admin360\Listeners;

use Admin360\Events\ReportMovement;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Admin360\Queue;

class UserEventsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function ReportMovement($data){

    	$queue = new Queue;
        $queue->devices = $data[0];
        $queue->business_id = $data[1];
        $queue->model = $model[2];
        $queue->action = $model[3];
        $queue->data = $model[4];
        
        if($queue->save()){
        	return $queue->id;
        }
        return 0;
    }
    public function SyncedMovement(){

    }
    /**
     * Handle the event.
     *
     * @param  ReportMovement  $event
     * @return void
     */
    public function handle(ReportMovement $event)
    {
        $events->listen('Admin360\Events\ReportMovement', 'UserEventListener@ReportMovement');
    }
}
