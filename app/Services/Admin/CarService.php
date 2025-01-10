<?php

namespace App\Services\Admin;

use App\Http\Resources\CarVersionResource;
use Exception;
use App\Models\Car;
use App\Models\Brand;
use App\Models\CarImage;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use App\Models\BrandColorMapping;
use App\Models\CarAdditonalSpecifications;
use App\Models\CarVersion;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DateTime;
use App\Exceptions\FuelTtypeAndTransmissionException;
class CarService
{
    public $car;
    protected $data;
    protected $version;
    private $oldAttributeIds;
    public $idstobedeleted;
    public $error;

    public function __construct(array $data, Car $car = null, CarVersion $version = null)
    {
        $this->car = $car;
        $this->version = $version;
        $this->data = $data;

    }

    public function handle()
    {
        // if (! $this->car) {
            return $this->create();
        // }

        // return $this->update();
    }

    private function create()
    {
        DB::beginTransaction();
        try {

            $this->saveCar();
            $this->saveToCarVersion();
            if ($this->error) {
                throw new \App\Exceptions\FuelTtypeAndTransmissionException($this->error);
            }
            $this->saveCarImages();
            $this->saveCarVideos();
            $this->saveCarColorsAndImages();

            if(isset($this->data['attribute']))
            {
                $this->saveCategoryAttributes();
            }
            $this->deleteCategoryAttributes();

            DB::commit();

            return $this->car;
        }
        catch(PostTooLargeException $ex){
            DB::rollBack();
            throw $ex;
        }
        catch (Exception $e) {
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
        if(!$this->car) {
            $this->car = new Car();
        }else {
            if($this->version) {
                $versionId = $this->version->id;
            }else{
                $versionId = $this->car->carSpec->id;
            }
            $this->oldAttributeIds = CarAdditonalSpecifications::where('car_id', $this->car->id)->where('car_version_id',$versionId)->pluck('id')->toArray();
        }

        // $oldImageName = $this->car->image;
        $this->car->brand_id = $this->data['brand_id'];
        $this->car->model_name = $this->data['model_name'];
        $this->car->is_upcoming = $this->data['is_upcoming'];
        $this->car->is_just_launched = $this->data['is_upcoming'] == 1 ? 2 :$this->data['is_just_launched'];
        $this->car->just_launch_sort_order = $this->data['just_launch_sort_order'] ?? null;
        $this->car->sort_order = $this->data['sort_order'];
        $this->car->status = $this->data['status'];
        $this->car->ex_showroom_price = $this->data['ex_showroom_price'];
        $this->car->on_road_price = $this->data['on_road_price'];
        $this->car->finance_available = $this->data['finance_available'];
        $this->car->insurance = $this->data['insurance'];
        $this->car->service_charge = $this->data['service_charge'];
        // $this->car->air_condition = $this->data['air_condition'];
        // $this->car->width = $this->data['width'];
        // $this->car->length = $this->data['length'];
        // $this->car->height = $this->data['height'];
        // $this->car->boot_space = $this->data['boot_space'];
        // $this->car->power_windows = $this->data['power_windows'];
         $this->car->fuel_tank_capacity = $this->data['fuel_tank_capacity'];
        // $this->car->seat_upholstery = $this->data['seat_upholstery'];
        $this->car->seat_capacity = $this->data['seat_capacity'];
        $this->car->safety_ratings = $this->data['safety_ratings'];
        $this->car->engine_capacity = $this->data['engine_capacity'];
        $this->car->power = $this->data['power'];
        $this->car->torque = $this->data['torque'];
        // $this->car->drive_train = $this->data['drive_train'];
        // $this->car->acceleration = $this->data['acceleration'];
        // $this->car->top_speed = $this->data['top_speed'];
        $this->car->mileage = $this->data['mileage'];
        // $this->car->gear_box = $this->data['gear_box'];

        $this->car->image = isset($this->data['image']) ? $this->moveUploadedProfileImage() : $this->car->image;
        $this->car->image_2 = isset($this->data['image_detail']) ? $this->moveUploadedProfileImage2() : $this->car->image_2;
        $this->car->fuel_types = $this->getIntValueFuel();
        $this->car->transmission_type = $this->getIntValueTransmission();
        $this->car->professions = $this->getIntValueProfession();
        $this->car->colours = $this->getIntValueColor();
        $this->car->travel_type = $this->getIntValueTravel();

        $this->car->why_choose = $this->data['why_choose'];
        $this->car->market_introduction = $this->data['market_introduction'];
        $this->car->engine_transmission = $this->data['engine_transmission'];
        $this->car->exterior = $this->data['exterior'];
        $this->car->interior = $this->data['interior'];
        $this->car->safety_features = $this->data['safety_features'];
        $this->car->rivals = $this->data['rivals'];
        $this->car->mileage_summary = $this->data['mileage_summary'];

        $this->car->save();
    }

    public function saveToCarVersion()
    {
        $name = $this->car->model_name. ' Base Varient';
        $carSpec = CarVersion::CAR_VARIENT_SPECIFICATION;
        if(!$this->version) {
            $this->version = new CarVersion();
            $carSpec = CarVersion::CAR_SPECIFICATION;
        }

        if(isset($this->data['varient_name'])) {
            $name = $this->data['varient_name'];
            $carSpec = CarVersion::CAR_VARIENT_SPECIFICATION;
        }

        $this->version->car_id = $this->car->id;
        $this->version->is_car_spec =  $this->version->is_car_spec ?? $carSpec;
        $this->version->varient_name = $name;

        $this->version->ex_showroom_price = $this->data['ex_showroom_price'];
        $this->version->on_road_price = $this->data['on_road_price'];
        $this->version->finance_available = $this->data['finance_available'];
        $this->version->insurance = $this->data['insurance'];
        $this->version->service_charge = $this->data['service_charge'];

        // $this->version->no_of_cylinders = $this->data['no_of_cylinders'];
        // $this->version->engine_type = $this->data['engine_type'];
        // $this->version->no_of_cylinders = $this->data['no_of_cylinders'];
        // $this->version->valves_per_cylinder = $this->data['valves_per_cylinder'];
        // $this->version->bore_stroke = $this->data['bore_stroke'];
        // $this->version->compression_ratio = $this->data['compression_ratio'];
        // $this->version->super_charge = $this->data['super_charge'];
        // $this->version->gear_box = $this->data['gear_box'];
        $this->version->engine_capacity = $this->data['engine_capacity'];
        $this->version->power = $this->data['power'];
        $this->version->torque = $this->data['torque'];
        $this->version->transmission_type = 1;
        //$this->version->drive_train = $this->data['drive_train'];
       // $this->version->acceleration = $this->data['acceleration'];
        // $this->version->top_speed = $this->data['top_speed'];
        $this->version->mileage = $this->data['mileage'];
        // $this->version->emission_norm_complains = $this->data['emission_norm_complains'];
        $this->version->fuel_tank_capacity = $this->data['fuel_tank_capacity'];
        $this->version->fuel_type = 1;
        $this->version->travel_type = 1;
        // $this->version->front_suspension = $this->data['front_suspension'];
        // $this->version->rear_suspension = $this->data['rear_suspension'];
        // $this->version->steering_type = $this->data['steering_type'];
        // $this->version->steering_column = $this->data['steering_column'];
        // $this->version->tuning_radius = $this->data['tuning_radius'];
        // $this->version->front_brake_type = $this->data['front_brake_type'];
        // $this->version->rear_brake_type = $this->data['rear_brake_type'];
        // $this->version->alloy_wheel_front = $this->data['alloy_wheel_front'];
        // $this->version->alloy_wheel_rear = $this->data['alloy_wheel_rear'];
        // $this->version->power_steering = $this->data['power_steering'];
        if(isset($this->data['update'])&& $this->version->body_type !== $this->data['body_type_id']){
            $this->version->body_type = $this->data['body_type_id'];
            CarVersion::where('car_id', $this->car->id)->update(['body_type' => $this->data['body_type_id']]);
        }else{
            $this->version->body_type = $this->data['body_type_id'];
        }
        // $this->version->width = $this->data['width'];
        // $this->version->length = $this->data['length'];
        // $this->version->height = $this->data['height'];
        // $this->version->seat_upholstery = $this->data['seat_upholstery'];
        $this->version->seat_capacity = $this->data['seat_capacity'];
        // $this->version->air_conditioner = $this->data['air_condition'];
        // $this->version->wheel_covers = $this->data['wheel_covers'];
        // $this->version->alloy_wheels = $this->data['alloy_wheels'];
        // $this->version->view_camera = $this->data['360_view_camera'];
        $this->version->view_camera = $this->data['view_camera'] ??null;
        // $this->version->boot_space = $this->data['boot_space'];
        // $this->version->power_windows = $this->data['power_windows'];
        // $this->version->tachometer = $this->data['tachometer'];
        // $this->version->electronic_multi_tripmeter = $this->data['electronic_multi_tripmeter'];
        // $this->version->digital_odometer = $this->data['digital_odometer'];
        // $this->version->LED_Taillights = $this->data['LED_Taillights'];
        // $this->version->automatic_headlamps = $this->data['automatic_headlamps'];
        // $this->version->adjustable_headlamps = $this->data['adjustable_headlamps'];
        // $this->version->LED_DRLs = $this->data['LED_DRLs'];
        // $this->version->Halogen_Headlamps = $this->data['Halogen_Headlamps'];
        // $this->version->LED_Headlights = $this->data['LED_Headlights'];
        // $this->version->sun_roof = $this->data['sun_roof'];
        $this->version->safety_ratings = $this->data['safety_ratings'];
        // $this->version->anti_theft_alarm = $this->data['anti_theft_alarm'];
        // $this->version->anti_brake_system = $this->data['anti_brake_system'];
        $this->version->no_of_airbags = $this->data['no_of_airbags'];
        // $this->version->passenger_airbags = $this->data['passenger_airbags'];
        // $this->version->driver_airbags = $this->data['driver_airbags'];
        // $this->version->child_safety_locks = $this->data['child_safety_locks'];
        // $this->version->integrated_antenna = $this->data['integrated_antenna'];
        // $this->version->apple_car_play = $this->data['apple_car_play'];
        // $this->version->touch_screen = $this->data['touch_screen'];
        // $this->version->speakers_rear = $this->data['speakers_rear'];
        // $this->version->speakers_front = $this->data['speakers_front'];
        // $this->version->radio = $this->data['radio'];
        // $this->version->android_auto = $this->data['android_auto'];
        // $this->version->digital_clock = $this->data['digital_clock'];
        // $this->version->usb_charger = $this->data['usb_charger'];
        // $this->version->bluetooth = $this->data['bluetooth'];

        if(isset($this->data['varient_name'])) {
            $this->version->transmission_type = $this->data['transmission_type'];
            $this->version->fuel_type = $this->data['fuel_type'];
            $this->version->travel_type = $this->data['travel_type'];
        } else {
            $this->version->transmission_type = ($this->getIntValueTransmission()[1]);
            $this->version->fuel_type = ($this->getIntValueFuel()[1]);
            $this->version->travel_type = ($this->getIntValueTravel()[1]);
        }

        // $this->version->travel_type = $this->getIntValueTravel();

        //$this->version->colours = $this->getIntValueColor($this->data['colors']);
        $this->version->colours = $this->car->colours;
        $this->version->status = $this->data['status'];
        $this->version->save();

        return $this->version;
    }

    private function moveUploadedProfileImage()
    {

        Log::info($this->data['image']->path());
        compressAndResizeImage($this->data['image']->path(), $this->data['image']->path());
        $this->data['image']->store(Car::FILE_DIR);    // Store original image
        compressAndResizeImage($this->data['image']->path(), $this->data['image']->path(), 'large_x');  // REsixe to 950x550 for images pages
        $this->data['image']->store(Car::FILE_DIR. DIRECTORY_SEPARATOR . 'large_x');  // Store resized image in car/large_x folder with same name.
        $hashedFileName = $this->data['image']->hashName();

        return $hashedFileName;
    }

    private function moveUploadedProfileImage2()
    {
        Log::info($this->data['image_detail']->path());
        compressAndResizeImage($this->data['image_detail']->path(), $this->data['image_detail']->path());
        $this->data['image_detail']->store(Car::FILE_DIR);    // Store original image
        compressAndResizeImage($this->data['image_detail']->path(), $this->data['image_detail']->path(), 'large_x');  // REsixe to 950x550 for images pages
        $this->data['image_detail']->store(Car::FILE_DIR. DIRECTORY_SEPARATOR . 'large_x');  // Store resized image in car/large_x folder with same name.
        $hashedFileName = $this->data['image_detail']->hashName();

        return $hashedFileName;
    }


    private function saveCarImages()
    {
        $oldImages = $this->getOldAdditionalImages($this->car->id);

        CarImage::where('car_id', $this->car->id)->whereNull('color')->whereNotNull('section')->where('type', CarImage::TYPE_IMAGE)->delete();

        $images = [];
        for ($i = 0; $i <= Car::MAX_NUM_IMAGES; $i++)
        {
            $name = 'image_' . $i;
            $oldName = 'image_old_' . $i;
            $removedName = 'image_removed_' . $i;
            $section = 'img_section_' . $i;

            if(isset($this->data[$oldName]) && !isset($this->data[$name]) && in_array($this->data[$oldName], $oldImages)){
                if (($key = array_search($this->data[$oldName], $oldImages)) !== false) {
                    unset($oldImages[$key]);
                }
            }

            if (isset($this->data[$name]) && $this->data[$name]->get()) {
                Log::info("file uploaded ");
                compressAndResizeImage($this->data[$name]->path(), $this->data[$name]->path());
                $this->data[$name]->store(Car::FILE_DIR);
                // resizeImage($this->data[$name]->path(), $this->data[$name]->path(), 'large_x');
                // $this->data[$name]->store(Car::FILE_DIR . '/large_x');
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

    private function saveCarColorsAndImages()
    {
        $existingColors = BrandColorMapping::where('brand_id', $this->car->brand_id)->pluck('id')->toArray();
        $submittedColors = $this->data['colors'] ?? [];
        $uncheckedColors = array_diff($existingColors, $submittedColors);
        $imagesToDelete = CarImage::where('car_id', $this->car->id)
                                ->whereIn('color', $uncheckedColors)
                                ->where('type', CarImage::TYPE_IMAGE)
                                ->pluck('file_name')
                                ->toArray();
        CarImage::where('car_id', $this->car->id)
                ->whereIn('color', $uncheckedColors)
                ->where('type', CarImage::TYPE_IMAGE)
                ->delete();

        foreach ($imagesToDelete as $name) {
            Storage::delete(Car::FILE_DIR . DIRECTORY_SEPARATOR . $name);
            Storage::delete(Car::FILE_DIR . DIRECTORY_SEPARATOR . 'large_x' . DIRECTORY_SEPARATOR . $name);
        }
        $images = [];
        foreach ($submittedColors as $color) {
            $imageData = $this->handleImageUpload($color);

            if ($imageData) {
                $images[] = [
                    'car_id' => $this->car->id,
                    'type' => CarImage::TYPE_IMAGE,
                    'color' => $color,
                    'file_name' => $imageData['file_name'],
                ];
            }
        }

        if (!empty($images)) {
            foreach ($images as &$image) {

                $existingImage = CarImage::where('car_id', $this->car->id)
                    ->where('color', $image['color'])
                    ->where('type', CarImage::TYPE_IMAGE)
                    ->first();

                if ($existingImage) {

                    $image['id'] = $existingImage->id;

                    $existingImage->file_name = $image['file_name'];
                    $existingImage->color = $image['color'];
                    $existingImage->save();
                }else{
                    $addedImage = new CarImage();
                    $addedImage->car_id = $this->car->id;
                    $addedImage->type =  CarImage::TYPE_IMAGE;
                    $addedImage->color =  $image['color'];
                    $addedImage->file_name =  $image['file_name'];
                    $addedImage->save();
                }
            }

           // DB::table((new CarImage())->getTable())->upsert($images, ['car_id','type', 'color'], ['file_name']);
        }
        JunkFileDeleteJob::dispatchAfterResponse(Car::FILE_DIR, $imagesToDelete);
        $uploadedColors = CarImage::where('car_id',$this->car->id)->where('type',CarImage::TYPE_IMAGE)->whereNotNull('color')->pluck('color')->toArray();;
        $this->car->colours = $uploadedColors;
        $this->car->save();
        $this->car->carVersions()->update(['colours' => json_encode($uploadedColors)]);

    }

    /**
     * Handle image upload and resizing
     *
     * @param string $color
     * @return array|null
     */
    private function handleImageUpload($color)
    {
        $name = 'colors_image_' . $color;
        $oldName = 'colors_image_old_' . $color;

        if (isset($this->data[$name]) && $this->data[$name]->get()) {
            $file = $this->data[$name];
            compressAndResizeImage($file->path(), $file->path());
            $file->store(Car::FILE_DIR);
            $fileName = $file->hashName();
        } else {
            $fileName = $this->data[$oldName] ?? null;
        }

        return $fileName ? ['file_name' => $fileName] : null;
    }

    private function getOldAdditionalImages($carId)
    {
        $oldImages = [];
        $images = CarImage::where('car_id', $carId)->whereNull('color')->whereNotNull('section')->where('type', CarImage::TYPE_IMAGE)->get();
        if(!empty($images)){
            foreach($images as $image){
                $oldImages[$image->id] = $image->file_name;
            }
        }

        return $oldImages;
    }


    private function getOldAdditionalImagesColor($carId)
    {
        $oldImages = [];
        $images = CarImage::where('car_id', $carId)->whereNotNull('color')->whereNull('section')->where('type', CarImage::TYPE_IMAGE)->get();
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
        $result = [];
        $i = 0;
        if(isset($this->data['all_fuels']) && $this->data['all_fuels'] == 1) {
            foreach(config('params.car.fuel_type') as $key => $value) {
                $result[$i] = intval($key);
                $i++;
            }
        }else {
            // if ($this->data['update']) {
            if (isset($this->data['update']) && $this->data['update']){
                $i = 0;
                $existingFuelTypes = Carversion::where('car_id', $this->car->id)->pluck('fuel_type')->toArray();
                // dd($existingFuelTypes);
                $result = [];
                foreach ($this->data['fuel_types'] as $p) {
                       $result[$i] = intval($p);
                        $i++;
                }
                $excludedFuelTypes = array_diff($existingFuelTypes, $result);
                // dd($excludedFuelTypes);
                // if (!empty($excludedFuelTypes)) {
                //  $this->error = 'The fuel types already assigned to car versions cannot be excluded';
                // }
                if (!empty($excludedFuelTypes)) {
                    Log::info('Fuel types removed during update: ' . implode(', ', $excludedFuelTypes));
                }
            }else{
                foreach($this->data['fuel_types'] as $p) {
                    $result[$i] = intval($p);
                    $i++;
                }
            }
        }
        return  json_encode($result);
    }

    private function getIntValueTravel()
    {
        $result = [];
        $i = 0;
        if(isset($this->data['all_travel']) && $this->data['all_travel'] == 1) {
            foreach(config('params.car.travel_type') as $key => $value) {
                $result[$i] = intval($key);
                $i++;
            }
        }else {
            foreach($this->data['travel_type'] as $tr) {
                $result[$i] = intval($tr);
                $i++;
            }
        }
        return  json_encode($result);
    }

    private function getIntValueTransmission()
    {
        $result = [];
        $i = 0;
        if(isset($this->data['all_transmissions']) && $this->data['all_transmissions'] == 1) {
            foreach(config('params.car.transmission_type') as $key => $value) {
                $result[$i] = intval($key);
                $i++;
            }
        }else {
            // if ($this->data['update']) {
            if (isset($this->data['update']) && $this->data['update']){
                $i = 0;
                $existingTranmissionTypes = Carversion::where('car_id', $this->car->id)->pluck('transmission_type')->toArray();
                foreach ($this->data['transmission_types'] as $p) {
                       $result[$i] = intval($p);
                        $i++;
                }
                $excludedTTypes = array_diff($existingTranmissionTypes, $result);
                if (!empty($excludedTTypes)) {
                    $this->error = 'The transmission types already assigned to car versions cannot be excluded';
                }
            }else{
                foreach($this->data['transmission_types'] as $p) {
                    $result[$i] = intval($p);
                    $i++;
                }
            }
        }
        return json_encode($result);
    }

    private function getIntValueProfession()
    {
        $result = [];
        $i = 0;
        if(isset($this->data['all_profession']) && $this->data['all_profession'] == 1) {
            foreach(config('params.professions') as $key => $value) {
                $result[$i] = intval($key);
                $i++;
            }
        }else {
            if(isset($this->data['professions'])){
                foreach($this->data['professions'] as $p) {
                    $result[$i] = intval($p);
                    $i++;
                }
            }
        }
        return json_encode($result);
    }

    private function getIntValueColor()
    {
        $result = null;
        $i = 0;
        foreach($this->data['colors'] as $p) {
            $result[$i] = intval($p);
            $i++;
        }

        return json_encode($result);
    }

    private function saveCarVideos()
    {
        // dd($this->data);
        $videos = [];
        $idstobedeleted = [];

        if(isset($this->data['update']))
        {
            $videosArr = $this->car->carVideos()->pluck('thumbnail')->toArray();
            $carVediosiIds = $this->car->carVideos()->pluck('id')->toArray();
        }
        for($i =1; $i <=3 ; $i++)
        {
            $title = 'title_'.$i;
            $video = 'video_'.$i;
            if(isset($this->data[$video])) {
                $description = 'description_'.$i;
                $date = 'date_'.$i;
                $thumbnail = 'thumbnail_'.$i;
                $postedMedia = 'posted_media_'.$i;

                $this->data[$video]->store(Car::FILE_DIR);
                $fileNameVideo = $this->data[$video]->hashName();
                if(isset($this->data[$thumbnail])){
                    $this->data[$thumbnail]->store(Car::FILE_DIR);
                    $fileNameThumbnail = $this->data[$thumbnail]->hashName();
                }else{
                    $fileNameThumbnail = $videosArr[$i-1] ?? '';
                }

                if(isset($videosArr[$i-1])) {
                    $idstobedeleted[] = $carVediosiIds[$i-1];
                }
                $videos[] = [
                    'car_id' => $this->car->id,
                    'file_name' => $fileNameVideo,
                    'type' => CarImage::TYPE_VIDEO,
                    'thumbnail' => $fileNameThumbnail,
                    'video_title' => $this->data[$title],
                    'video_description' => $this->data[$description],
                    'video_posted_date' =>  $this->data[$date] ? (new DateTime($this->data[$date]))->format('Y-m-d'):'',
                    'video_posted_media' => $this->data[$postedMedia],
                ];
                // dd($idstobedeleted);
            }

        }
        DB::table((new CarImage())->getTable())->insert($videos);
        CarImage::whereIn('id',$idstobedeleted)->delete();
    }

    public function saveCategoryAttributes()
    {
        $attributes = [];

       foreach ($this->data['attribute'] as $index => $name)
        {
            if ($index == $this->data['row_count']) {
                break;
            }

            if (empty($name)) {
                continue;
            }

            if (isset($this->data['attribute_id']) && isset($this->data['attribute_id'][$index]))
            {

                $categoryAttribute = CarAdditonalSpecifications::find($this->data['attribute_id'][$index]);
                $categoryAttribute->specification = $name;
            }
            else
            {
                $categoryAttribute = new CarAdditonalSpecifications();
            }

            $categoryAttribute->car_id = $this->car->id;
            $categoryAttribute->car_version_id = $this->version->id;
            $categoryAttribute->input_type = $this->data['input_type'][$index];
            $categoryAttribute->specification = $this->data['attribute'][$index];
            $categoryAttribute->category_id = $this->data['section'][$index];
            $categoryAttribute->unit = $this->data['units'][$index];
            $categoryAttribute->is_key_feature = $this->data['key_feature'][$index];
            $categoryAttribute->is_key_spec = $this->data['key_spec'][$index];
            if (isset($this->data['attribute_id']) && isset($this->data['attribute_id'][$index]))
            {
                $categoryAttribute->key_icon = $this->data['icon'][$index] !== null ? $this->uploadKeyIcon($this->data['icon'][$index]) : $categoryAttribute->key_icon ;
            }
            else
            {
                $categoryAttribute->key_icon = $this->data['icon'][$index] !== null ? $this->uploadKeyIcon($this->data['icon'][$index]) :'';
            }

            if($this->data['input_type'][$index] == CarAdditonalSpecifications::TYPE_TEXT) {
                $categoryAttribute->value = $this->data['text_value'][$index];
            }else {
                $categoryAttribute->value = $this->data['bool_value'][$index];
            }

            $categoryAttribute->saveOrFail();

        }
    }

    private function deleteCategoryAttributes()
    {
        if (empty($this->oldAttributeIds)) {
            return;
        }

        if (empty($this->car)) {
            return;
        }

        if (!is_array($this->data['attribute_id'])) {
            $this->data['attribute_id'] = array($this->data['attribute_id']);
        }

        $ids = array_diff($this->oldAttributeIds, $this->data['attribute_id']);
        if (empty($ids)) {
            return;
        }

        // $idsNotTodelete = CarAdditonalSpecifications::where('car_id',$this->car->id)->where('car_version_id',$this->version->id)->pluck('id')->toArray();
        //     $ids = array_diff($ids, $idsNotTodelete);

        CarAdditonalSpecifications::destroy($ids);
    }
    private function uploadKeyIcon($image)
    {
        Log::info($image->path());
        compressAndResizeImage($image->path(), $image->path());
        $image->store(Car::FILE_DIR);
        $hashedFileName = $image->hashName();

        return $hashedFileName;
    }
    private function updateCarColour($colors)
    {
        $result = null;
        $i = 0;
        foreach($colors as $p) {
            $result[$i] = intval($p);
            $i++;
        }

        return json_encode($result);
    }
}
