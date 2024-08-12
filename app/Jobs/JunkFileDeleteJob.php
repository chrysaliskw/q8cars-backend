<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class JunkFileDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $type;
    protected $filename;
    public function __construct($type, array $filename)
    {
        $this->type = $type;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            foreach($this->filename as $file){
                if(Storage::exists($this->type.'/'.$file)){
                   Storage::delete($this->type.'/'.$file);
                }
            }
           
        }
        catch(Exception $ex){
            logger($ex);
            return back()->with('error', __('app_error'));
        }
    }
}
