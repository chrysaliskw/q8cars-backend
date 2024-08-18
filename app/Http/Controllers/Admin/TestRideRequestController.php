<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\TestDrive;
use Exception;
use App\DataGrids\Admin\TestRideRequestDataGrid;
use Illuminate\Http\Request;

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
        $testDrive->status = $request->status;
        $testDrive->save();
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        // return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}