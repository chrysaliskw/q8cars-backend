<?php

namespace App\Exports;

use App\Models\OfferRequest;
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

class OfferRequestExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings, ShouldAutoSize, ShouldQueue
{

    use Exportable;
    protected $startDate;
    protected $endDate;
    protected $model;
    protected $type;
    protected $mobile;
    protected $launch;
    protected $status;

    protected $index = 0;
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    public function forModel($carid)
    {
        $this->model = $carid;
        return $this;
    }
    public function forMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }
    public function forType($type)
    {
        $this->type = $type;
        return $this;
    }
    public function forStatus($status)
    {
        $this->status = $status;
        return $this;
    }


    public function query()
    {
        // dd($this->startDate, $this->endDate);
        // $endDate = Carbon::parse(request()->query('end_date'))->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
        // $startDate = Carbon::parse(request()->query('start_date'))->format('Y-m-d H:i');
        $endDate = Carbon::parse($this->endDate)->endOfDay()->format('Y-m-d H:i:s');
        $startDate = Carbon::parse($this->startDate)->startOfDay()->format('Y-m-d H:i:s');

        return  OfferRequest::query()
            ->leftJoin('users as u', 'u.id', '=', 'offer_requests.user_id')
            ->leftJoin('cars as c', 'c.id', '=', 'offer_requests.car_id')
            // ->leftJoin('offers', 'offers.id', '=', 'offer_requests.offer_id')
            ->orderBy('offer_requests.id', 'Desc')
            ->when($this->model, function ($query,) {
                $query->where('c.id', $this->model);
            })
            ->when($this->mobile, function ($query, $model) {
                $query->where('u.mobile', 'like', '%' . $model . '%');
            })
            ->when($this->type, function ($query, $model) {
                $query->where('offer_requests.type', 'like', $model);
            })
            ->when($this->status, function ($query, $value) {
                $query->where('offer_requests.status', $value);
            })
            ->when($this->startDate && $this->endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('offer_requests.created_at', [$startDate, $endDate]);
            })
            // ->when(request()->query('start_date'), function ($q) use ($startDate, $endDate) {
            //     $q->where('offer_requests.created_at', '>=', $startDate)
            //         ->where('offer_requests.created_at', '<=', $endDate);
            // })
            // ->whereBetween('offer_requests.updated_at', [$startDate, $endDate])

            ->select([
                'offer_requests.*',
                'u.phone_code as user_phone_code',
                'u.mobile as user_mobile',
                'c.model_name as car_model'
            ])
            ->orderBy('offer_requests.created_at', 'desc');
    }
    public function headings(): array
    {
        return [
            'User Mobile',
            'Car Model',
            'Requested Name',
            'Requested Mobile',
            'Requested Email',
            'Type',
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
    public function map($offer): array
    {
        return [
            $offer->user->phone_code . $offer->user->mobile,
            $offer->car_model,
            $offer->full_name,
            $offer->phone_code . $offer->mobile,
            $offer->email,
            config('params.offer_request.type')[$offer->type],
            config('params.offer_request.status')[$offer->status],
        ];
    }
}
