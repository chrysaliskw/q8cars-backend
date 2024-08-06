<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Flyer;
use App\Models\FlyerMaster;
use Illuminate\Http\Request;
use App\Services\ZebraImageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (empty(request('name')) || empty(request('type'))) {
            abort(404);
        }

        $sizeFolder = request('size');
        $size = config('image-resize.sizes.' . $sizeFolder);
        Log::info("SizeFolder = ");
        Log::info($sizeFolder);

        if (empty($size)){
            $size = config('image-resize.sizes.default');
        }

        $type = str_replace('-', '.', request('type'));
        $basePath = config('params.' . $type);
        $path = storage_path("app". DIRECTORY_SEPARATOR . "{$basePath}" . DIRECTORY_SEPARATOR . request('name'));

        if (! File::exists($path)) {
            abort(404);
        }

        $cacheFolder = storage_path('app' . DIRECTORY_SEPARATOR . 'zebra') . DIRECTORY_SEPARATOR . $basePath . DIRECTORY_SEPARATOR . $sizeFolder;
        if(!file_exists($cacheFolder)){
            Storage::makeDirectory('zebra' . DIRECTORY_SEPARATOR . $basePath . DIRECTORY_SEPARATOR . $sizeFolder);
            // dd($cacheFolder);
        }

        $cachePath = $cacheFolder . DIRECTORY_SEPARATOR . request('name');

        if(file_exists($cachePath)){
            Log::info("file exists");
            return $this->renderImage($cachePath);
        }
        
        try{
            $image = new ZebraImageService();
            $image->source_path = $path;
            $image->target_path = $cachePath;

            // dd($image);
            $image->resize($size[0], $size[1], ZEBRA_IMAGE_CROP_CENTER, -1);
        }catch(\Exception $e){
            abort(404, $e->getMessage());
        }
        
    
        return $this->renderImage($cachePath);
    }

    private function renderImage($cachePath){
        define('CACHE_EXPIRES', 60 * 60 * 24 * 14);

        $file = File::get($cachePath);
        $type = File::mimeType($cachePath);    
        $response = Response::make($file, 200);
    
        $response->header("Content-Type", $type);
        $response->header("Pragma", 'public');
        $response->header("Expires", gmdate('D, d M Y H:i:s', time() + CACHE_EXPIRES) . ' GMT');

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        if (empty(request('name')) || empty(request('type'))) {
            abort(404);
        }
        
        $type = str_replace('-', '.', request('type'));
        $basePath = config('params.' . $type);
        $path = storage_path("app/{$basePath}/" . request('name'));

        if (! File::exists($path)) {
            abort(404);
        }
    
        $file = File::get($path);
        $type = File::mimeType($path);    
        $response = Response::make($file, 200);
    
        $response->header("Content-Type", $type) 
                ->header("Content-Disposition", 'inline; filename="'.request('original').'"');
      
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show flyer html for app
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showFlyer($id, Request $request)
    {
        $flyer = FlyerMaster::find($id);
        $flyers = Flyer::where('flyer_master_id', $id)->get();
       
        //Update flyer click count
        $flyer->clicks = $flyer->clicks + 1;
        $flyer->save();

        $showPage = 0;
        if(isset($request->page)) {
            $showPage = $request->page - 1;
        }

        return view('flyer-html.index', compact('flyers','showPage'));
    
    }

    /**
     * Show flyer html for app
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showFlyerCarousel($id)
    {
        $flyer = FlyerMaster::find($id);
        $flyers = Flyer::where('flyer_master_id', $id)->get();
       
        return view('flyer-html.index-test', compact('flyers',));
       
    }
}
