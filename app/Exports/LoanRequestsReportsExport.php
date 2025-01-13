<?php

namespace App\Exports;

use App\Models\BankSuggestionRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoanRequestsReportsExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $status;
    protected $bank_id;
    protected $name;
    protected $counter = 0;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate)->startOfDay();
        $this->endDate = Carbon::parse($endDate)->endOfDay();
    }

    public function forBank($bank_id)
    {
        // dd($bank_id);
        $this->bank_id = $bank_id;
        return $this;
    }

    public function forUser($name)
    {
        $this->name = $name;
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


        $query = BankSuggestionRequest::query()
            ->leftJoin('users', 'users.id', '=', 'bank_suggestion_requests.user_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_suggestion_requests.bank_id')
            ->when($this->bank_id, function ($q) {
                $q->where('banks.id', $this->bank_id);
            })
            ->when($this->name, function ($q) {
                $q->where('users.name', $this->name);
            })
            ->when($this->status, function ($q) {
                $q->where('bank_suggestion_requests.status', $this->status);
            })
            ->when($this->startDate && $this->endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('bank_suggestion_requests.created_at', [$startDate, $endDate]);
            })
            // ->whereBetween('bank_suggestion_requests.created_at', [$this->startDate, $this->endDate])
            ->where('type', BankSuggestionRequest::TYPE_LOAN)
            ->select([
                'bank_suggestion_requests.id',
                'bank_suggestion_requests.contact_number',
                'bank_suggestion_requests.email',
                'bank_suggestion_requests.status',
                'banks.bank_name as bank_name',
                'users.name as requested_user',
                'users.mobile as requested_mobile',
                'bank_suggestion_requests.created_at as created_at',
            ])
            ->orderBy('bank_suggestion_requests.id', 'desc');
        // dd($this);
        return $query;
    }
    // public function gridQuery()
    // {
    //     $endDate = Carbon::parse(request()->query('end_date'))->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
    //     $startDate = Carbon::parse(request()->query('start_date'))->format('Y-m-d H:i');

    //     $query = BankSuggestionRequest::query()

    //         ->leftJoin('users', 'users.id', '=', 'bank_suggestion_requests.user_id')
    //         ->leftJoin('banks', 'banks.id', '=', 'bank_suggestion_requests.bank_id')
    //         ->select(['bank_suggestion_requests.id', 'first_name', 'last_name', 'contact_number', 'bank_suggestion_requests.email', 'bank_suggestion_requests.status', 'banks.bank_name as bank_name', 'users.name as requested_user'])
    //         ->where('type', BankSuggestionRequest::TYPE_LOAN)
    //         ->orderBy('bank_suggestion_requests.id', 'Desc');
    //     // logger($query);
    //     $query->when(request('bank_id'), function ($query, $model) {
    //         $query->where('bank_id', $model);
    //     });

    //     $query->when(request('name'), function ($query, $model) {
    //         $query->where('name', 'like', '%' . $model . '%');
    //     });

    //     $query->when(request('status'), function ($query, $model) {
    //         $query->where('status', $model);
    //     });
    //     $query->when(request('start_date'), function ($query, $model) use ($startDate, $endDate) {
    //         $query->where('bank_suggestion_requests.created_at', '>=', $startDate)
    //             ->where('bank_suggestion_requests.created_at', '<=', $endDate);
    //     });
    //     logger($query);
    //     return $query;
    // }




    public function headings(): array
    {
        return [
            '#',
            'Bank Name',
            'User Name',
            'User Mobile',
            'Email',
            'Contact Number',
            'Status',
            'Created At',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function map($row): array
    {
        $this->counter++;

        return [

            $this->counter,
            $row->bank_name,
            $row->requested_user,
            $row->requested_mobile,
            $row->email,
            $row->contact_number,
            config('params.banks.status')[$row->status] ?? 'Unknown',
            dateFormat($row->created_at),
        ];
    }
}
