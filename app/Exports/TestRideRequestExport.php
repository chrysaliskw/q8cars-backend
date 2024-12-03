<?php

namespace App\Exports;

use App\Models\TestDrive;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestRideRequestExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $status;
    protected $brand;
    protected $model;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function forBrand($brandid)
    {
        $this->brand = $brandid;
        return $this;
    }

    public function forModel($carid)
    {
        $this->model = $carid;
        return $this;
    }

    public function forStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function query()
    {
        $endDate = Carbon::parse($this->endDate)->endOfDay()->format('Y-m-d H:i:s');
        $startDate = Carbon::parse($this->startDate)->startOfDay()->format('Y-m-d H:i:s');

        return TestDrive::query()
            ->leftJoin('users as u', 'u.id', '=', 'test_drives.user_id')
            ->leftJoin('cars as c', 'c.id', '=', 'test_drives.car_id')
            ->leftJoin('brands', 'brands.id', '=', 'c.brand_id')
            ->when($this->brand, function ($query) {
                $query->where('c.brand_id', $this->brand);
            })
            ->when($this->model, function ($query) {
                $query->where('c.id', $this->model);
            })
            ->when($this->status, function ($query) {
                $query->where('test_drives.status', $this->status);
            })

            ->whereBetween('test_drives.created_at', [$startDate, $endDate])
            ->select([
                'test_drives.*',
                'u.phone_code as user_phone_code',
                'u.mobile as user_mobile',
                'c.model_name as car_model',
                'brands.name as brand_name',
            ])
            ->where('test_drives.status', '<>', 5)
            ->orderBy('test_drives.id', 'desc');
    }

    public function headings(): array
    {
        return [
            'Car Model',
            'Car Brand',
            'Name',
            'Mobile',
            'Status',
            'Created At',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function map($row): array
    {
        return [
            $row->car_model, // Aliased column from query
            $row->brand_name, // Aliased column from query
            "{$row->first_name} {$row->last_name}", // Concatenated name
            "{$row->user_phone_code}{$row->user_mobile}", // Phone with code
            config('params.test_drive.status')[$row->status] ?? 'Unknown', // Mapped status
            dateFormat($row->created_at), // Formatted date
        ];
    }

    public function returnExportedData()
    {
        return $this->query()->get();
    }
}
