<?php

namespace App\Http\Controllers\Admin;
use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\BrandService;
use App\DataGrids\Admin\BrandDataGrid;
use App\Http\Requests\Admin\BrandRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new BrandDataGrid(request()->query());

        return view('admin.brand.index', compact('grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        try 
        {
            $service = new BrandService($request);
            $brand = $service->handle();
        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.brand.show', $brand)->with('success', 'Brand created successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return view('admin.brand.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        try 
        {
            $service = new BrandService($request,$brand);
            $brand = $service->handle();
        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.brand.show', $brand)->with('success', 'Brand updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        DB::beginTransaction();
        try {
            $oldPicture[] = $brand->icon;
            JunkFileDeleteJob::dispatchAfterResponse(Brand::FILE_DIR, $oldPicture); 
            $brand->delete();
            DB::commit();
        } catch (Exception $ex) {
            logger($ex);
            DB::rollBack();
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.brand.index')->with('success', 'Brand deleted successfully!');

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
        $countryId = $request->query('country_id');
        $limit = 100;
        $offset = ($page - 1) * $limit;

        $query = Brand::where('name', 'like', "%$term%")->active();
        if ($countryId) {
            $query->where('country_id', $countryId);
        }
        $cities = $query->select(['id', 'name AS text'])->offset($offset)->limit($limit)->get()->toArray();

        $response['results'] = $cities;
        $response['pagination'] = ['more' => !empty($cities) ?? false];
        
        return $response;
    }

}
