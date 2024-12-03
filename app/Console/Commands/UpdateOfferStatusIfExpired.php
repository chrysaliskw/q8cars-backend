<?php

namespace App\Console\Commands;

use App\Models\Offer;
use Illuminate\Console\Command;

class UpdateOfferStatusIfExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offer:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move offer to expired status if expired';

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
            Offer::where('end_date', '<', date('Y-m-d'))
                ->update(['status' => offer::STATUS_EXPIRED]);

        }
        catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
        
        $this->info("Neofferws expired status updated");
        return 0;
    }
}
