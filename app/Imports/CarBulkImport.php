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

    private $configFieldMappings = [
        'fuel_types' => 'car.fuel_type',
        'transmission_types' => 'car.transmission_type',
        'view_camera' => 'car.view-camera',
        'travel_type' => 'car.travel_type',
        'professions' => 'professions',
        'status' => 'car.status',
        'category' => 'car.specification-section',
    ];

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        Log::info("Starting car bulk import.");

        $successfulImports = 0;
        $failedImports = 0;

        foreach ($rows as $index => $row) {
            try {
                $row = $row instanceof Collection ? $row->toArray() : $row;

                if (collect($row)->filter(fn($value) => !is_null($value) && trim($value) !== '')->isEmpty()) {
                    Log::info("Skipping empty row at index $index.");
                    continue;
                }

                Log::info("Processing row $index: " . json_encode($row));

                $headersToFields = [
                    'brand' => 'brand_id',
                    'model' => 'model_name',
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
                    'seat_capacity' => 'seat_capacity',
                    'safety_ratings' => 'safety_ratings',
                    'engine_capacity' => 'engine_capacity',
                    'power' => 'power',
                    'torque' => 'torque',
                    'mileage' => 'mileage',
                    'fuel_types' => 'fuel_types',
                    'transmission_types' => 'transmission_types',
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
                    'body_type' => 'body_type_id',
                    'view_camera' => 'view_camera',
                    'noof_airbags' => 'no_of_airbags',
                    'category' => 'category',
                    'input_type' => 'input_type',
                    'specification' => 'specification',
                    'value' => 'value',
                    'unit' => 'units',
                    'is_key_feature' => 'is_key_feature',
                    'is_key_spec' => 'is_key_spec',
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

                    if ($field === 'input_type') {
                        $value = $this->mapInputType($value, $index);
                    }

                    // if (isset($data['is_key_feature']) || isset($data['is_key_spec'])) {
                    //     $value = $this->mapKeyFeatureAndSpec($data['is_key_feature'] ?? null, $data['is_key_spec'] ?? null, $index);
                    //     $data['is_key_feature'] = $value['is_key_feature'];
                    //     $data['is_key_spec'] = $value['is_key_spec'];
                    // }

                    if ($field === 'value') {
                        $inputType = $data['input_type'][$index] ?? null; // make sure you get the input_type for this index
                        if (!$inputType) {
                            Log::warning("Row $index - input_type not set before value field.");
                        }
                        $value = $this->mapValueBasedOnInputType($value, $inputType, $index);
                        $data[$field] = $value;
                        continue;
                    }

                    if (in_array($field, [
                        // 'colors',
                        'professions',
                        'fuel_types',
                        'travel_type',
                        'transmission_types',
                        'view_camera',
                        'category'])) {
                        $value = $this->parseCsvToArray($value, $field, $index, $data);
                        if (empty($value)) Log::warning("Row $index - $field is empty.");
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

                    // if ($field === 'is_key_feature') {
                    //     $isKeyFeatureValue = $value;
                    //     continue;
                    // }

                    // if ($field === 'is_key_spec') {
                    //     $isKeySpecValue = $value;
                    //     continue;
                    // }

                    $data[$field] = $value;
                }

                // $keyFeatureSpec = $this->mapKeyFeatureAndSpec($isKeyFeatureValue, $isKeySpecValue, $index);
                // $data['is_key_feature'] = $keyFeatureSpec['is_key_feature'];
                // $data['is_key_spec'] = $keyFeatureSpec['is_key_spec'];

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
                $carService = new CarService($data);
                $carId = $carService->saveCar();

                if ($carId) {
                    Log::info("Car saved successfully with ID: $carId");
                    $data['car_id'] = $carId;

                    $carVersion = $this->saveCarVersion($carId, $data);
                    Log::info("Car version saved for car ID: $carId");

                    $carVersionId = $carVersion->id;

                    $categories = $data['category'] ?? [];
                    $inputTypes = $data['input_type'] ?? [];
                    $specifications = $data['specification'] ?? [];
                    $values = $data['value'] ?? [];
                    $units = $data['units'] ?? [];

                    $this->saveCategoryAttributes($carId, $carVersionId, [
                        'category' => $categories,
                        'input_type' => $inputTypes,
                        'specification' => $specifications,
                        'value' => $values,
                        'unit' => $units,
                    ]);

                    $successfulImports++;
                } else {
                    Log::error("Failed to save car for row $index.");
                    $failedImports++;
                }

            } catch (Exception $e) {
                Log::error("Exception while saving car for row $index: " . $e->getMessage());
                $failedImports++;
            }
        }

        if ($successfulImports === 0) {
            DB::rollBack();
            $message = "Car bulk import failed: All rows had errors.";
            Log::error($message);
        } else {
            DB::commit();
            $message = "Car bulk import completed. Successfully imported $successfulImports rows. Failed rows: $failedImports.";
            Log::info($message);
        }

        $this->result = [
            'status' => $successfulImports === 0 ? 'error' : 'success',
            'successful_imports' => $successfulImports,
            'failed_imports' => $failedImports,
            'message' => $message
        ];
    }

    private function downloadImageAsUploadedFile($url, $name = null)
    {
        $useDummy = false;

        $headers = @get_headers($url, 1);

        if (!$headers || strpos($headers[0], '200') === false) {
            $url = null;
            Log::warning("URL did not return 200 OK.");
        }

        if(!empty($headers)){
            $normalizedHeaders = array_change_key_case($headers, CASE_LOWER);
        }

        if (!isset($normalizedHeaders['content-type']) || strpos($normalizedHeaders['content-type'], 'image/') === false) {
            $url = null;
            Log::warning('Not a valid URL. Processing with dummy image.');
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

    private function mapValueBasedOnInputType($value, $inputType, $rowIndex)
    {
        if (is_array($value)) {
            $mappedValues = [];
            foreach ($value as $i => $singleValue) {
                $mapped = $this->mapSingleValueBasedOnInputType($singleValue, $inputType, $rowIndex, $i);
                $mappedValues[] = $mapped;
            }
            return $mappedValues;
        }

        return $this->mapSingleValueBasedOnInputType($value, $inputType, $rowIndex);
    }

    private function mapSingleValueBasedOnInputType($value, $inputType, $rowIndex, $subIndex = null)
    {
        $valueStr = strtolower(trim((string) $value));
        $location = $subIndex !== null ? "Row $rowIndex, SubIndex $subIndex" : "Row $rowIndex";

        if (!in_array($inputType, [CarAdditonalSpecifications::TYPE_TEXT, CarAdditonalSpecifications::TYPE_BOOLEAN])) {
            Log::error("$location - Invalid 'input_type': '$inputType'. Must be TYPE_TEXT or TYPE_BOOLEAN.");
            return null;
        }

        if ($inputType == CarAdditonalSpecifications::TYPE_TEXT) {
            if ($valueStr === 'yes' || $valueStr === 'no') {
                Log::error("$location - 'value' cannot be 'Yes' or 'No' when 'input_type' is TEXT.");
                return null;
            }
            if (!is_string($value) || $valueStr === '') {
                Log::error("$location - 'value' must be a non-empty string when 'input_type' is TEXT.");
                return null;
            }

            return (string) $value;
        }

        if ($inputType == CarAdditonalSpecifications::TYPE_BOOLEAN) {
            if (!in_array($valueStr, ['yes', 'no'])) {
                Log::error("$location - 'value' must be 'Yes' or 'No' when 'input_type' is BOOLEAN.");
                return null;
            }

            return $valueStr === 'yes' ? 1 : 2;
        }

        Log::error("$location - Unhandled 'input_type' ($inputType).");
        return null;
    }

    private function mapInputType($value, $rowIndex)
    {
         $map = [
            'Text' => CarAdditonalSpecifications::TYPE_TEXT,
            'Boolean' => CarAdditonalSpecifications::TYPE_BOOLEAN,
        ];

        $result = [];

        if (is_string($value) && str_starts_with(trim($value), '[') && str_ends_with(trim($value), ']')) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $inputTypes = array_filter(array_map('trim', $decoded), fn($item) => $item !== '');
            } else {
                Log::warning("Row $rowIndex - Failed to decode JSON input_type array: '$value'");
                return [];
            }
        } elseif (is_array($value)) {
            $inputTypes = array_filter(array_map('trim', $value), fn($item) => $item !== '');
        } else {
            $inputTypes = array_map('trim', explode(',', (string) $value));
        }

        foreach ($inputTypes as $inputType) {
            $found = false;
            foreach ($map as $label => $constValue) {
                if (strcasecmp($inputType, $label) === 0) {
                    $result[] = $constValue;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                Log::warning("Row $rowIndex - Invalid input_type value: '$inputType'.");
            }
        }

        Log::info("Row $rowIndex - Mapped input_type values: " . json_encode($result));
        return $result;
    }

    public function saveCategoryAttributes($carId, $versionId, $data)
    {
        if (empty($data['category'])) {
            Log::warning('No categories provided for car ID ' . $carId->id);
            return;
        }

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

    private function mapKeyFeatureAndSpec($featureValue, $specValue, $rowIndex)
    {
        $featureValue = strtolower(trim((string) $featureValue));
        $specValue = strtolower(trim((string) $specValue));

        $isFeatureYes = $featureValue === 'yes';
        $isSpecYes = $specValue === 'yes';

        if ($isFeatureYes && $isSpecYes) {
            Log::error("Row $rowIndex - Both 'is_key_feature' and 'is_key_spec' are 'Yes'. Only one can be 'Yes'. Setting both to 0.");
            return ['is_key_feature' => 0, 'is_key_spec' => 0];
        }

        if ($isFeatureYes) {
            return ['is_key_feature' => CarAdditonalSpecifications::IS_KEY_FEATURE, 'is_key_spec' => 0];
        }

        if ($isSpecYes) {
            return ['is_key_feature' => 0, 'is_key_spec' => CarAdditonalSpecifications::IS_KEY_SPEC];
        }

        return ['is_key_feature' => 0, 'is_key_spec' => 0];
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

        $name = $data['varient_name'] ?? $car->model_name;
        $carSpec = CarVersion::CAR_SPECIFICATION;

        $version = new CarVersion();
        $version->car_id = $carId->id;
        $version->is_car_spec = $carSpec;
        $version->varient_name = $name;
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
        $version->view_camera = is_array($data['view_camera']) ? $data['view_camera'][0] : $data['view_camera'];

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
