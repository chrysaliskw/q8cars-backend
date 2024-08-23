<?php

namespace App\Services\Admin;

use Exception;
use App\Models\Car;
use App\Models\Brand;
use App\Models\CarImage;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use App\Models\CarVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarService
{
    protected $car;
    protected $data;
    protected $version;

    public function __construct(array $data, Car $car = null)
    {
        $this->car = $car;
        $this->data = $data;
        //dd($this->data);
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
            $this->saveToCarVersion();
            $this->saveCarImages();
            // $this->saveCarVideos();
            // $this->saveCarColorsAndImages();
          
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
        $this->car->brand_id = $this->data['brand_id'];
        $this->car->model_name = $this->data['model_name'];
        // $this->car->varient_name = $this->data['varient_name'];
        $this->car->is_just_launched = $this->data['is_just_launched'];
        $this->car->sort_order = $this->data['sort_order'];
        $this->car->status = $this->data['status'];
        $this->car->ex_showroom_price = $this->data['ex_showroom_price'];
        $this->car->on_road_price = $this->data['on_road_price'];
        $this->car->finance_available = $this->data['finance_available'];
        $this->car->insurance = $this->data['insurance'];
        $this->car->service_charge = $this->data['service_charge'];
        $this->car->air_condition = $this->data['air_condition'];
        $this->car->width = $this->data['width'];
        $this->car->length = $this->data['length'];
        $this->car->height = $this->data['height'];
        $this->car->boot_space = $this->data['boot_space'];
        $this->car->power_windows = $this->data['power_windows'];
        $this->car->fuel_tank_capacity = $this->data['fuel_tank_capacity'];
        $this->car->seat_upholstery = $this->data['seat_upholstery'];
        $this->car->seat_capacity = $this->data['seat_capacity'];
        $this->car->safety_ratings = $this->data['safety_ratings'];
        $this->car->engine_capacity = $this->data['engine_capacity'];
        $this->car->power = $this->data['power'];
        $this->car->torque = $this->data['torque'];
        $this->car->drive_train = $this->data['drive_train'];
        $this->car->acceleration = $this->data['acceleration'];
        $this->car->top_speed = $this->data['top_speed'];
        $this->car->mileage = $this->data['mileage'];
       
        $this->car->image = $this->moveUploadedProfileImage();
        $this->car->fuel_types = $this->getIntValueFuel();
        $this->car->transmission_type = $this->getIntValueTransmission();
        $this->car->professions = $this->getIntValueProfession();
        $this->car->colours = $this->getIntValueColor();
        
        $this->car->why_choose = $this->data['why_choose'];
        $this->car->market_introduction = $this->data['market_introduction'];
        $this->car->engine_transmission = $this->data['engine_transmission'];
        $this->car->exterior = $this->data['exterior'];
        $this->car->interior = $this->data['interior'];
        $this->car->safety_features = $this->data['safety_features'];
        $this->car->rivals = $this->data['rivals'];

        $this->car->save();
    }

    public function saveToCarVersion()
    {
        $name = $this->car->model_name;
       
        if(isset($this->data['varient_name'])) {
            $name = $this->data['varient_name'];
            
        } 

        $this->version = new CarVersion();
        $this->version->car_id = $this->car->id;
        $this->version->is_car_spec = CarVersion::CAR_SPECIFICATION;
        $this->version->varient_name = $name;
        
        $this->version->ex_showroom_price = $this->data['ex_showroom_price'];
        $this->version->on_road_price = $this->data['on_road_price'];
        $this->version->finance_available = $this->data['finance_available'];
        $this->version->insurance = $this->data['insurance'];
        $this->version->service_charge = $this->data['service_charge'];
        
        // $this->version->professions = 1;

        $this->version->no_of_cylinders = $this->data['no_of_cylinders'];
        $this->version->engine_type = $this->data['engine_type'];
        $this->version->no_of_cylinders = $this->data['no_of_cylinders'];
        $this->version->valves_per_cylinder = $this->data['valves_per_cylinder'];
        $this->version->bore_stroke = $this->data['bore_stroke'];
        $this->version->compression_ratio = $this->data['compression_ratio'];
        $this->version->super_charge = $this->data['super_charge'];
        $this->version->engine_capacity = $this->data['engine_capacity'];
        $this->version->power = $this->data['power'];
        $this->version->torque = $this->data['torque'];
        $this->version->transmission_type = 1;
        $this->version->drive_train = $this->data['drive_train'];
        $this->version->acceleration = $this->data['acceleration'];
        $this->version->top_speed = $this->data['top_speed'];
        $this->version->mileage = $this->data['mileage'];
        $this->version->emission_norm_complains = $this->data['emission_norm_complains'];
        $this->version->fuel_tank_capacity = $this->data['fuel_tank_capacity'];
        $this->version->fuel_type = 1;
        $this->version->front_suspension = $this->data['front_suspension'];
        $this->version->rear_suspension = $this->data['rear_suspension'];
        $this->version->steering_type = $this->data['steering_type'];
        $this->version->steering_column = $this->data['steering_column'];
        $this->version->tuning_radius = $this->data['tuning_radius'];
        $this->version->front_brake_type = $this->data['front_brake_type'];
        $this->version->rear_brake_type = $this->data['rear_brake_type'];
        $this->version->alloy_wheel_front = $this->data['alloy_wheel_front'];
        $this->version->alloy_wheel_rear = $this->data['alloy_wheel_rear'];
        $this->version->power_steering = $this->data['power_steering'];
        $this->version->body_type = $this->data['body_type_id'];
        $this->version->width = $this->data['width'];
        $this->version->length = $this->data['length'];
        $this->version->height = $this->data['height'];
        $this->version->seat_upholstery = $this->data['seat_upholstery'];
        $this->version->seat_capacity = $this->data['seat_capacity'];
        $this->version->air_conditioner = $this->data['air_condition'];
        $this->version->wheel_covers = $this->data['wheel_covers'];
        // $this->version->360_view_camera = $this->data['360_view_camera'];
        $this->version->boot_space = $this->data['boot_space'];
        $this->version->power_windows = $this->data['power_windows'];
        $this->version->tachometer = $this->data['tachometer'];
        $this->version->electronic_multi_tripmeter = $this->data['electronic_multi_tripmeter'];
        $this->version->digital_odometer = $this->data['digital_odometer'];
        $this->version->LED_Taillights = $this->data['LED_Taillights'];
        $this->version->automatic_headlamps = $this->data['automatic_headlamps'];
        $this->version->LED_DRLs = $this->data['LED_DRLs'];
        $this->version->Halogen_Headlamps = $this->data['Halogen_Headlamps'];
        $this->version->LED_Headlights = $this->data['LED_Headlights'];
        $this->version->safety_ratings = $this->data['safety_ratings'];
        $this->version->anti_theft_alarm = $this->data['anti_theft_alarm'];
        $this->version->no_of_airbags = $this->data['no_of_airbags'];
        $this->version->passenger_airbags = $this->data['passenger_airbags'];
        $this->version->driver_airbags = $this->data['driver_airbags'];
        $this->version->child_safety_locks = $this->data['child_safety_locks'];
        $this->version->integrated_antenna = $this->data['integrated_antenna'];
        $this->version->apple_car_play = $this->data['apple_car_play'];
        $this->version->touch_screen = $this->data['touch_screen'];
        $this->version->speakers_rear = $this->data['speakers_rear'];
        $this->version->speakers_front = $this->data['speakers_front'];
        $this->version->radio = $this->data['radio'];
        $this->version->android_auto = $this->data['android_auto'];
        $this->version->digital_clock = $this->data['digital_clock'];
        $this->version->usb_charger = $this->data['usb_charger'];
        $this->version->bluetooth = $this->data['bluetooth'];
        // $this->version->transmission_type = intval($this->data['transmission_types']);
        // $this->version->fuel_type = intval($this->data['fuel_types']);
        $this->version->colours = $this->getIntValueColor($this->data['colors']);
        $this->version->save();
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
        $images = CarImage::where('car_id', $carId)->get();
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

    private function getIntValueFuel()
    {
        $result = null;
        if(isset($this->all_profession)) {
            foreach(config('params.cars.fuel_type') as $p) {
                $result[] = intval($p);
            }
        }else {
            foreach($this->data['fuel_types'] as $p) {
                $result[] = intval($p);
            }
        }
        return  json_encode($result);
    }

    private function getIntValueTransmission()
    {
        $result = null;
        if(isset($this->all_transmission)) {
            foreach(config('params.car.transmission_type') as $p) {
                $result[] = intval($p);
            }
        }else {
            foreach($this->data['transmission_types'] as $p) {
                $result[] = intval($p);
            }
        }
        return json_encode($result);
    }

    private function getIntValueProfession()
    {
        $result = null;
        if(isset($this->all_fuels)) {
            foreach(config('params.car.fuel_type') as $p) {
                $result[] = intval($p);
            }
        }else {
            foreach($this->data['fuel_types'] as $p) {
                $result[] = intval($p);
            }
        }
        return json_encode($result);
    }

    private function getIntValueColor()
    {
        $result = null;
       
        foreach($this->data['colors'] as $p) {
            $result[] = intval($p);
        }
        
        return json_encode($result);
    }
}
