<?php

namespace App\Services\Admin;

use App\Models\Brand;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\JunkFileDeleteJob;

class BrandService
{
    protected $brand;

    protected $request;

    public function __construct(Request $request, Brand $brand = null)
    {
        $this->brand = $brand;
        $this->request = $request;
    }

    public function handle()
    {
        if (! $this->brand) {
            return $this->create();
        }

        return $this->update();
    }

    private function create()
    {
        DB::beginTransaction();
        try {

           $this->brand = new Brand();
           $this->brand->name = $this->request->name;
           $this->brand->is_top_brand = $this->request->is_top_brand;
           $this->brand->status = $this->request->status;

           if($this->request->hasfile('icon')){
                $this->request->icon->store(Brand::FILE_DIR);
                $this->brand->icon = $this->request->icon->hashName();  
           }
            $this->brand->save();   
            DB::commit();

            return $this->brand;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function update()
    {
        try {

            DB::beginTransaction();
            $this->brand->name = $this->request->name;
            $this->brand->is_top_brand = $this->request->is_top_brand;
            $this->brand->status = $this->request->status;
            
            if($this->request->has('icon')){
                $oldPicture[] = $this->brand->icon;
                $this->request->icon->store(Brand::FILE_DIR);
                $this->brand->icon = $this->request->icon->hashName(); 
                JunkFileDeleteJob::dispatchAfterResponse(Brand::FILE_DIR, $oldPicture); 
            }
            $this->brand->save();
            DB::commit();

            return $this->brand;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
