<?php

namespace App\Services\Admin;

use Exception;
use App\Models\Car;
use App\Models\Brand;
use App\Models\CarImage;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarService
{
    protected $car;
    protected $data;

    public function __construct(array $data, Car $car = null)
    {
        $this->car = $car;
        $this->data = $data;
    }

    public function handle()
    {
        if (! $this->car) {
            return $this->create();
        }

        return $this->update();
    }

    private function create()
    {
        DB::beginTransaction();
        try {

            $this->saveCar();
            $this->saveCarImages();
            // $this->saveCarVideos();
            // $this->saveCarColorsAndImages();
            // $this->saveVarient();

      
           
            DB::commit();

            return $this->car;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function update()
    {
        try {

          
            DB::commit();

            return $this->car;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function saveCar()
    {
        $this->car = new Car();
        // $oldImageName = $this->car->image;
        // $this->car->brand_id = $this->data['brand_id'];
        // $this->car->model_name = $this->data['model_name'];
        // $this->car->varient_name = $this->data['varient_name'];
        // $this->car->is_just_launched = $this->data['is_just_launched'];
        // $this->car->sort_order = $this->data['sort_order'];
        // $this->car->status = $this->data['status'];
        // $this->car->ex_showroom_price = $this->data['ex_showroom_price'];
        // $this->car->on_road_price = $this->data['on_road_price'];
        // $this->car->finance_available = $this->data['finance_available'];
        // $this->car->insurance = $this->data['insurance'];
        // $this->car->service_charge = $this->data['service_charge'];
        // $this->car->air_condition = $this->data['air_condition'];
        // $this->car->width = $this->data['width'];
        // $this->car->length = $this->data['length'];
        // $this->car->height = $this->data['height'];
        // $this->car->boot_space = $this->data['boot_space'];
        // $this->car->power_windows = $this->data['power_windows'];
        // $this->car->fuel_tank_capacity = $this->data['fuel_tank_capacity'];
        // $this->car->seat_upholstery = $this->data['seat_upholstery'];
        // $this->car->safety_ratings = $this->data['safety_ratings'];
        // $this->car->engine_capacity = $this->data['engine_capacity'];
        // $this->car->power = $this->data['power'];
        // $this->car->torque = $this->data['torque'];
        // $this->car->drive_train = $this->data['drive_train'];
        // $this->car->acceleration = $this->data['acceleration'];
        // $this->car->top_speed = $this->data['top_speed'];
        // $this->car->mileage = $this->data['mileage'];
        $this->car->fill($this->data);
        $this->car->image = $this->moveUploadedProfileImage();
        $this->car->fuel_types = intval($this->data['fuel_types']);
        $this->car->transmission_types = intval($this->data['transmission_types']);
        $this->car->professions = intval($this->data['professions']);
        $this->car->colors = intval($this->data['colors']);
        $this->car->save();
    }

    private function moveUploadedProfileImage()
    {
        Log::info($this->data['image']->path());
        compressAndResizeImage($this->data['image']->path(), $this->data['image']->path());
        $this->data['image']->store(Car::FILE_DIR);    // Store original image 
        compressAndResizeImage($this->data['image']->path(), $this->data['image']->path(), 'large_x');  // REsixe to 950x550 for images pages
        $this->data['image']->store(Car::FILE_DIR. DIRECTORY_SEPARATOR . 'large_x');  // Store resized image in car/large_x folder with same name.
        $hashedFileName = $this->data['image']->hashName();
      
        return $hashedFileName;                                                                                                                                                                                                                                                                                     nm       ;
    }

    private function saveCarImages()
    {
        $oldImages = $this->getOldAdditionalImages($this->car->id);
        CarImage::where('car_id', $this->car->id)->whereNull('color')->whereNotnull('section')->delete();
    
        $images = [];
        for ($i = 1; $i <= Car::MAX_NUM_IMAGES; $i++)
        {
            $name = 'image_' . $i;
            $oldName = 'image_old_' . $i;
            $removedName = 'image_removed_' . $i;
            $section = 'image_section_' . $i;
    
            if(isset($this->data[$oldName]) && !isset($this->data[$name]) && in_array($this->data[$oldName], $oldImages)){
                if (($key = array_search($this->data[$oldName], $oldImages)) !== false) {
                    unset($oldImages[$key]);
                }
            }
    
            if (isset($this->data[$name]) && $this->data[$name]->get()) {
                compressAndResizeImage($this->data[$name]->path(), $this->data[$name]->path());
                $this->data[$name]->store(Car::FILE_DIR);
                resizeImage($this->data[$name]->path(), $this->data[$name]->path(), 'large_x');
                $this->data[$name]->store(Car::FILE_DIR . '/large_x');
                $fileName = $this->data[$name]->hashName();
            }
            elseif(isset($this->data[$removedName]) && $this->data[$removedName] == 1) {
                $image = Car::find($this->data['deleted_image_id_' . $i]);
                if($image){
                    Storage::delete(Car::FILE_DIR . DIRECTORY_SEPARATOR . $image->file_name);
                    Storage::delete(Car::FILE_DIR . DIRECTORY_SEPARATOR . 'large_x' . DIRECTORY_SEPARATOR . $image->file_name);
                    $car = Car::where('id', $image->id);
                    $car->delete();
                }     
                continue;
            }
            else {
                $fileName = $this->data[$oldName] ?? null;
            }
            if (! $fileName) {
                continue;
            }
    
            $images[] = [
                'car_id' => $this->car->id,
                'file_name' => $fileName,
                'type' => CarImage::TYPE_IMAGE,
                'section' => $this->data[$section],
            ];
        }
    
        DB::table((new CarImage())->getTable())->insert($images);
        $imagesToDelete = $this->getJunkImages($images, $oldImages);
        JunkFileDeleteJob::dispatchAfterResponse(Car::FILE_DIR, $imagesToDelete);
        foreach($oldImages as $name)
        {
            Storage::delete(Car::FILE_DIR . DIRECTORY_SEPARATOR . $name);
            Storage::delete(Car::FILE_DIR . DIRECTORY_SEPARATOR . 'large_x' . DIRECTORY_SEPARATOR . $name);
        }
    
    }

    private function getOldAdditionalImages($carId)
    {
        $oldImages = [];
        $images = CarImage::where('carId', $carId)->get();
        if(!empty($images)){
          foreach($images as $image){
              $oldImages[$image->id] = $image->file_name;
          }
        }

        return $oldImages;
    }

    private function getJunkImages($images, $oldAdditionalImages)
    {
        $image = [];
        foreach($images as $img){
            $image[] = $img['file_name'];
        }
        $imagesToDelete = array_diff($oldAdditionalImages, $image);
       
        return $imagesToDelete;
    }

    private function getJson($data)
    {
        dd($data);
    }
}
