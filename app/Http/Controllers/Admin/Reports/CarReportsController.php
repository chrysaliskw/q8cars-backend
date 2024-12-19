<?php

namespace App\Http\Controllers\Admin\Reports;

use App\DataGrids\Admin\Reports\CarReportDataGrid;
use App\Exports\CarExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;



class CarReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        $grid = new CarReportDataGrid(request()->query());
        // dd(request()->query());
        return view('admin.reports.car.index', compact('grid'));
    }

    /**
     * Exporting the file
     *
     * @return file
     */
    public function export(Request $request)
    {
        if (empty($request->startDate) && empty($request->endDate) && empty($request->model) && empty($request->brand) && empty($request->is_upcoming) && empty($request->is_just_launched) && empty($request->status)) {
            return back()->with('error', __('Please choose at least one filter to export the report'));
        }
        $name = 'Q8cars_Car_Report.xlsx';
        if (! empty($request->startDate)) {
            $start = Carbon::parse($request->startDate)->format('d_M_Y');
            $end = Carbon::parse($request->endDate)->format('d_M_Y');
            $name = 'Q8cars_Car_Report' . $start . '_To_' . $end . '.xlsx';
        }
        return (new CarExport($request->startDate, $request->endDate))
            ->forModel($request->car_1_id)
            ->forBrand($request->brand_1_id)
            ->forUpcoming($request->is_upcoming)
            ->forJustLaunch($request->is_just_launched)
            ->forStatus($request->status)
            ->download($name, \Maatwebsite\Excel\Excel::XLSX);
    }
}
