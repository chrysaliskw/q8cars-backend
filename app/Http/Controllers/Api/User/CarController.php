<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use App\Models\Car;
use App\Models\Faq;
use App\Models\News;
use App\Models\Review;
use App\Models\CarView;
use App\Models\CarImage;
use App\Models\CarVersion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CarResource;
use App\Http\Resources\NewsResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\FaqListResource;
use App\Http\Resources\CarImageResource;
use App\Http\Resources\CarDetailResource;
use App\Services\Api\User\Car\FilterService;
use App\Http\Controllers\Api\ApiBaseController;
use Carbon\Carbon;

class CarController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $result = null;
        $result = (new FilterService($request))->handle();

        return CarResource::collection($result)
            ->additional([
                'message' => 'Cars listing',
                'status' => Response::HTTP_OK
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $car = Car::find($id);

        $data['counts'] = $this->getCounts($car);
        $data['key_features'] = $this->getKeyFeatures($car);
        $data['key_specifications'] = $this->getKeySpecifications($car);
        $data['specification_and_features'] = $this->getAllSpecificationAndFeatures($car, $request);
        $data['version_price_mileage'] = $this->getCarVersionAndPrice($car);
        $data['summary'] = $this->getSummary($car);
        $data['compare_with_similar'] = $this->getComparison($car);
        $data['reviews'] = $this->getReviews($car);
        $data['faq'] = $this->getFaq($car);
        $data['news_banner'] = $this->getNewsBanner($car);
        $data['related_news'] = $this->getRelatedNews($car);

        try {
            if (! empty($result) && Auth::user()->isNotGuest()) {
                $this->saveCarViewCount($id);
            }
        }
        catch (Exception $ex) {
            logger($ex);
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->success(['data' => $data], 'Car Detail', Response::HTTP_OK);
    }

    /**
     * Compare section inside the detal page
     */
    public function compareSimilar(Request $request)
    {
        $car = Car::find($request->id);
        if(!$car) {
            return $this->error(null, 'Car Not Found', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $version = CarVersion::where('car_id', $car->id)->where('transmission_type', $request->transmission_type)->first();
        $result['id'] = $car->id;
        $result['name'] = $car->model_name;
        $result['image'] = file_asset('files-car', $car->image);
        $result['ex_show_room_price'] = 'KWD '.$version->ex_show_room_price;
        $result['finance_available'] = 'KWD '.$version->finance_available;
        $result['insurance'] = 'KWD '.$version->insurance;
        $result['service_amount'] = 'KWD '.$version->service_amount;
        $result['gear_box'] = $version->gear_box;
        $result['power'] = $version->power;
        $result['torque'] = $version->torque;
        $result['transmission_type'] = $version->transmission_type;
        $result['transmission_type_text'] = config('params.car.transmission_type')[$version->transmission_type];

        return $this->success(['data' => $result], 'Car Details', Response::HTTP_OK);

    }
     /**
     * Car images and videos
     */
    public function carImages(Request $request)
    {
        $car = Car::find($request->id);
        if(!$car) {
            return $this->error(null, 'Car Not Found', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $images = CarImage::when($request->section, function($query, $value) {
                $query->where('section', $value);
            })
            ->when($request->colour, function($query, $value) {
                $query->where('color', $value);
            })
            ->when($request->type, function($query, $value) {
                $query->where('type', $value);
            })
            ->get();

        return CarimageResource::collection($images)
            ->additional([
                'main_image' => file_asset('files-car', $car->image),
                'message' => 'Cars Images',
                'status' => Response::HTTP_OK
            ]);
    }

    private function getCounts(Car $car)
    {
        $result = [
            'id' => $car->id,
            'model_name' => $car->model_name,
            'brand_name' => $car->brand->name,
            'brand_id' => $car->brand_id,
            'colours' => count(json_decode($car->colours)),
            'photos' => $car->carPhotos->count() + 1,
            'videos' => $car->carVideos->count(),
            'main_image' =>  file_asset('files-car', $car->image),
        ];

        return $result;
    }

    private function getKeyFeatures(Car $car)
    {
        return [
            'air_condition' => $car->air_condition,
            'length' => $car->length. ' mm',
            'width' => $car->width. ' mm',
            'height' => $car->height. ' mm',
            'boot_space' => $car->boot_space. ' L',
            'power_windows' => $car->power_windows,
            'fuel_tank_capacity' => $car->fuel_tank_capacity. 'L',
            'seat_upholstery' => $car->seat_upholstery,
        ];
    }

    private function getKeySpecifications(Car $car)
    {
        return [
            'fuel_types' => $this->getFuelTypes($car->fuel_types),
            'engine_capacity' => $car->engine_capacity. ' cc',
            'power' => $car->power. 'Bph',
            'torque' => $car->torque. 'Bph',
            'drive_train' => $car->drive_train,
            'acceleration' => $car->acceleration.' sec',
            'top_speed' => $car->top_speed.' kmph',
            'seat_capacity' => $car->seat_capacity,
            'mileage' => $car->mileage. ' klmp',
        ];
    }

    private function getSummary(Car $car)
    {
        return [
            'why_choose' => $car->why_choose,
            'market_introduction' => $car->market_introduction,
            'engine_transmission' => $car->engine_transmission,
            'exterior' => $car->exterior,
            'interior' => $car->interior,
            'safety_features' => $car->safety_features,
            'rivals' => $car->rivals,
        ];
    }

    private function getFaq(Car $car)
    {
       $result = Faq::where('car_id', $car->id)
                    ->where('question_status', Faq::STATUS_ACTIVE)
                    ->orderBy('sort_order', 'asc')
                    ->limit(4)
                    ->get();

        return FaqListResource::collection($result);
    }

    private function getReviews(Car $car)
    {
        $result = Review::where('status', Review::STATUS_VERIFIED)
                    ->where('car_id', $car->id)
                    ->orderBy('sort_order', 'asc')
                    ->limit(4)
                    ->get();

        return [
            'avg_rating' => $car->avg_rating,
            'total_reviews_count' => $car->total_reviews_count,
            'rating_1_count' => $car->rating_1,
            'rating_2_count' => $car->rating_2,
            'rating_3_count' => $car->rating_3,
            'rating_4_count' => $car->rating_4,
            'rating_5_count' => $car->rating_5,
            'review' => ReviewResource::collection($result)
        ];
        
    }

    private function getCarVersionAndPrice(Car $car)
    {
        $versions = CarVersion::where('car_id', $car->id)->get();
        return CarDetailResource::collection($versions);
    }

    private function getNewsBanner(Car $car)
    {
        $result = News::where('car_id', $car->id)->where('show_in_detail_page', 1)->first();
        return $result;
    }

    private function getFuelTypes($fuel_types)
    {
        $result = null;
        $fuel = json_decode($fuel_types, true);
        $newArray = array_combine(range(1, count($fuel)), array_values($fuel));
        foreach($newArray as $fuelType) {
            $result[] = config('params.car.fuel_type')[$fuelType];
        }

        return $result;
    }

    private function getAllSpecificationAndFeatures(Car $car, Request $request)
    {
        $result = null;
        $version = (CarVersion::baseVarient()->first())->id;
        if($request->car_version_id) {
            $version = $request->car_version_id;
        }

        $varient = CarVersion::find($version);

        $result['engine_and_transmission'] = [
            'engine_type' => $varient->engine_type,
            'valves_per_cylinder' => $varient->valves_per_cylinder,
            'no_of_cylinders' => $varient->no_of_cylinders,
            'bore_stroke' => $varient->bore_stroke,
            'compression_ratio' => $varient->compression_ratio,
            'super_charge' => $varient->super_charge,
            'transmission_type' => config('params.car.transmission_type')[$varient->transmission_type],
            'engine_capacity' => $varient->engine_capacity,
        ];

        $result['fuel_and_performance'] = [
            'fuel_type' => config('params.car.fuel_type')[$varient->fuel_type],
            'tank_capacity' => $varient->tank_capacity,
            'mileage' => $varient->mileage,
            'power' => $varient->power,
            'torque' => $varient->torque,
            'emission_norm_complains' => $varient->emission_norm_complains,
            'fuel_tank_capacity' => $varient->fuel_tank_capacity,
        ];

        $result['suspension_steering_brake'] = [
            'front_suspension' => $varient->front_suspension,
            'rear_suspension' => $varient->rear_suspension,
            'steering_type' => $varient->steering_type,
            'steering_column' => $varient->steering_column,
            'tuning_radius' => $varient->tuning_radius,
            'front_brake_type' => $varient->front_brake_type,
            'rear_brake_type' => $varient->rear_brake_type,
            'alloy_wheel_front' => $varient->alloy_wheel_front,
            'alloy_wheel_rear' => $varient->alloy_wheel_rear,
            'power_steering' => $varient->power_steering,
        ];

        $result['dimension_capacity'] = [
            'body_type' => $varient->bodyType->name,
            'length' => $varient->length,
            'width' => $varient->width,
            'height' => $varient->height,
        ];

        $result['comfort_convinience'] = [
            'seat_upholstery' => $varient->seat_upholstery,
            'seat_capacity' => $varient->seat_capacity,
            'air_conditioner' => $varient->air_conditioner,
            'wheel_covers' => $varient->wheel_covers,
            '360_view_camera' => $varient->view_camera,
        ];

        $result['interior'] = [
            'boot_space' => $varient->boot_space,
            'tachometer' => $varient->tachometer,
            'electronic_multi_tripmeter' => $varient->electronic_multi_tripmeter,
            'digital_odometer' => $varient->digital_odometer,
        ];

        $result['exterior'] = [
            'LED_Taillights' => $varient->LED_Taillights,
            'automatic_headlamps' => $varient->automatic_headlamps,
            'adjustable_headlamps' => $varient->adjustable_headlamps,
            'LED_DRLs' => $varient->LED_DRLs,
            'Halogen_Headlamps' => $varient->Halogen_Headlamps,
            'LED_Headlights' => $varient->LED_Headlights,
        ];

        $result['safety'] = [
            'engine_type' => $varient->engine_type,
            'safety_ratings' => $varient->safety_ratings,
            'anti_theft_alarm' => $varient->anti_theft_alarm,
            'no_of_airbags' => $varient->no_of_airbags,
            'passenger_airbags' => $varient->passenger_airbags,
            'driver_airbags' => $varient->driver_airbags,
            'Child Safety Locks' => $varient->child_safety_locks,
        ];

        $result['entertainment_and_comminication'] = [
            'integrated_antenna' => $varient->integrated_antenna,
            'Apple CarPlay' => $varient->apple_car_play,
            'Touch Screen' => $varient->touch_screen,
            'Speakers Rear' => $varient->speakers_rear,
            'Speakers Front' => $varient->speakers_front,
            'Radio' => $varient->radio,
            'Android Auto' => $varient->android_auto,
            'Digital Clock' => $varient->digital_clock,
            'USB & Auxiliary input' => $varient->usb_charger,
            'Bluetooth Connectivity' => $varient->bluetooth,
        ];
        
        return $result;
    }

    private function getRelatedNews(Car $car)
    {
        $result = News::active()->published()->where('car_id', $car->id)->limit(4)->orderBy('posted_time', 'asc')->get();
        return NewsResource::collection($result);
    }

    public function getComparison(Car $car)
    {
        $result[] = null;
        $cars = Car::where('brand_id', '!=', $car->brand_id)->active()->limit(2)->get();
        $i = 0;
        foreach($cars as $compare) {
            $version = CarVersion::where('car_id', $compare->id)->where('transmission_type', Car::TR_MANUAL)->first();
            $result[$i]['id'] = $compare->id;
            $result[$i]['name'] = $compare->model_name;
            $result[$i]['image'] = file_asset('files-car', $car->image);
            $result[$i]['ex_show_room_price'] = 'KWD '.$version->ex_show_room_price;
            $result[$i]['finance_available'] = 'KWD '.$version->finance_available;
            $result[$i]['insurance'] = 'KWD '.$version->insurance;
            $result[$i]['service_amount'] = 'KWD '.$version->service_amount;
            $result[$i]['gear_box'] = $version->gear_box;
            $result[$i]['power'] = $version->power;
            $result[$i]['torque'] = $version->torque;
            $result[$i]['transmission_type'] = $version->transmission_type;
            $result[$i]['transmission_type_text'] = config('params.car.transmission_type')[$version->transmission_type];
            $i++;
        }

        return $result;
    }

    /**
     * @param int $id
     * 
     * @throws \Exception
     */
    private function saveCarViewCount($id)
    {
        DB::beginTransaction();

        try
        {
            $model = CarView::firstOrNew([
                'car_id' => $id,
                'user_id' => Auth::id()
            ]);

            if (! $model->id) {
                $count = CarView::where('car_id', $id)->count();
                Car::where('id', $id)->update(['view_count' => $count + 1]);
                
            }
            $model->updated_at = Carbon::now();
            $model->save();
            
            DB::commit();
        }
        catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
   
}
