<?php

namespace App\Exports;

use App\Models\Car;
use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;;

class CarExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings, ShouldAutoSize, ShouldQueue
{

    use Exportable;
    protected $startDate;
    protected $endDate;
    protected $model;
    protected $brand;
    protected $upcoming;
    protected $launch;
    protected $status;

    protected $index = 0;
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    public function forModel($model)
    {
        $this->model = $model;
        return $this;
    }
    public function forBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }
    public function forUpcoming($upcoming)
    {
        $this->upcoming = $upcoming;
        return $this;
    }
    public function forJustLaunch($launch)
    {
        $this->launch = $launch;
        return $this;
    }
    public function forStatus($status)
    {
        $this->status = $status;
        return $this;
    }


    public function query()
    {
        $endDate = Carbon::parse($this->endDate)->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
        $startDate = Carbon::parse($this->startDate)->format('Y-m-d H:i');

        return  $query = Car::query()
            ->select([
                'b.id as brand_id',
                'b.name as brand_name',
                'cars.id',
                'cars.model_name as model_name',
                'cars.sort_order as sort_order',
                'cars.status as status',
                'cars.is_upcoming as is_upcoming',
                'cars.is_just_launched as is_just_launched',
                DB::raw('COALESCE((SELECT COUNT(*) FROM car_versions WHERE car_versions.car_id = cars.id AND car_versions.status = 1), 0) as version_count'),
                DB::raw('COALESCE((SELECT COUNT(*) FROM test_drives WHERE test_drives.car_id = cars.id AND test_drives.status = 3), 0) as test_ride_count'),
                DB::raw('COALESCE((SELECT COUNT(*) FROM offer_requests WHERE offer_requests.car_id = cars.id AND offer_requests.status = 2 AND offer_requests.type = 1), 0) as offer_request_count'),
                DB::raw('COALESCE((SELECT COUNT(*) FROM offer_requests WHERE offer_requests.car_id = cars.id AND offer_requests.status = 2 AND offer_requests.type = 2), 0) as onroad_price_count'),
                DB::raw('COALESCE((SELECT COUNT(*) FROM offer_requests WHERE offer_requests.car_id = cars.id AND offer_requests.status = 2 AND offer_requests.type = 3), 0) as emi_request_count'),
            ])
            ->leftJoin('brands as b', 'b.id', '=', 'cars.brand_id') // Ensure correct join
            ->groupBy('cars.id', 'b.id', 'b.name', 'cars.model_name', 'cars.sort_order', 'cars.status') // Group by necessary columns
            ->orderBy('cars.id', 'DESC') // Sort by car ID in descending order
            ->when($this->model, function ($query, $model) {
                $query->where('cars.id', $this->model);
            })
            ->when($this->brand, function ($query, $brand) {
                $query->where('cars.brand_id', $this->brand);
            })
            ->when($this->upcoming, function ($query, $value) {
                $query->where('cars.is_upcoming', $value);
            })
            ->when($this->launch, function ($query, $value) {
                $query->where('cars.is_just_launched', $value);
            })
            ->when($this->status, function ($query, $value) {
                $query->where('cars.status', $value);
            })

            ->when($this->startDate, function ($query) use ($startDate, $endDate) {
                $query->where('cars.created_at', '>=', $startDate)
                    ->where('cars.created_at', '<=', $endDate);
            })
            ->orderBy('cars.created_at', 'desc');
    }
    public function headings(): array
    {
        return [
            'Model Name',
            'Brand',
            'Sort Order',
            'Version Count',
            'Is Upcoming',
            'Is Just Launched',
            'Test Ride Count',
            'Offer Request Count',
            'On Road Price Request Count',
            'Emi Request Count',
            'Status',
        ];
    }

    public function columnFormats(): array
    {
        return [
            //    'G' => NumberFormat::FORMAT_NUMBER,
            //    'H' => NumberFormat::FORMAT_NUMBER,
            //    'I' => NumberFormat::FORMAT_NUMBER,
            //    'J' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * @var $user
     */
    public function map($car): array
    {
        return [
            $car->model_name,
            $car->brand->name,
            $car->sort_order,
            $car->version_count,
            $car->is_upcoming == Car::UPCOMING ? 'Yes' : 'No',
            $car->is_just_launched == Car::JUST_LAUNCHED ? 'Yes' : 'No',
            $car->test_ride_count,
            $car->offer_request_count,
            $car->onroad_price_count,
            $car->emi_request_count,
            config('params.car.status')[$car->status],

        ];
    }
}
