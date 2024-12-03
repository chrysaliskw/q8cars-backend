<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;

class UpdateNewsStatusIfExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move news to expired status if expired';

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
            News::where('expiry_date', '<', date('Y-m-d'))
                ->update(['status' => News::STATUS_EXPIRED]);

        }
        catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
        
        $this->info("News expired status updated");
        return 0;
    }
}
