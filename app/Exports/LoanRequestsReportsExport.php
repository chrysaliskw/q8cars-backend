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
    protected $bank_name;
    protected $name;
    protected $counter = 0;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate)->startOfDay();
        $this->endDate = Carbon::parse($endDate)->endOfDay();
    }

    public function forBank($bank_name)
    {
        
        $this->bank_name = $bank_name;
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

        $query = BankSuggestionRequest::query()
            ->leftJoin('users', 'users.id', '=', 'bank_suggestion_requests.user_id')
            ->leftJoin('banks', 'banks.id', '=', 'bank_suggestion_requests.bank_id')
            ->when($this->bank_name, function ($q) {
                $q->where('banks.bank_name', $this->bank_name);
            })
            ->when($this->name, function ($q) {
                $q->where('users.name', $this->name);
            })
            ->when($this->status, function ($q) {
                $q->where('bank_suggestion_requests.status', $this->status);
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
                'bank_suggestion_requests.created_at',
            ])
            ->orderBy('bank_suggestion_requests.id', 'desc');
            // dd($this);
        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Bank Name',
            'User Name',
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
            $row->email,
            $row->contact_number,
            config('params.banks.status')[$row->status] ?? 'Unknown',
            dateFormat($row->created_at),
        ];
    }
}
