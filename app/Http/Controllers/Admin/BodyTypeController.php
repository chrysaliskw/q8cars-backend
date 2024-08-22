<?php

namespace App\Http\Controllers\Admin;
use Exception;
use App\Models\BodyType;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\BodyTypeService;
use App\DataGrids\Admin\BodyTypeDataGrid;
use App\Http\Requests\Admin\BodyTypeRequest;

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
            $oldPicture[] = $bodyType->icon;
            JunkFileDeleteJob::dispatchAfterResponse(BodyType::FILE_DIR,$oldPicture); 
            $bodyType->delete();
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.body-type.index')->with('success', 'Body Type deleted successfully!');

    }

     /**
     * Search endpoint for select2 dropdown
     * 
     * @param Request $request
     * @return array
     */
    public function select(Request $request)
    {
        $page = $request->query('page');
        $term = $request->query('search');
       
        $limit = 100;
        $offset = ($page - 1) * $limit;

        $query = BodyType::where('name', 'like', "%$term%")->active();
      
        $cities = $query->select(['id', 'name AS text'])->offset($offset)->limit($limit)->get()->toArray();

        $response['results'] = $cities;
        $response['pagination'] = ['more' => !empty($cities) ?? false];
        
        return $response;
    }


}
