<?php

namespace App\Http\Controllers\Admin\Reports;

use App\DataGrids\Admin\Reports\TestRideReportDataGrid;
use App\Exports\TestRideRequestExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestRideRequestsController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        // if ($request->hasAny(['start_date', 'end_date'])) {
        //     $validated = $request->validate([

        //         'start_date' => 'required|date|before_or_equal:today',
        //         'end_date' => [
        //             'nullable',
        //             'date',
        //             'after_or_equal:start_date',
        //             function ($attribute, $value, $fail) use ($request) {
        //                 $startDate = Carbon::parse($request->start_date);
        //                 $endDate = $value ? Carbon::parse($value) : Carbon::today();
        //                 if ($endDate->diffInDays($startDate) > 30) {
        //                     $fail('The number of days should be 30 or less.');
        //                 }
        //             },
        //         ],
        //     ]);
        // }

        // $grid = new TestRideReportDataGrid(request()->query());
        // return view('admin.reports.test-ride.index', compact('grid'));
        // if ($request->hasAny(['start_date', 'end_date'])) {
        //     $validated = $request->validate([

        //         'start_date' => 'required|date|before_or_equal:today',
        //         'end_date' => [
        //             'nullable',
        //             'date',
        //             'after_or_equal:start_date',
        //             function ($attribute, $value, $fail) use ($request) {
        //                 $startDate = Carbon::parse($request->start_date);
        //                 $endDate = $value ? Carbon::parse($value) : Carbon::today();
        //                 if ($endDate->diffInDays($startDate) > 30) {
        //                     $fail('The number of days should be 30 or less.');
        //                 }
        //             },
        //         ],
        //     ]);
        // }

        $grid = new TestRideReportDataGrid(request()->query());
        return view('admin.reports.test-ride.index', compact('grid'));
    }

    public function export(Request $request)
    {

        // dd($request->all());
        if (
            empty($request->startDate) && empty($request->endDate)
            && empty($request->user_id) && empty($request->car_1_id)
            && empty($request->brand_1_id) && empty($request->status)
        ) {
            // return redirect()->back()->with('error', 'Please select start date and end date.');
            return back()->with('error', __('Please choose at least one filter to export the report'));
        }
        $name = 'Q8cars_Test_Ride_Request_Report.xlsx';
        if (! empty($request->startDate)) {
            $start = Carbon::parse($request->startDate)->format('d_M_Y');
            $end = Carbon::parse($request->endDate)->format('d_M_Y');
            $name = 'Q8cars_Test_Ride_Request_Report' . $start . '_To_' . $end . '.xlsx';
        }
        return (new TestRideRequestExport($request->startDate, $request->endDate))
            // ->forBrand($request->brand_1_id)
            ->forBrand($request->brand_1_id)
            ->forModel($request->car_1_id)
            ->forStatus($request->status)


            ->download($name, \Maatwebsite\Excel\Excel::XLSX);
    }
}
