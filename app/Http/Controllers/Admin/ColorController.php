<?php

namespace App\Http\Controllers\Admin;
use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Admin\ColorService;
use App\DataGrids\Admin\ColorDataGrid;
use App\Http\Requests\Admin\ColorRequest;
use App\Models\BrandColorMapping;
use App\Models\Car;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new ColorDataGrid(request()->query());

        return view('admin.color.index', compact('grid'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brandArr =  Brand::active()->pluck('name','id')->toArray();
        return view('admin.color.create',compact('brandArr'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ColorRequest $request)
    {
        try 
        {
            $service = new ColorService($request);
            $service->handle();
        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.color.index')->with('success', 'Color created successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BrandColorMapping $color)
    {
        return view('admin.color.show', compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(BrandColorMapping $color)
    {
        $brandArr =  Brand::active()->pluck('name','id')->toArray();
    
        return view('admin.color.edit', compact('color','brandArr'));  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ColorRequest $request, BrandColorMapping $color)
    {
        try 
        {
            if($color->status == BrandColorMapping::STATUS_ACTIVE && $request->status == BrandColorMapping::STATUS_INACTIVE)
            {
                $colors = json_decode(Car::active()->pluck('colours'));
                if (in_array($color->id, $colors)) {
                    return back()->with('error', __('Cannot deactivate color: Active cars are associated with it. Please deactivate or reassign the cars first.'));
                }
                
            }
            $service = new ColorService($request,$color);
             $color = $service->handle();
        }
        catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.color.show',$color)->with('success', 'Color updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BrandColorMapping $color)
    {
        $colors = json_decode(Car::active()->pluck('colours'));
        if (in_array($color->id, $colors)) {
            return back()->with('error', __('Cannot delete color: Active cars are associated with it. Please deactivate or reassign the cars first.'));
        }
        DB::beginTransaction();
        try {
          
            $color->delete();
            DB::commit();
        } catch (Exception $ex) {
            logger($ex);
            DB::rollBack();
            return back()->with('error', __('app.error'))->withInput();
        }
        return redirect()->route('admin.color.index')->with('success', 'Color deleted successfully!');

    }

     /**
     * Search endpoint for select2 dropdown
     * 
     * @param Request $request
     * @return array
     */
    public function select(Request $request)
    {
        
        $brandId = $request->query('brand_id');
        $colors = BrandColorMapping::where('brand_id', $brandId)->pluck('name', 'id');
    
        return response()->json($colors);
    }

}
