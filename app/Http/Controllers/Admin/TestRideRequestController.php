<?php

namespace App\Http\Controllers\Admin;
use Exception;
use App\Models\TestDrive;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use App\Jobs\SendAdminMailJob;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\DataGrids\Admin\TestRideRequestDataGrid;

class TestRideRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new TestRideRequestDataGrid(request()->query());

        return view('admin.test-ride-requests.index', compact('grid'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TestDrive $testDrive,$id)
    {
        $testDrive = TestDrive::find($id);
        $viewData = [
            'id' => $id,
            'User Mobile' =>  $testDrive->user ? "<a href='" . route('admin.user.show', $testDrive->user->id) . "'>{$testDrive->user->phone_code} {$testDrive->user->mobile}</a>": 'NA',
            'Car Model' => $testDrive->car->model_name,
            'Car Brand' => "<a href='" . route('admin.brand.show', $testDrive->car->brand->id) . "'>{$testDrive->car->brand->name}</a>" ,
            'Requested Name' => $testDrive->first_name . ' '.$testDrive->last_name,
            'Requeted Mobile' => $testDrive->mobile,
            // 'status_key' => $testDrive->status,
            'Status' => config('params.test_drive.status')[$testDrive->status],
            'Created At' =>  dateTimeFormat($testDrive->created_at),
            'Updated At' => dateTimeFormat($testDrive->updated_at),

        ];
        return view('admin.test-ride-requests.show', compact('viewData','testDrive'));
    }

    public function update(Request $request)
    {
        $testDrive = TestDrive::find($request->id);

        // $settings = SmtpSetting::checkSmtpConfig();
        // $details = [];

        // if ($settings) {

            Log::info('Status in request: ' . $request->status);
            if ($request->status == TestDrive::STATUS_COMPLETED) {
                $page = 'emails.admin.testdrive.testdrive_completed';
            } elseif ($request->status == TestDrive::STATUS_ONGOIND) {
                $page = 'emails.admin.testdrive.testdrive_ongoing';
            } elseif ($request->status == TestDrive::STATUS_REJECTED) {
                $page = 'emails.admin.testdrive.testdrive_rejected';
            } elseif ($request->status == TestDrive::STATUS_CANCELLED) {
                $page = 'emails.admin.testdrive.testdrive_cancelled';
            } else {
                $page = null;
            }

            $details = [
                'title' => 'Test Drive Request',
                'page'  => $page,
                'testDrive' => $testDrive,
            ];
        // }

        try {
            $testDrive->status = $request->status;
            $testDrive->save();

            if($page){
                dispatch(new SendAdminMailJob($details, $testDrive->user->email));
                return response()->json(['success' => true, 'message' => 'Test Drive request status updated and email sent.']);
            }
            return response()->json(['success' => true, 'message' => 'Test Drive request status updated.']);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return back()->with('failed', 'Failed! There is some issue with email provider.');
        }
        // $testDrive->status = $request->status;
        // $testDrive->save();
        // return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        // return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
