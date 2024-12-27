<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use App\Models\View360Image;
use Illuminate\Console\Command;

class Upload360Images extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '360images:upload';

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
            $carIds = [48, 47, 40];
            $imageCount = 32; // Number of images per car
            $records = [];
            
            foreach ($carIds as $carId) {
                for ($i = 1; $i <= $imageCount; $i++) {
                    $records[] = [
                        'car_id' => $carId,
                        'image' => "output_{$i}.jpg",
                        'status' => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }
            
            // Batch insert the records into the database
            View360Image::insert($records);

        }
        catch (\Exception $ex) {
            return $this->error($ex->getMessage());
        }
        
        $this->info("News expired status updated");
        return 0;
    }
}
