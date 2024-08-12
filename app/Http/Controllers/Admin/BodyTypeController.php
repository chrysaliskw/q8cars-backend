<?php

namespace App\Http\Controllers\Admin;
use App\DataGrids\Admin\BodyTypeDataGrid;
use App\Http\Controllers\Controller;
use App\Models\BodyType;
use Exception;
use App\Http\Requests\Admin\BodyTypeRequest;
use App\Services\Admin\BodyTypeService;
use Illuminate\Support\Facades\DB;
use App\Jobs\JunkFileDeleteJob;

class BodyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new BodyTypeDataGrid(request()->query());

        return view('admin.body-type.index', compact('grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.body-type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BodyTypeRequest $request)
    {
        try 
        {
            $service = new BodyTypeService($request);
            $bodyType = $service->handle();
        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.body-type.show', $bodyType)->with('success', 'Body Type created successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BodyType $bodyType)
    {
        return view('admin.body-type.show', compact('bodyType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BodyType $bodyType)
    {
        return view('admin.body-type.edit', compact('bodyType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BodyTypeRequest $request, BodyType $bodyType)
    {
        try 
        {
            $service = new BodyTypeService($request,$bodyType);
            $bodyType = $service->handle();
        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.body-type.show', $bodyType)->with('success', 'Body Type updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BodyType $bodyType)
    {
        DB::beginTransaction();
        try {
            JunkFileDeleteJob::dispatchAfterResponse(BodyType::FILE_DIR, $bodyType->icon); 
            $bodyType->delete();
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.body-type.index')->with('success', 'Body Type deleted successfully!');

    }

}
