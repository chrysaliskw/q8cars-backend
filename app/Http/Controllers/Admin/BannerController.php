<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Banner;
use App\Jobs\JunkFileDeleteJob;
use App\Http\Controllers\Controller;
use App\DataGrids\Admin\BannerDataGrid;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\BannerRequest;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid = new BannerDataGrid(request()->query());

        return view('admin.banner.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\BannerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        try {

            $banner = new Banner();
            $banner->name = $request->name;
            $banner->page = $request->page;
            $banner->type = $request->type ?? 1;
            $banner->status = $request->status;
            // $banner->expiry_date = $request->expiry_date ?? null;
            // $banner->start_date = $request->start_date ?? null;
            $banner->sort_order = $request->sort_order;
            if($request->hasfile('file_name')){
                $request->file_name->store(Banner::DOC_DIR);
                $banner->file_name = $request->file_name->hashName();  
            }
            if($request->hasfile('file_name_mobile_view')){
                $request->file_name_mobile_view->store(Banner::DOC_DIR);
                $banner->file_name_mobile_view = $request->file_name_mobile_view->hashName();  
            }
            $banner->save();

        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.banner.index')
            ->with('success', 'Banner created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        return view('admin.banner.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\BannerRequest  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, Banner $banner)
    {
        try {
            $oldFile[] = $banner->file_name;
            $banner->name = $request->name;
            $banner->page = $request->page;
            $banner->type = $request->type ?? 1;
            $banner->status = $request->status;
            // $banner->expiry_date = $request->expiry_date ?? null;
            // $banner->start_date = $request->start_date ?? null;
            $banner->sort_order = $request->sort_order;
           
           if($request->has('file_name')){
                $oldPicture[] = $banner->file_name;
                $request->file_name->store(Banner::DOC_DIR);
                $banner->file_name = $request->file_name->hashName(); 
                JunkFileDeleteJob::dispatchAfterResponse(Banner::DOC_DIR, $oldPicture); 
            }

            if($request->has('file_name_mobile_view')){
                $oldPicture[] = $banner->file_name_mobile_view;
                $request->file_name_mobile_view->store(Banner::DOC_DIR);
                $banner->file_name_mobile_view = $request->file_name_mobile_view->hashName(); 
                JunkFileDeleteJob::dispatchAfterResponse(Banner::DOC_DIR, $oldPicture); 
            }

            $banner->saveOrFail();

        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.banner.show', $banner)
            ->with('success', 'Banner updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $count = Banner::active()->count();
        if($count <= 1){
            return back()->with('error', __('Cannot delete banner: At least one active banner is required.'));
        }
      
        try {
            $image = $banner->file_name;
            $image_mobile_view = $banner->file_name_mobile_view;
            $banner->delete();
            Storage::delete(Banner::DOC_DIR . DIRECTORY_SEPARATOR . $image);
            Storage::delete(Banner::DOC_DIR . DIRECTORY_SEPARATOR . $image_mobile_view);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'));
        }

        return redirect()->route('admin.banner.index')
            ->with('success', 'Banner deleted successfully!');
    }
}
