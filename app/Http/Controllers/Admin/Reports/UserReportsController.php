<?php

namespace App\Http\Controllers\Admin\Reports;

use App\DataGrids\Admin\Reports\UserReportDataGrid;
use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;



class UserReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if ($request->hasAny(['start_date', 'end_date'])) {
            $validated = $request->validate([
                'start_date' => 'required|date|before_or_equal:today',
                'end_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:start_date',
                    function ($attribute, $value, $fail) use ($request) {
                        $startDate = Carbon::parse($request->start_date);
                        $endDate = $value ? Carbon::parse($value) : Carbon::today();
                        if ($endDate->diffInDays($startDate) > 30) {
                            $fail('The number of days should be 30 or less.');
                        }
                    },
                ],
            ]);
        }

        $grid = new UserReportDataGrid(request()->query());
        return view('admin.reports.user.index', compact('grid'));
    }

    /**
     * Exporting the file
     *
     * @return file
     */
    public function export(Request $request)
    {
        // dd($request->all());
        if (empty($request->startDate) && empty($request->endDate) && empty($request->name) && empty($request->mobile) && empty($request->email)) {
            return back()->with('error', __('Please choose at least one filter to export the report'));
        }
        $name = 'Q8cars_User_Report.xlsx';
        if (! empty($request->startDate)) {
            $start = Carbon::parse($request->startDate)->format('d_M_Y');
            $end = Carbon::parse($request->endDate)->format('d_M_Y');
            $name = 'Q8cars_User_Report' . $start . '_To_' . $end . '.xlsx';
        }
        return (new UserExport($request->startDate, $request->endDate))
            ->forUser($request->name)
            ->forMobile($request->mobile)
            ->forEmail($request->email)

            ->download($name, \Maatwebsite\Excel\Excel::XLSX);
    }
}
