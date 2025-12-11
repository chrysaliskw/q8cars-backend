<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const TR_AUTOMATIC = 1;
    const TR_MANUAL = 2;
    const TR_CLUCHLESS_MANUAL = 3;
    const TR_AUTOMATIC_TC = 4;

    const JUST_LAUNCHED = 1;
    const NOT_JUST_LAUNCHED = 2;

    const FILE_DIR = 'cars';

    const MAX_NUM_IMAGES = 20;
    const UPCOMING = 1;
    const LAUNCHED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function offer()
    {
        return $this->belongsTo(Offer::class, 'id', 'car_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function carVersions()
    {
        return $this->hasMany(CarVersion::class);
    }
    public function baseVarient()
    {
        return $this->hasOne(CarVersion::class)->where('is_base_varient', CarVersion::BASE_VARIENT);
    }
    public function carSpec()
    {
        return $this->hasOne(CarVersion::class)->where('is_car_spec', CarVersion::CAR_SPECIFICATION);
    }
    public function manualVersion()
    {
        return $this->hasOne(CarVersion::class)->where('transmission_type', Car::TR_MANUAL)->first();
    }
    public function automaticVersion()
    {
        return $this->hasOne(CarVersion::class)->where('transmission_type', Car::TR_AUTOMATIC)->first();
    }
    public function news()
    {
        return $this->hasMany(News::class);
    }
    public function carImages()
    {
        return $this->hasMany(CarImage::class);
    }
    public function carPhotos()
    {
        return $this->hasMany(CarImage::class)->where('type', CarImage::TYPE_IMAGE);
    }
    public function carPhoto()
    {
        return $this->hasMany(CarImage::class)->where('type', CarImage::TYPE_IMAGE)->whereNull('color');
    }
    public function carVideos()
    {
        return $this->hasMany(CarImage::class)->where('type', CarImage::TYPE_VIDEO);
    }

    public function carComparisonLists()
    {
        return $this->hasMany(CarComparisonList::class);
    }
    public function varients()
    {
        return $this->hasMany(CarVersion::class);
    }


    public function getVersionName($version_id)
    {
        return CarVersion::find($version_id)->version_name;
    }


    /*
    |--------------------------------------------------------------------------
    | Local Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
    public function scopeLaunched($query)
    {
        return $query->where('is_upcoming', self::LAUNCHED);
    }
    public function scopeUpcoming($query)
    {
        return $query->where('is_upcoming', self::UPCOMING);
    }

    protected static function booted()
    {
        static::creating(function ($car) {
            if (!$car->car_ref_no) {
                // $brand = Brand::find($car->brand_id);
                // $brandPart = $brand ? strtoupper(substr($brand->name, 0, 3)) : 'CAR';

                do {
                    $randomNumber = mt_rand(100000, 999999);
                    $carRefNo = 'NS' . '-' . $randomNumber;
                } while (Car::where('car_ref_no', $carRefNo)->exists());

                $car->car_ref_no = $carRefNo;
            }
            $car->slug = static::generateUniqueSlug($car->model_name, $car->car_ref_no);
        });
    }

    protected static function generateUniqueSlug($modelName, $refNo)
    {
        $slug = Str::slug($modelName);
        $baseSlug = $slug . '-' . $refNo;

        $count = static::where('slug', 'like', "$slug%")->count();

        if ($count > 0) {
            return $baseSlug . '-' . ($count + 1);
        }

        return $baseSlug;
    }

    public function getAttribute($key)
    {
        $priceFields = [
            'ex_showroom_price',
            'on_road_price',
            'finance_available',
            'service_charge',
            'insurance',
        ];

        $value = parent::getAttribute($key);

        if (in_array($key, $priceFields) && !is_null($value)) {
            return (int) round($value);
        }

        return $value;
    }

}
