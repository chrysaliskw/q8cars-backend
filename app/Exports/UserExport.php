<?php

namespace App\Exports;

use App\Models\User;
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

class UserExport implements FromQuery, WithColumnFormatting, WithMapping, WithHeadings, ShouldAutoSize, ShouldQueue
{

    use Exportable;
    protected $startDate;
    protected $endDate;
    protected $name;
    protected $mobile;
    protected $email;
    protected $index = 0;
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    public function forUser($name)
    {
        $this->name = $name;
        return $this;
    }
    public function forMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }
    public function forEmail($email)
    {
        $this->email = $email;
        return $this;
    }
   
    public function query()
    {
        $endDate = Carbon::parse($this->endDate)->addHours(23)->addMinutes(59)->addSeconds(59)->format('Y-m-d H:i');
        $startDate = Carbon::parse($this->startDate)->format('Y-m-d H:i');
        
      return User::query() 
                ->where('id' ,'!=' ,0)->orderBy('id','Desc')
                ->select(['users.*', DB::raw("CONCAT(users.phone_code, users.mobile) as full_mobile")])
                ->when($this->name, function ($query, $value) {
                    $query->where('users.name', $value);
                })
                ->when($this->mobile, function ($query, $value) {
                    $query->where('users.mobile', $value);
                })
                ->when($this->email, function ($query, $value) {
                    $query->where('users.email', $value);
                })
               
                ->when($this->startDate, function ($query) use ($startDate, $endDate) {
                    $query->where('users.created_at', '>=', $startDate)
                        ->where('users.created_at', '<=', $endDate);
                })
            ->orderBy('users.created_at', 'desc');
    }
    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Phone Number',
            'Email',
            'Status',
            'Created Date'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * @var $user
     */
    public function map($user): array
    {
        $this->index++;
        return [
            $this->index,
            $user->first_name . $user->last_name,
            $user->mobile,
            $user->email,
            config('params.user.status')[$user->status],
            dateFormat($user->created_at),
        ];
    }

}
