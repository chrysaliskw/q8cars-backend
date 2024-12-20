<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Offer;
use App\Models\UserNotificationMapping;
use Illuminate\Console\Command;

class UpdateNotificationStatusIfExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move notification to expired status if expired';

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
     * @return int
     */
    public function handle()
    {
        try
        {
            $notificationIds =    Notification::where('end_date', '<', date('Y-m-d'))->pluck('id');
            Notification::where('end_date', '<', date('Y-m-d'))
                ->update(['status' => Notification::STATUS_EXPIRED]);
            UserNotificationMapping ::whereIn('notification_id',$notificationIds)   
            ->update(['read_status' => Notification::EXPIRED]);

        }
        catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
        
        $this->info("Notification expired status updated");
        return 0;
    }
}
