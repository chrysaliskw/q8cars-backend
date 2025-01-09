<?php

namespace App\Http\Controllers\Admin\Reports;

use App\DataGrids\Admin\Reports\LoanRequestReportDataGrid;
use App\Exports\LoanRequestsReportsExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanRequestReportController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
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

        $grid = new LoanRequestReportDataGrid(request()->query());
        // dd($grid);
        return view('admin.reports.loan-requests-reports.index', compact('grid'));
    }

    public function export(Request $request)
    {
        if (
            empty($request->startDate) && empty($request->endDate)
            && empty($request->user_id) && empty($request->name)
            && empty($request->bank_id) && empty($request->status)
            && empty($request->area_id)
        ) {
            // return redirect()->back()->with('error', 'Please select start date and end date.');
            return back()->with('error', __('Please choose at least one filter to export the report'));
        }
        // dd($request->all());
        $name = 'Q8cars_Loan_Request_Report.xlsx';
        if (! empty($request->startDate)) {
            $start = Carbon::parse($request->startDate)->format('d_M_Y');
            $end = Carbon::parse($request->endDate)->format('d_M_Y');
            $name = 'Q8cars_Loan_Report' . $start . '_To_' . $end . '.xlsx';
        }
        return (new LoanRequestsReportsExport($request->startDate, $request->endDate))
            // ->forBrand($request->brand_1_id)
            ->forBank($request->bank_id)
            ->forUser($request->name)
            ->forStatus($request->status)
            ->download($name, \Maatwebsite\Excel\Excel::XLSX);
    }
}
