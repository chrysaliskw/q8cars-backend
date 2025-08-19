<?php

namespace App\Imports;

use Exception;
use App\Models\Car;
use App\Models\Brand;
use App\Models\BodyType;
use App\Models\CarImage;
use App\Models\CarVersion;
use Illuminate\Support\Str;
use Illuminate\Bus\Batchable;
use App\Models\BrandColorMapping;
use Illuminate\Http\UploadedFile;
use App\Services\Admin\CarService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Models\CarAdditonalSpecifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CarBulkImport implements ToCollection, WithChunkReading, WithHeadingRow, ShouldQueue
{
    use Batchable;

    protected $version;
    public $result;

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    private $configFieldMappings = [
        'fuel_types' => 'car.fuel_type',
        'transmission_types' => 'car.transmission_type',
        '360_view_camera' => 'car.view-camera',
        'travel_type' => 'car.travel_type',
        'professions' => 'professions',
        'status' => 'car.status',
        'category' => 'car.specification-section',
    ];

    public function collection(Collection $rows)
    {
        Log::info("Starting car bulk import.");

        $successfulImports = 0;
        $failedImports = 0;
        $skippedRows = 0;
        $rowErrors = [];
        $rowHasError = false;

        foreach ($rows as $index => $row) {
            DB::beginTransaction();
            try {
                $row = $row instanceof Collection ? $row->toArray() : $row;

                if (collect($row)->filter(fn($value) => !is_null($value) && trim($value) !== '')->isEmpty()) {
                    Log::info("Skipping empty row at index $index.");
                    $skippedRows++;
                    DB::rollBack();
                    continue;
                }

                Log::info("Processing row $index: " . json_encode($row));

                $headersToFields = [
                    'brand' => 'brand_id',
                    'model' => 'model_name',
                    'reference_no' => 'car_ref_no',
                    'image' => 'image',
                    'image_2' => 'image_2',
                    'is_upcoming' => 'is_upcoming',
                    'is_just_launched' => 'is_just_launched',
                    'just_launch_sort_order' => 'just_launch_sort_order',
                    'sort_order' => 'sort_order',
                    'status' => 'status',
                    'ex_showroom_price' => 'ex_showroom_price',
                    'on_road_price' => 'on_road_price',
                    'finance_available' => 'finance_available',
                    'insurance' => 'insurance',
                    'service_charge' => 'service_charge',
                    'fuel_tank_capacity' => 'fuel_tank_capacity',
                    'power' => 'power',
                    'torque' => 'torque',
                    'professions' => 'professions',
                    'colors' => 'colors',
                    'travel_type' => 'travel_type',
                    'why_choose' => 'why_choose',
                    'market_introduction' => 'market_introduction',
                    'engine_transmission' => 'engine_transmission',
                    'exterior' => 'exterior',
                    'interior' => 'interior',
                    'safety_features' => 'safety_features',
                    'rivals' => 'rivals',
                    'mileage_summary' => 'mileage_summary',
                    'varient_name' => 'varient_name',
                    'engine_capacity' => 'engine_capacity',
                    'power_and_torque' => 'power_and_torque',
                    'transmission_types' => 'transmission_types',
                    'drivetrain' => 'drivetrain',
                    'engine_type' => 'engine_type',
                    'no_of_cylinders' => 'no_of_cylinders',
                    'valve_all_cylinder' => 'valve_all_cylinder',
                    'bore_and_stroke' => 'bore_and_stroke',
                    'compression_ratio' => 'compression_ratio',
                    'super_charge' => 'super_charge',
                    'fuel_types' => 'fuel_types',
                    'mileage' => 'mileage',
                    'acceleration' => 'acceleration',
                    'top_speed' => 'top_speed',
                    'emission_norm_compliance' => 'emission_norm_compliance',
                    'front_suspension' => 'front_suspension',
                    'rear_suspension' => 'rear_suspension',
                    'steering_type' => 'steering_type',
                    'turning_radius' => 'turning_radius',
                    'front_brake_type' => 'front_brake_type',
                    'rear_brake_type' => 'rear_brake_type',
                    'power_steering' => 'power_steering',
                    'body_type' => 'body_type_id',
                    'length' => 'length',
                    'width' => 'width',
                    'height' => 'height',
                    'seat_capacity' => 'seat_capacity',
                    '360_view_camera' => '360_view_camera',
                    'air_condition' => 'air_condition',
                    'seat_upholstery' => 'seat_upholstery',
                    'wheel_covers' => 'wheel_covers',
                    'boot_space' => 'boot_space',
                    'power_windows' => 'power_windows',
                    'tachometer' => 'tachometer',
                    'electric_multi_trip_meter' => 'electric_multi_trip_meter',
                    'digital_odo_meter' => 'digital_odo_meter',
                    'led_tail_lights' => 'led_tail_lights',
                    'automatic_head_lamps' => 'automatic_head_lamps',
                    'adjustable_head_lamps' => 'adjustable_head_lamps',
                    'safety_ratings' => 'safety_ratings',
                    'no_of_airbags' => 'no_of_airbags',
                    'anti_theft_alarm' => 'anti_theft_alarm',
                    'child_safety_locks' => 'child_safety_locks',
                    'integrated_antenna' => 'integrated_antenna',
                    'usb_and_auxiliary_input' => 'usb_and_auxiliary_input',
                    'bluetooth_connectivity' => 'bluetooth_connectivity',
                ];

                $specMapping = [
                    // Engine and Transmission (ID: 1)
                    // 'power_and_torque' => ['category' => 1, 'input_type' => 1],
                    'drivetrain' => ['category' => 1, 'input_type' => 1],
                    'engine_type' => ['category' => 1, 'input_type' => 1],
                    'no_of_cylinders' => ['category' => 1, 'input_type' => 1],
                    'valve_all_cylinder' => ['category' => 1, 'input_type' => 1],
                    'bore_and_stroke' => ['category' => 1, 'input_type' => 1],
                    'compression_ratio' => ['category' => 1, 'input_type' => 1],
                    'super_charge' => ['category' => 1, 'input_type' => 1],

                    // Fuel and Performance (ID: 2)
                    'acceleration' => ['category' => 2, 'input_type' => 1, 'unit' => 'sec'],
                    'top_speed' => ['category' => 2, 'input_type' => 1, 'unit' => 'km/h'],
                    'emission_norm_compliance' => ['category' => 2, 'input_type' => 1, 'unit' => 'g/km'],

                    //Suspension, Steering and Brake (ID: 3)
                    'front_suspension' => ['category' => 3, 'input_type' => 1],
                    'rear_suspension' => ['category' => 3, 'input_type' => 1],
                    'steering_type' => ['category' => 3, 'input_type' => 1],
                    'turning_radius' => ['category' => 3, 'input_type' => 1, 'unit' => 'mtr'],
                    'front_brake_type' => ['category' => 3, 'input_type' => 1],
                    'rear_brake_type' => ['category' => 3, 'input_type' => 1],
                    'power_steering' => ['category' => 3, 'input_type' => 2],

                    //Dimension and Capacity (ID: 4)
                    'length' => ['category' => 4, 'input_type' => 1],
                    'width' => ['category' => 4, 'input_type' => 1],
                    'height' => ['category' => 4, 'input_type' => 1],
                    'seat_capacity' => ['category' => 4, 'input_type' => 1],

                    //Comfort and Convinience (ID: 5)
                    'air_condition' => ['category' => 5, 'input_type' => 2],
                    'seat_upholstery' => ['category' => 5, 'input_type' => 2],
                    'wheel_covers' => ['category' => 5, 'input_type' => 2],

                    //Interior (ID: 6)
                    'boot_space' => ['category' => 6, 'input_type' => 1, 'unit' => 'L'],
                    'power_windows' => ['category' => 6, 'input_type' => 2],
                    'tachometer' => ['category' => 6, 'input_type' => 2],
                    'electric_multi_trip_meter' => ['category' => 6, 'input_type' => 2],
                    'digital_odo_meter' => ['category' => 6, 'input_type' => 2],

                    //Exterior (ID: 7)
                    'led_tail_lights' => ['category' => 7, 'input_type' => 2],
                    'automatic_head_lamps' => ['category' => 7, 'input_type' => 2],
                    'adjustable_head_lamps' => ['category' => 7, 'input_type' => 2],

                    //Safety (ID: 8)
                    'anti_theft_alarm' => ['category' => 8, 'input_type' => 2],
                    'child_safety_locks' => ['category' => 8, 'input_type' => 2],

                    //Entertainment and Communication (ID: 9)
                    'integrated_antenna' => ['category' => 9, 'input_type' => 2],
                    'usb_and_auxiliary_input' => ['category' => 9, 'input_type' => 2],
                    'bluetooth_connectivity' => ['category' => 9, 'input_type' => 2],
                ];

                $data = [];

                foreach ($headersToFields as $header => $field) {
                    $value = $row[$header] ?? null;

                    if ($field === 'brand_id' || $field === 'body_type_id') {
                        $value = $this->mapNameToId($field, $value, $index);
                        if (!$value) Log::warning("Row $index - $field is empty.");
                        $data[$field] = $value;
                        continue;
                    }

                    if (in_array($field, [
                        'colors',
                        'professions',
                        'fuel_types',
                        'travel_type',
                        'transmission_types',
                        '360_view_camera',
                        'category'])) {
                        $value = $this->parseCsvToArray($value, $field, $index, $data);
                        if (empty($value)) Log::warning("Row $index - $field is empty.");
                    }

                    if ($field === '360_view_camera') {
                        if ($value) {
                            $value = $value;
                        } else {
                            $value = 2;
                        }
                        $data['view_camera'] = $value;
                    }

                    if ($field === 'transmission_types') {
                        $data['transmission_type'] = $value;
                    }
                    if ($field === 'fuel_types') {
                        $data['fuel_type'] = $value;
                    }

                    if ($field === 'is_upcoming') {
                        if($value){
                            $value = $this->mapToConstant($field, $value, $index);
                        }
                        else{
                            $value = 2;
                        }
                        $data['is_upcoming'] = $value;
                    }

                    if ($field === 'is_just_launched') {
                        if($value){
                            $value = $this->mapToConstant($field, $value, $index);
                        }
                        else{
                            $value = 2;
                        }
                        $data['is_just_launched'] = $value;
                    }

                    if ($field === 'status') {
                        if($value){
                            $value = $this->mapToConstant($field, $value, $index);
                        }
                        else{
                            $value = 1;
                        }
                        $data['status'] = $value;
                    }

                    if ($field === 'safety_ratings') {
                        if($value){
                            $value = $value;
                        }
                        else{
                            $value = 1;
                        }
                        $data['safety_ratings'] = $value;
                    }

                    $data[$field] = $value;
                }

                $specLabels = [
                    // 'power_and_torque' => 'Power and Torque',
                    'drivetrain' => 'Drivetrain',
                    'engine_type' => 'Engine Type',
                    'no_of_cylinders' => 'No. of Cylinders',
                    'valve_all_cylinder' => 'Valve per Cylinder',
                    'bore_and_stroke' => 'Bore & Stroke',
                    'compression_ratio' => 'Compression Ratio',
                    'super_charge' => 'Super Charger',

                    'acceleration' => 'Acceleration',
                    'top_speed' => 'Top Speed',
                    'emission_norm_compliance' => 'Emission Norm Compliance',

                    'front_suspension' => 'Front Suspension',
                    'rear_suspension' => 'Rear Suspension',
                    'steering_type' => 'Steering Type',
                    'turning_radius' => 'Turning Radius',
                    'front_brake_type' => 'Front Brake Type',
                    'rear_brake_type' => 'Rear Brake Type',
                    'power_steering' => 'Power Steering',

                    'length' => 'Length',
                    'width' => 'Width',
                    'height' => 'Height',
                    'seat_capacity' => 'Seat Capacity',

                    'air_condition' => 'Air Conditioning',
                    'seat_upholstery' => 'Seat Upholstery',
                    'wheel_covers' => 'Wheel Covers',

                    'boot_space' => 'Boot Space',
                    'power_windows' => 'Power Windows',
                    'tachometer' => 'Tachometer',
                    'electric_multi_trip_meter' => 'Electric Multi Trip Meter',
                    'digital_odo_meter' => 'Digital Odometer',

                    'led_tail_lights' => 'LED Tail Lights',
                    'automatic_head_lamps' => 'Automatic Head Lamps',
                    'adjustable_head_lamps' => 'Adjustable Head Lamps',

                    'anti_theft_alarm' => 'Anti Theft Alarm',
                    'child_safety_locks' => 'Child Safety Locks',

                    'integrated_antenna' => 'Integrated Antenna',
                    'usb_and_auxiliary_input' => 'USB and Auxiliary Input',
                    'bluetooth_connectivity' => 'Bluetooth Connectivity',
                ];

                $spec = [
                    'category' => [],
                    'specification' => [],
                    'value' => [],
                    'input_type' => [],
                ];

                foreach ($specMapping as $header => $specInfo) {
                    $value = $row[$header] ?? null;

                    if (is_null($value) || trim($value) === '') {
                        continue;
                    }

                    $label = $specLabels[$header] ?? ucwords(str_replace('_', ' ', $header));
                    $processedValue = trim($value);
                    $unit = $specInfo['unit'] ?? null;

                    if ($specInfo['input_type'] == 2) {
                        $processedValue = strtolower($processedValue);
                        if ($processedValue === 'yes') {
                            $processedValue = 1;
                        } elseif ($processedValue === 'no') {
                            $processedValue = 0;
                        }
                    }

                    $spec['category'][] = $specInfo['category'];
                    $spec['specification'][] = $label;
                    $spec['value'][] = $processedValue;
                    $spec['input_type'][] = $specInfo['input_type'];
                    $spec['unit'][] = $unit;
                }

                $image = null;
                $imageDetail = null;

                if (!empty($row['image'])) {
                    $image = $this->downloadImageAsUploadedFile($row['image']);
                    if ($image === null) {
                        $image = $this->downloadImageAsUploadedFile($row['image']);
                        Log::warning("Image 1 failed to download. Assigning dummy image for row with ID: " . ($row['id'] ?? 'unknown'));
                    }
                } else {
                    $image = $this->downloadImageAsUploadedFile('https://dummy');
                    Log::warning("Image 1 URL missing for row with ID: " . ($row['id'] ?? 'unknown'));
                }

                if (!empty($row['image_2'])) {
                    $imageDetail = $this->downloadImageAsUploadedFile($row['image_2']);
                    if ($imageDetail === null) {
                        $imageDetail = $this->downloadImageAsUploadedFile($row['image_2']);
                        Log::warning("Image 2 failed to download. Assigning dummy image for row with ID: " . ($row['id'] ?? 'unknown'));
                    }
                } else {
                    $imageDetail = $this->downloadImageAsUploadedFile('https://dummy');
                    Log::warning("Image 2 URL missing for row with ID: " . ($row['id'] ?? 'unknown'));
                }

                if ($image) {
                    $data['image'] = $image;
                }

                if ($imageDetail) {
                    $data['image_detail'] = $imageDetail;
                }

                Log::info("Saving car for row $index.");
                // $carService = new CarService($data);
                // $carId = $carService->saveCar();

                $carRefNo = $data['car_ref_no'] ?? null;

                if ($carRefNo) {
                    $existingCar = Car::where('car_ref_no', $carRefNo)->first();
                    if ($existingCar) {
                        Log::info("Row $index: Updating existing car with car_ref_no: $carRefNo");
                        $carService = new CarService($data);
                        $carService->car = $existingCar;
                    } else {
                        Log::info("Row $index: car_ref_no provided but no match found. Will create new car.");
                        $carService = new CarService($data);
                    }
                } else {
                    Log::info("Row $index: No car_ref_no provided. Will create new car.");
                    $carService = new CarService($data);
                }

                $carId = $carService->saveCar();

                if ($carId) {
                    Log::info("Car saved successfully with ID: $carId");
                    $data['car_id'] = $carId;

                    $carVersion = $this->saveCarVersion($carId, $data);
                    Log::info("Car version saved for car ID: $carId");

                    $carVersionId = $carVersion->id;

                    $this->saveCategoryAttributes($carId, $carVersionId, $spec);

                    DB::commit();
                    $successfulImports++;
                } else {
                    Log::error("Failed to save car for row $index.");
                    $failedImports++;
                }

            } catch (QueryException $qe) {
                DB::rollBack();
                $msg = $qe->getMessage();
                $bindings = $qe->getBindings();

                if (str_contains($msg, 'Data truncated')) {
                    preg_match("/column '(.+?)'/", $msg, $m);
                    $col = $m[1] ?? 'unknown';
                    $value = $data[$col] ?? 'N/A';
                    $userMsg = "Row $index: Value '$value' too large or wrong format for '$col'.";
                } elseif (preg_match("/Incorrect (integer|decimal) value: '(.+?)' for column '(.+?)'/", $msg, $m)) {
                    [$all, $type, $bad, $col] = $m;
                    $userMsg = "Row $index: '$bad' is not a valid $type for '$col'.";
                } elseif (preg_match("/Column '(.+?)' cannot be null/", $msg, $m)) {
                    $col = $m[1] ?? 'unknown';
                    $enteredValue = $data[$col] ?? ($row[$col] ?? '');
                    $userMsg = "Row $index: Required field '{$col}' is missing or invalid. Entered value: '" . (is_null($enteredValue) ? '' : $enteredValue) . "'";
                } elseif (isset($data['transmission_type']) && empty($data['transmission_type'])) {
                    $originalVal = trim($row['transmission_types'] ?? '');
                    if (!empty($originalVal)) {
                        $userMsg = "Row $index - Invalid transmission_types value: '{$originalVal}'.";
                    } else {
                        $userMsg = "Row $index: General database error. Please check your input.";
                    }
                } else {
                    $userMsg = "Row $index: General database error. Please check your input.";
                }

                Log::error("Row $index DATABASE ERROR: $msg | SQL: " . $qe->getSql());
                Log::error("Bindings: " . json_encode($bindings));

                $rowErrors[] = $userMsg;
                $failedImports++;
            } catch (Exception $e) {
                Log::error("Exception while saving car for row $index: " . $e->getMessage());
                $failedImports++;
                $rowErrors[] = "Row $index: " . $e->getMessage();
            }
        }

        if ($successfulImports === 0) {
            if ($failedImports === 0 && $skippedRows === count($rows)) {
                $rowErrors[] = 'No rows were valid or all were empty.';
            } elseif ($failedImports === 0) {
                $failedImports = count($rows) - $skippedRows;
                $rowErrors[] = 'All rows failed validation.';
            }

            $message = "Car bulk import failed: All rows had errors or were skipped.";
            Log::error($message);
        } else {
            $message = "Car bulk import completed. Success: $successfulImports, Failed: $failedImports.";
            Log::info($message);
        }

        $cacheKey = "car_import_result_{$this->userId}";

        $existing = Cache::get($cacheKey, [
            'successful_imports' => 0,
            'failed_imports' => 0,
            'skipped_rows' => 0,
            'errors' => [],
        ]);

        $merged = [
            'status' => $successfulImports === 0 ? 'error' : 'success',
            'successful_imports' => $existing['successful_imports'] + $successfulImports,
            'failed_imports' => $existing['failed_imports'] + $failedImports,
            'skipped_rows' => ($existing['skipped_rows'] ?? 0) + $skippedRows,
            'errors' => array_merge($existing['errors'], $rowErrors),
        ];

        Cache::put($cacheKey, $merged, now()->addMinutes(3));
    }

    private function downloadImageAsUploadedFile($url, $name = null)
    {
        $useDummy = false;

        $isGoogleDrive = preg_match('#drive\.google\.com\/file\/d\/([^\/]+)#', $url, $matches);

        if ($isGoogleDrive) {
            $fileId = $matches[1];
            $url = "https://drive.google.com/uc?export=download&id={$fileId}";
            Log::info("Converted Google Drive link to direct download: {$url}");
        }

        if (!$isGoogleDrive) {
            $headers = @get_headers($url, 1);

            if (!$headers || strpos($headers[0], '200') === false) {
                $url = null;
                Log::warning("URL did not return 200 OK.");
            }

            if (!empty($headers)) {
                $normalizedHeaders = array_change_key_case($headers, CASE_LOWER);
            }

            if (!isset($normalizedHeaders['content-type']) || strpos($normalizedHeaders['content-type'], 'image/') === false) {
                $url = null;
                Log::warning('Not a valid URL. Processing with dummy image.');
            }
        }

        if (empty($url)) {
            $useDummy = true;
        } else {
            try {
                $imageContent = @file_get_contents($url);

                if ($imageContent === false) {
                    throw new \Exception("Failed to download image from URL.");
                }

                if (!$name) {
                    $name = 'downloaded_image_' . time() . rand(1000, 9999) . '.jpg';
                }

                $path = 'car-images/' . $name;
                Storage::put($path, $imageContent);

                $fullPath = storage_path('app/' . $path);

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $fullPath) ?: 'image/jpeg';
                finfo_close($finfo);

                return new UploadedFile(
                    $fullPath,
                    $name,
                    $mimeType,
                    null,
                    true
                );
            } catch (\Exception $e) {
                Log::error("Failed to download image from URL: {$url} - " . $e->getMessage());
                $useDummy = true;
            }
        }

        if ($useDummy) {
            $dummyPath = public_path('images/Vector1.jpg');

            if (!file_exists($dummyPath)) {
                Log::error("Dummy image not found at {$dummyPath}");
                throw new \Exception("Dummy image file is missing.");
            }

            $dummyName = 'dummy_' . time() . rand(1000, 9999) . '.jpg';

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $dummyPath) ?: 'image/jpeg';
            finfo_close($finfo);

            return new UploadedFile(
                $dummyPath,
                $dummyName,
                $mimeType,
                null,
                true
            );
        }
    }

    public function saveCategoryAttributes($carId, $versionId, $data)
    {
        if (empty($data['category'])) {
            Log::warning('No categories provided for car ID ' . $carId->id);
            return;
        }

        CarAdditonalSpecifications::where('car_version_id', $versionId)->delete();
        Log::info("Deleted old specifications for car version ID: $versionId");

        foreach (['specification', 'value', 'unit'] as $field) {
            if (!empty($data[$field]) && is_string($data[$field])) {
                $decoded = json_decode($data[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$field] = $decoded;
                } else {
                    Log::warning("Failed to decode JSON for field '$field': " . json_last_error_msg());
                    $data[$field] = [];
                }
            }
        }

        foreach ($data['category'] as $index => $categoryId) {
            if (empty($categoryId)) {
                continue;
            }

            Log::debug("saveCategoryAttributes - data at index $index for car ID {$carId->id}", [
                'category' => $categoryId,
                'input_type' => $data['input_type'][$index] ?? null,
                'specification' => $data['specification'][$index] ?? null,
                'value' => $data['value'][$index] ?? null,
                'unit' => $data['unit'][$index] ?? null,
            ]);

            $categoryAttribute = new CarAdditonalSpecifications();

            $categoryAttribute->car_id = $carId->id;
            $categoryAttribute->car_version_id = $versionId;
            $categoryAttribute->category_id = (string)$categoryId;

            $categoryAttribute->input_type = isset($data['input_type'][$index])
                ? (is_array($data['input_type'][$index]) ? json_encode($data['input_type'][$index]) : (string)$data['input_type'][$index])
                : null;

            $categoryAttribute->specification = isset($data['specification'][$index])
                ? (is_array($data['specification'][$index]) ? json_encode($data['specification'][$index]) : (string)$data['specification'][$index])
                : null;

            $categoryAttribute->value = isset($data['value'][$index])
                ? (is_array($data['value'][$index]) ? json_encode($data['value'][$index]) : (string)$data['value'][$index])
                : null;

            $categoryAttribute->unit = isset($data['unit'][$index])
                ? (is_array($data['unit'][$index]) ? json_encode($data['unit'][$index]) : (string)$data['unit'][$index])
                : null;

            $categoryAttribute->is_key_feature = 0;
            $categoryAttribute->is_key_spec = 0;

            try {
                $categoryAttribute->save();
            } catch (\Exception $e) {
                Log::error("Error saving category index $index for car ID {$carId->id}: " . $e->getMessage());
            }
        }
    }

    private function mapNameToId($field, $value, $rowIndex)
    {
        if (empty($value)) {
            Log::warning("Row $rowIndex - $field is empty.");
            return null;
        }

        $query = null;

        switch ($field) {
            case 'brand_id':
                $query = Brand::where('name', $value)->where('status', 1);
                break;
            case 'body_type_id':
                $query = BodyType::where('name', $value)->where('status', 1);
                break;
            default:
                Log::warning("Row $rowIndex - Unsupported field for name to ID mapping: $field");
                return null;
        }

        $record = $query->first();

        if ($record) {
            Log::info("Row $rowIndex - Mapped $field name '$value' to ID {$record->id}");
            return $record->id;
        } else {
            Log::warning("Row $rowIndex - No active record found for $field name: '$value'");
            return null;
        }
    }

    private function mapToConstant($field, $value, $rowIndex)
    {
        if (empty($value)) {
            Log::warning("Row $rowIndex - $field is empty.");
            return null;
        }

        $value = strtolower(trim($value));

        switch ($field) {
            case 'is_upcoming':
                if ($value === 'yes') {
                    return Car::UPCOMING;
                } elseif ($value === 'no') {
                    return Car::LAUNCHED;
                }
                break;
            case 'is_just_launched':
                if ($value === 'yes') {
                    return Car::JUST_LAUNCHED;
                } elseif ($value === 'no') {
                    return Car::NOT_JUST_LAUNCHED;
                }
                break;
            case 'status':
                if ($value === 'active') {
                    return Car::STATUS_ACTIVE;
                } elseif ($value === 'inactive') {
                    return Car::STATUS_INACTIVE;
                }
                break;
            default:
                Log::warning("Row $rowIndex - Unsupported field for yes/no mapping: $field");
                return null;
        }

        Log::warning("Row $rowIndex - Invalid value for $field: '$value'. Expected 'Yes' or 'No'.");
        return null;
    }

    private function parseCsvToArray($value, $field, $rowIndex, $rowData = [])
    {
        if (empty($value)) {
            Log::warning("Row $rowIndex - $field is empty.");
            return [];
        }

        if (is_string($value) && str_starts_with(trim($value), '[') && str_ends_with(trim($value), ']')) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $filtered = array_filter(array_map('trim', $decoded), fn($item) => $item !== '');
            } else {
                Log::warning("Row $rowIndex - Failed to decode JSON array for $field: '$value'");
                return [];
            }
        } elseif (is_array($value)) {
            $filtered = array_filter(array_map('trim', $value), fn($item) => $item !== '');
        } else {
            $items = array_map('trim', explode(',', (string) $value));
            $filtered = array_filter($items, fn($item) => $item !== '');
        }

        if ($field === 'colors') {
            return $this->mapColorsFromBrand($filtered, $rowData['brand_id'] ?? null, $rowIndex);
        }

        $result = [];

        if (isset($this->configFieldMappings[$field])) {
            $configKey = $this->configFieldMappings[$field];
            $configArray = config("params.$configKey");

            if (count($filtered) === 1 && strtolower($filtered[0]) === 'all') {
                $allKeys = array_map('strval', array_keys($configArray));
                Log::info("Row $rowIndex - '$field' set to 'all', returning all options: " . json_encode($allKeys));
                return $allKeys;
            }

            foreach ($filtered as $item) {
                $found = false;
                foreach ($configArray as $key => $name) {
                    if (strcasecmp($name, $item) === 0) {
                        $result[] = (string) $key;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    Log::warning("Row $rowIndex - Invalid $field value: '$item'. No matching config key found.");
                }
            }

            Log::info("Row $rowIndex - Mapped $field values: " . json_encode($result));
            return $result;
        }

        Log::info("Row $rowIndex - No mapping config found for $field, returning raw values.");
        return array_map('strval', array_values($filtered));
    }

    private function mapColorsFromBrand(array $colors, $brandId, $rowIndex)
    {
        if (!$brandId) {
            $message = "Row $rowIndex - Cannot map colors without brand_id.";
            Log::warning($message);
            throw new Exception($message);
        }

        $result = [];

        $brandName = Brand::where('id', $brandId)->value('name') ?? 'Unknown';

        foreach ($colors as $colorName) {
            $record = BrandColorMapping::where('name', $colorName)
                ->where('brand_id', $brandId)
                ->active()
                ->first();

            if ($record) {
                $result[] = (string) $record->id;
                Log::info("Row $rowIndex - Mapped color '$colorName' to ID {$record->id} for brand_id $brandId");
            } else {
                Log::warning("Row $rowIndex - No active color mapping found for color '$colorName' with brand_id $brandId");
            }
        }

        if (empty($result)) {
            $message = "Row $rowIndex - No valid colors found for brand $brandName. Import aborted.";
            Log::error($message);
            throw new Exception($message);
        }

        return $result;
    }

    public function saveCarVersion($car, $data)
    {
        $carId = $data['car_id'] ?? null;
        $carSpec = CarVersion::CAR_SPECIFICATION;

        $version = CarVersion::where('car_id', $car->id)
            ->where('varient_name', $data['varient_name'] ?? $car->model_name)
            ->first();

        if (!$version) {
            $version = new CarVersion();
            $version->car_id = $car->id;
            $version->is_car_spec = $carSpec;
        }

        $version->varient_name = $data['varient_name'] ?? $car->model_name;
        $version->ex_showroom_price = $data['ex_showroom_price'];
        $version->on_road_price = $data['on_road_price'];
        $version->finance_available = $data['finance_available'];
        $version->insurance = $data['insurance'];
        $version->service_charge = $data['service_charge'];
        $version->engine_capacity = $data['engine_capacity'];
        $version->power = $data['power'];
        $version->torque = $data['torque'];

        $version->transmission_type = !empty($data['transmission_type'])
            ? (is_array($data['transmission_type']) ? $data['transmission_type'][0] : $data['transmission_type'])
            : null;
        $version->fuel_type = !empty($data['fuel_type'])
            ? (is_array($data['fuel_type']) ? $data['fuel_type'][0] : $data['fuel_type'])
            : null;
        $version->travel_type = !empty($data['travel_type'])
            ? (is_array($data['travel_type']) ? $data['travel_type'][0] : $data['travel_type'])
            : null;
        $version->colours = !empty($data['colors'])
            ? (is_array($data['colors']) ? $data['colors'][0] : $data['colors'])
            : json_encode([]);
        $version->view_camera = !empty($data['360_view_camera'])
            ? (is_array($data['360_view_camera']) ? $data['360_view_camera'][0] : $data['360_view_camera'])
            : null;

        if (!empty($data['mileage'])) {
            $mileage = $data['mileage'];
            if (strpos($mileage, '-') !== false) {
                [$min, $max] = array_map('floatval', explode('-', $mileage));
                $version->mileage = ($min + $max) / 2;
                $version->mileage_min = $min;
                $version->mileage_max = $max;
            } else {
                $version->mileage = floatval($mileage);
                $version->mileage_min = null;
                $version->mileage_max = null;
            }
        }

        $version->fuel_tank_capacity = $data['fuel_tank_capacity'];
        $version->seat_capacity = $data['seat_capacity'];
        $version->safety_ratings = $data['safety_ratings'];
        $version->no_of_airbags = $data['no_of_airbags'];
        $version->body_type = $data['body_type_id'];
        $version->status = $data['status'];
        $version->save();

        return $version;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
