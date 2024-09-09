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
use App\Models\CarComparisonList;
use App\Models\CarFavourite;

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
        $data['mileage_details'] = $this->getMileageDetails($car);
        $data['mileage_desc'] = $car->mileage_summary;

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
        $result['service_amount'] = 'KWD '.$version->service_charge;
        $result['gear_box'] = $version->gear_box;
        $result['power'] = $version->power;
        $result['torque'] = $version->torque;
        $result['torque_power'] = $version->power.'Bhp @'.$version->torque.'rpm';
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
            'main_image' =>  file_asset('files-car', $car->image_2),
            'showroom_price' => 'KWD '.$car->ex_showroom_price,
            'finance_available' =>'KWD '. $car->finance_available,
            'insurance' =>'KWD '. $car->insurance,
            'service_amount' => 'KWD '.$car->service_charge,
            'gear_box' => $car->gear_box,
            'torque_power' => $car->power.'Bhp @'.$car->torque.'rpm',
            'is_favourite' => CarFavourite::where('user_id',Auth::id())->where('car_id',$car->id)->first() ? 1:0,
            'tranmission_version_count' => $this->getVersionTransmissionTypesCount($car),
            'transmission_type' => $car->carSpec->transmission_type,
            'transmission_type_text' => config('params.car.transmission_type')[$car->carSpec->transmission_type],
            'available_transmission_types' =>$this->getversionTransmissionTypes($car)

        ];

        return $result;
    }

    private function getKeyFeatures(Car $car)
    {
        return [
            'Air Condition' => $car->air_condition,
            'Length' => $car->length. ' mm',
            'Width' => $car->width. ' mm',
            'Height' => $car->height. ' mm',
            'Boot Space' => $car->boot_space. ' L',
            'Power Windows' => $car->power_windows,
            'Fuel Tank Capacity' => $car->fuel_tank_capacity. 'L',
            'Seat Upholstery' => $car->seat_upholstery,
        ];
    }

    private function getKeySpecifications(Car $car)
    {
        $data['Fuel Types'] = $this->getFuelTypes($car->fuel_types);
        $data['Engine Capacity'] = $car->engine_capacity. ' cc';
        $data['Power & Torque'] = $car->power. '-'. $car->torque. ' Bph';
        // $data['Torque'] = $car->torque. 'Bph';
        $data['Drivetrain'] = $car->drive_train;
        $data['Acceleration'] = $car->acceleration.' sec';
        $data['Top Speed'] = $car->top_speed.' kmph';
        $data['Seat Capacity'] = $car->seat_capacity.' Persons';
        $data['Mileage'] = $car->mileage. ' klmp';

        $list = [];
        $i=0;
        foreach($data as $key => $value) {
            $list[$i]['title'] = $key;
            $list[$i]['value'] = $value;
            $list[$i]['icon'] = $this->findImage($key);
            $i++;
        }

        return $list;

        // return [
        //     'Fuel Types' => $this->getFuelTypes($car->fuel_types),
        //     'Engine Capacity' => $car->engine_capacity. ' cc',
        //     'Power' => $car->power. 'Bph',
        //     'Torque' => $car->torque. 'Bph',
        //     'Drive Train' => $car->drive_train,
        //     'Acceleration' => $car->acceleration.' sec',
        //     'Top Speed' => $car->top_speed.' kmph',
        //     'Seat Capacity' => $car->seat_capacity,
        //     'Mileage' => $car->mileage. ' klmp',
        // ];
    }
    private function findImage($key)
    {
        switch ($key) {
            case 'Fuel Types':
            return  asset('images/fuel_type.png');
                break;
            case 'Engine Capacity':
                return  asset('images/engine.png');
                break;
            case 'Power & Torque':
                return  asset('images/power_torque.png');
                break;
            case 'Drive Train':
                return  asset('images/drive_train.png');
                break;
            case 'Acceleration':
                return  asset('images/acceleration.png');
                break;
            case 'Top Speed':
                return  asset('images/top_speed.png');
                break; 
            case 'Seat Capacity':
                return  asset('images/seat_capacity.png');
                break;     
            default:
                return  asset('images/avg_milage.png');
                break;
        }
    }

    private function getSummary(Car $car)
    {
        return [
            'Why Choose' => $car->why_choose,
            'Market Introduction' => $car->market_introduction,
            'Engine Transmission' => $car->engine_transmission,
            'Exterior' => $car->exterior,
            'Interior' => $car->interior,
            'Safety Features' => $car->safety_features,
            'Rivals' => $car->rivals,
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
            'avg_rating' => round($car->avg_rating,1),
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
        $carTransmissionTypes = $this->getCarTransmissionTypes($car);
        $versionsByTransmission = [];
        foreach ($carTransmissionTypes as $typeKey => $typeName) {
            $versions = CarVersion::where('car_id', $car->id)
                ->where('transmission_type', $typeKey)
                ->get();
            $versionsByTransmission[$typeName] = CarDetailResource::collection($versions);
        }
        return $versionsByTransmission;
    }

    private function getNewsBanner(Car $car)
    {
        $result = News::where('car_id', $car->id)->where('show_in_detail_page', 1)->first();
        if($result){
            return NewsResource::make($result);
        }
        else{
            return [];
        }
        
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
        $version = (CarVersion::where('car_id', $car->id)->where('is_car_spec', CarVersion::CAR_SPECIFICATION)->first())->id;
        if($request->car_version_id) {
            $version = $request->car_version_id;
        }

        $varient = CarVersion::find($version);
       
        $result = [
            ['key' => 1,
            'id' => 'engine-type',
            'section' => 'Engine and Transmission',
            'features' => [
                [
                    'title' => 'Engine Type',
                    'value' => $varient->engine_type,
                ],
                [
                    'title' => 'Engine Displacement (cc)',
                    'value' => $varient->engine_capacity.'cc',
                ],
                [
                    'title' => 'Power & Torque',
                    'value' => $varient->power.'Bhp@'.$varient->torque.'rpm',
                ],
                [
                    'title' => 'Drivetrain',
                    'value' => $varient->drive_train,
                ],
                [
                    'title' => 'No. of Cylinders',
                    'value' => $varient->no_of_cylinders,
                ],
                [
                    'title' => 'Valves Per Cylinder',
                    'value' => $varient->valves_per_cylinder,
                ],              
                [
                    'title' => 'Bore X Stroke',
                    'value' => $varient->bore_stroke.'mm',
                ],
                [
                    'title' => 'Compression Ratio',
                    'value' => $varient->compression_ratio,
                ],
                [
                    'title' => 'Turbo Charger',
                    'value' => $varient->super_charge == 1 ? 'No' : 'Yes',
                ],
                [
                    'title' => 'Super Charge',
                    'value' => $varient->super_charge == 1 ? 'Yes' : 'No',
                ],
                [
                    'title' => 'Transmission Type',
                    'value' => config('params.car.transmission_type')[$varient->transmission_type],
                ],
                [
                    'title' => 'Gear Box',
                    'value' => $varient->gear_box,
                ],
            ],],
            ['key'=> 2,
            'id' => 'fuel-type',
            'section' => 'Fuel & Performance',
            'features' => [
                [
                    'title' => 'Fuel Type',
                    'value' => config('params.car.fuel_type')[$varient->fuel_type],
                ],
                [
                    'title' => 'Acceleration',
                    'value' => $varient->acceleration.'sec',
                ],
                [
                    'title' => 'Top Speed',
                    'value' => $varient->top_speed.'kmph',
                ],
                [
                    'title' => 'Mileage',
                    'value' => $varient->mileage.'kmpl',
                ],
                [
                    'title' => 'Fuel Tank Capacity',
                    'value' => $varient->fuel_tank_capacity.'L',
                ],
                [
                    'title' => 'Emission Norm Complains',
                    'value' => $varient->emission_norm_complains,
                ],
            ],],
            ['key'=> 3,
            'id' => 'suspension',
            'section' => 'Suspension, Steering & Brakes',
            'features' => [
                [
                    'title' => 'Front Suspension' ,
                    'value' => $varient->front_suspension,
                ],
                [
                    'title' => 'Rear Suspension',
                    'value' => $varient->rear_suspension,
                ],
                [
                    'title' => 'Steering Type',
                    'value' =>  $varient->steering_type,
                ],
                [
                    'title' => 'Steering Column',
                    'value' => $varient->steering_column,
                ],
                [
                    'title' => 'Tuning Radius',
                    'value' => $varient->tuning_radius.'m',
                ],
                [
                    'title' => 'Front Brake Type',
                    'value' => $varient->front_brake_type,
                ],
                [
                    'title' => 'Rear Brake Type',
                    'value' =>$varient->rear_brake_type,
                ],
                [
                    'title' => 'Alloy Wheel Front',
                    'value' => $varient->alloy_wheel_front ==1 ?'Yes':'No',
                ],
                [
                    'title' => 'Alloy Wheel Rear',
                    'value' => $varient->alloy_wheel_rear == 1? 'Yes':'No',
                ],
                [
                    'title' => 'Power Steering',
                    'value' => $varient->power_steering == 1 ? 'Yes':'No',
                ],
               
            ],],
           [ 'key' => 4,
            'id' => 'dimension-capacity',
            'section' => 'Dimensions & Capacity',
            'features' => [
                [
                    'title' => 'Body Type',
                    'value' =>  $varient->bodyType->name,
                ],
                [
                    'title' => 'Length',
                    'value' =>  $varient->length.'mm',
                ],
                [
                    'title' => 'Width',
                    'value' =>  $varient->width.'mm',
                ],
                [
                    'title' => 'Height',
                    'value' =>  $varient->height.'mm',
                ],
            ],],
            ['key' => 5,
            'id' => 'comfort-convinience',
            'section' => 'Comfort & Convenience',
            'features' => [
                [
                    'title' => 'Seat Upholstery',
                    'value' =>  $varient->seat_upholstery,
                ],
                [
                    'title' =>  'Seat Capacity',
                    'value' => $varient->seat_capacity.' Passengers', 
                ],
                [
                    'title' => 'Air Conditioner', 
                    'value' => $varient->air_conditioner ==1 ?'Yes':'No',
                ],
                [
                   'title' => 'Wheel Covers',
                   'value' => $varient->wheel_covers ==1 ?'Yes':'No',
                ],
                [
                    'title' =>   '360 VieW Camera',
                    'value' => $varient->view_camera ==1 ?'Yes':'No',
                ],
            ], ],
            ['key' => 6,
            'id' => 'interior',
            'section' => 'Interior',
            'features' => [
                [
                    'title' => 'Boot Space' ,                 
                    'value' =>  $varient->boot_space.'cubic feet',
                ],
                [
                    'title' =>  'Tachometer',
                    'value' => $varient->tachometer  ==1 ?'Yes':'No', 
                ],
                [
                    'title' => 'Electronic Multi Tripmeter', 
                    'value' =>  $varient->electronic_multi_tripmeter  ==1 ?'Yes':'No',
                ],
                [
                    'title' => 'Digital Odometer',
                    'value' => $varient->digital_odometer ==1 ?'Yes':'No',     
                ],
            ] ,],
            ['key' => 7,
            'id' => 'exterior',
            'section' => 'Exterior',
            'features' => [
                [
                    'title' => 'LED Taillights' ,                 
                    'value' =>   $varient->LED_Taillights ==1 ?'Yes':'No',
                ],
                [
                    'title' => 'Automatic Headlamps',
                    'value' => $varient->automatic_headlamps ==1 ?'Yes':'No',
                ],
                [
                   'title' => 'Adjustable Headlamps',
                   'value' => $varient->adjustable_headlamps ==1 ?'Yes':'No',
                ],
                [
                     'title' =>'LED DRLs',
                     'value'  => $varient->LED_DRLs ==1 ?'Yes':'No',
                ],
                [
                     'title' =>'Halogen Headlamps',
                     'value'  => $varient->Halogen_Headlamps ==1 ?'Yes':'No',
                ],
                [
                     'title' =>'LED Headlights',
                    'value'  => $varient->LED_Headlights ==1 ?'Yes':'No',
                ]
            ],],
            ['key' => 8,
            'id' => 'safety',
            'section' => 'Safety',            
            'features' => [
                [
                    'title' => 'Engine Type', 
                    'value' => $varient->engine_type,               
                ],
                [
                    'title' => 'Safety Ratings',
                    'value' => $varient->safety_ratings,
                ],
                [
                     'title' =>'Anti Theft Alarm',
                     'value' => $varient->anti_theft_alarm ==1 ?'Yes':'No',
                ],
                [
                   'title' => 'No of Airbags' ,
                   'value' => $varient->no_of_airbags,
                ],
                [
                    'title' => 'Passenger Airbags',
                    'value' => $varient->passenger_airbags,
                ],
                [
                    'title' =>'Driver Airbags',
                    'value' => $varient->driver_airbags,
                ],
                [
                    'title' =>'Child Safety Locks',
                    'value' => $varient->child_safety_locks ==1 ?'Yes':'No',
                ]
            ],],
            ['key' => 9,
            'id' => 'entertainment',
            'section' => 'Entertainment & Communication',       
            'features' => [
                [
                    'title' => 'Integrated Antenna', 
                    'value'=> $varient->integrated_antenna ==1 ?'Yes':'No',               
                ],
                [
                    'title' =>'Apple CarPlay',
                    'value' => $varient->apple_car_play ==1 ?'Yes':'No',
                ],
                [
                    'title'=> 'Touch Screen',
                    'value' => $varient->touch_screen ==1 ?'Yes':'No',
                ],
                [
                    'title'=> 'Speakers Rear' ,
                    'value'=> $varient->speakers_rear ==1 ?'Yes':'No',
                ],
                [
                    'title'=>'Speakers Front' ,
                    'value'=> $varient->speakers_front ==1 ?'Yes':'No',
                ],
                [
                     'title'=>'Radio',
                     'value' => $varient->radio ==1 ?'Yes':'No',
                ],
                [
                   'title'=> 'Android Auto',
                   'value' => $varient->android_auto ==1 ?'Yes':'No',
                ],
                [
                    'title'=> 'Digital Clock',
                    'value' => $varient->digital_clock ==1 ?'Yes':'No',
                ],
                [
                    'title' =>'USB & Auxiliary input',
                    'value' => $varient->usb_charger ==1 ?'Yes':'No',
                ],
                [                   
                'title'=>'Bluetooth Connectivity',
                'value' => $varient->bluetooth ==1 ?'Yes':'No',     
                ]
                ],]
        ];
        

        // $result['engine_and_transmission'] = [
        //     'Engine Type' => $varient->engine_type,
        //     'Valves Per Cylinder' => $varient->valves_per_cylinder,
        //     'No of Cylinders' => $varient->no_of_cylinders,
        //     'Bore x Stroke' => $varient->bore_stroke,
        //     'Compression Ratio' => $varient->compression_ratio,
        //     'Super Charge' => $varient->super_charge==1 ? 'Yes' : 'No',
        //     'Transmission Type' => config('params.car.transmission_type')[$varient->transmission_type],
        //     'Engine Capacity' => $varient->engine_capacity,
        // ];

        // $result['fuel_and_performance'] = [
        //     'Fuel Type' => config('params.car.fuel_type')[$varient->fuel_type],
        //     'Mileage' => $varient->mileage,
        //     'Power' => $varient->power,
        //     'Torque' => $varient->torque,
        //     'Emission Norm Complains' => $varient->emission_norm_complains,
        //     'Fuel Tank Capacity' => $varient->fuel_tank_capacity,
        // ];

        // $result['suspension_steering_brake'] = [
            // 'Front Suspension' => $varient->front_suspension,
            // 'Rear Suspension' => $varient->rear_suspension,
            // 'Steering Type' => $varient->steering_type,
            // 'Steering Column' => $varient->steering_column,
            // 'Tuning Radius' => $varient->tuning_radius,
            // 'Front Brake Type' => $varient->front_brake_type,
            // 'Rear Brake Type' => $varient->rear_brake_type,
            // 'Alloy Wheel Front' => $varient->alloy_wheel_front,
            // 'Alloy Wheel Rear' => $varient->alloy_wheel_rear,
            // 'Power Steering' => $varient->power_steering,
        // ];

        // $result['dimension_capacity'] = [
        //     'Body Type' => $varient->bodyType->name,
        //     'Length' => $varient->length,
        //     'Width' => $varient->width,
        //     'Height' => $varient->height,
        // ];

        // $result['comfort_convinience'] = [
        //     'Seat Upholstery' => $varient->seat_upholstery,
        //     'Seat Capacity' => $varient->seat_capacity,
        //     'Air Conditioner' => $varient->air_conditioner,
        //     'Wheel Covers' => $varient->wheel_covers,
        //     '360 VieW Camera' => $varient->view_camera,
        // ];

        // $result['interior'] = [
        //     'Boot Space' => $varient->boot_space,
        //     'Tachometer' => $varient->tachometer,
        //     'Electronic Multi Tripmeter' => $varient->electronic_multi_tripmeter,
        //     'Digital Odometer' => $varient->digital_odometer,
        // ];

        // $result['exterior'] = [
        //     'LED Taillights' => $varient->LED_Taillights,
        //     'Automatic Headlamps' => $varient->automatic_headlamps,
        //     'Adjustable Headlamps' => $varient->adjustable_headlamps,
        //     'LED DRLs' => $varient->LED_DRLs,
        //     'Halogen Headlamps' => $varient->Halogen_Headlamps,
        //     'LED Headlights' => $varient->LED_Headlights,
        // ];

        // $result['safety'] = [
        //     'Engine Type' => $varient->engine_type,
        //     'Safety Ratings' => $varient->safety_ratings,
        //     'Anti Theft Alarm' => $varient->anti_theft_alarm,
        //     'No of Airbags' => $varient->no_of_airbags,
        //     'Passenger Airbags' => $varient->passenger_airbags,
        //     'Driver Airbags' => $varient->driver_airbags,
        //     'Child Safety Locks' => $varient->child_safety_locks,
        // ];

        // $result['entertainment_and_comminication'] = [
        //     'Integrated Antenna' => $varient->integrated_antenna,
        //     'Apple CarPlay' => $varient->apple_car_play,
        //     'Touch Screen' => $varient->touch_screen,
        //     'Speakers Rear' => $varient->speakers_rear,
        //     'Speakers Front' => $varient->speakers_front,
        //     'Radio' => $varient->radio,
        //     'Android Auto' => $varient->android_auto,
        //     'Digital Clock' => $varient->digital_clock,
        //     'USB & Auxiliary input' => $varient->usb_charger,
        //     'Bluetooth Connectivity' => $varient->bluetooth,
        // ];
        
        return $result;
    }

    private function getRelatedNews(Car $car)
    {
        $result = News::active()->published()->where('car_id', $car->id)->limit(4)->orderBy('posted_time', 'asc')->get();
        if($result){
            return NewsResource::collection($result);
        }else{
            return [];
        }
       
    }

    public function getComparison(Car $car)
    {
        $result[] = null;
        $compareCar = CarComparisonList::where('car_id',$car->id)->first();
        if($compareCar)
        {     
            $carId1 = $compareCar->car_1_id;
            $carId2 = $compareCar->car_2_id;
            $cars = Car::whereIn('id',[$carId1,$carId2])->get();
        }else{
            // $cars = Car::where('brand_id', '!=', $car->brand_id)->where('version_id')->active()->limit(2)->get();
            $carBaseVariantBodyType = CarVersion::where('car_id', $car->id)
                ->where('is_base_varient', 1)
                ->value('body_type');

            $cars = Car::where('brand_id', '!=', $car->brand_id)
                ->whereIn('id', function ($query) use ($carBaseVariantBodyType) {
                    $query->select('car_id')
                        ->from('car_versions')
                        ->where('is_base_varient', 1)
                        ->where('body_type', $carBaseVariantBodyType);
                    })
                ->whereBetween('on_road_price', [$car->on_road_price * 0.95, $car->on_road_price * 1.05])
                //->whereNotNull('version_id')
                ->active()
                ->limit(2)
                ->get();
        }
        $i = 0;
        foreach($cars as $compare) {
            $version = CarVersion::where('car_id', $compare->id)->where('transmission_type', Car::TR_MANUAL)->first();
            $result[$i]['id'] = $compare->id;
            $result[$i]['name'] = $compare->model_name;
            $result[$i]['image'] = file_asset('files-car', $compare->image);
            $result[$i]['showroom_price'] = 'KWD '.$version->ex_showroom_price;
            $result[$i]['finance_available'] = 'KWD '.$version->finance_available;
            $result[$i]['insurance'] = 'KWD '.$version->insurance;
            $result[$i]['service_amount'] = 'KWD '.$version->service_charge;
            $result[$i]['gear_box'] = $version->gear_box;
            $result[$i]['power'] = $version->power;
            $result[$i]['torque'] = $version->torque;
            $result[$i]['transmission_type'] = $version->transmission_type;
            $result[$i]['transmission_type_text'] = config('params.car.transmission_type')[$version->transmission_type];
            $result[$i]['rating'] = $compare->avg_rating;
            $result[$i]['torque_power'] = $version->power.'Bhp @'.$version->torque.'rpm';
            $result[$i]['available_transmission_types'] =  $this->getversionTransmissionTypes($version->car);
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

    private function getMileageDetails(Car $car)
    {
        $subquery = CarVersion::select('fuel_type', 'transmission_type')
            ->where('car_id', $car->id)
            ->distinct()
            ->toBase();

        $versions = CarVersion::whereIn(DB::raw('(fuel_type, transmission_type)'), $subquery)
            ->where('car_id', $car->id)
            ->limit(2)->get();
       
        return CarDetailResource::collection($versions);

    }
    private function getCarTransmissionTypes(Car $car)
    {
        $carTransmissionTypes = [];
        foreach (json_decode($car->transmission_type) as $type) {
            if (isset(config('params.car.transmission_type')[$type])) {
                $carTransmissionTypes[$type] = config('params.car.transmission_type')[$type];
            }
        }
        return $carTransmissionTypes;
    }
    private function getVersionTransmissionTypesCount(Car $car)
    {
        foreach (json_decode($car->transmission_type) as $type) {
            if (isset(config('params.car.transmission_type')[$type])) {
                $count[config('params.car.transmission_type')[$type] ]= CarVersion::where('car_id',$car->id)->where('transmission_type',$type)->count();
            }
        }
        return $count;

       
    }
    private function  getversionTransmissionTypes(Car $car)
    {
        foreach (json_decode($car->transmission_type) as $type) {
            if (isset(config('params.car.transmission_type')[$type])) {
             if(CarVersion::where('car_id',$car->id)->where('transmission_type',$type)->count()> 0)
             {
                $res[$type]= config('params.car.transmission_type')[$type];
             }
            }
        }
        return $res;
    }
    

    
}
