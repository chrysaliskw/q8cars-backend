<?php

namespace App\Services\Admin;

use App\Models\BodyType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\JunkFileDeleteJob;

class BodyTypeService
{
    protected $bodyType;

    protected $request;

    public function __construct(Request $request, BodyType $bodyType = null)
    {
        $this->bodyType = $bodyType;
        $this->request = $request;
    }

    public function handle()
    {
        if (! $this->bodyType) {
            return $this->create();
        }

        return $this->update();
    }

    private function create()
    {
        DB::beginTransaction();
        try {

           $this->bodyType = new BodyType();
           $this->bodyType->name = $this->request->name;
           $this->bodyType->status = $this->request->status;

           if($this->request->hasfile('icon')){
                $this->request->icon->store(BodyType::FILE_DIR);
                $this->bodyType->icon = $this->request->icon->hashName();  
           }
            $this->bodyType->save();   
            DB::commit();

            return $this->bodyType;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function update()
    {
        try {

            DB::beginTransaction();
            $this->bodyType->name = $this->request->name;
            $this->bodyType->status = $this->request->status;
            
            if($this->request->has('icon')){
                $oldPicture[] = $this->bodyType->icon;
                $this->request->icon->store(BodyType::FILE_DIR);
                $this->bodyType->icon = $this->request->icon->hashName(); 
                JunkFileDeleteJob::dispatchAfterResponse(BodyType::FILE_DIR, $oldPicture); 
            }
            $this->bodyType->save();
            DB::commit();

            return $this->bodyType;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
