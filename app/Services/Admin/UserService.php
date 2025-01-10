<?php

namespace App\Services\Admin;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\JunkFileDeleteJob;
use App\Models\Country;

class UserService
{
    protected $user;

    protected $request;

    public function __construct(Request $request, User $user = null)
    {
        $this->user = $user;
        $this->request = $request;
    }

    public function handle()
    {
        if (! $this->user) {
            return $this->create();
        }

        return $this->update();
    }

    private function create()
    {
        DB::beginTransaction();
        try {

           $this->user = new User();
           $this->user->name = $this->request->name;
           $this->user->country_id = Country::active()->first()->id;
           $this->user->email = $this->request->email;
           $this->user->address = $this->request->address;
           $this->user->phone_code = $this->request->phone_code;
           $this->user->mobile = $this->request->phone_code .$this->request->mobile;
           $this->user->status = $this->request->status;

           if($this->request->hasfile('picture')){
                $this->request->picture->store(User::FILE_DIR);
                $this->user->picture = $this->request->picture->hashName();  
           }
            $this->user->save();   
            DB::commit();

            return $this->user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function update()
    {
        try {
            // dd($this->request->all(),'here');

            DB::beginTransaction();
            $this->user->name = $this->request->name;
            $this->user->country_id = Country::active()->first()->id;
            $this->user->email = $this->request->email;
            $this->user->address = $this->request->address;
            $this->user->phone_code = $this->request->phone_code;
            $this->user->mobile = $this->request->phone_code .$this->request->mobile;
            $this->user->status = $this->request->status;
            
            if($this->request->hasfile('picture')){
                $oldPicture[] = $this->user->picture;
                $this->request->picture->store(User::FILE_DIR);
                $this->user->picture = $this->request->picture->hashName();  
                JunkFileDeleteJob::dispatchAfterResponse(User::FILE_DIR, $oldPicture); 
           }
           
            $this->user->save();
            DB::commit();

            return $this->user;
        } catch (Exception $ex) {
            DB::rollBack();
            return back()->with('error', __('app.error' ))->withInput();
            throw $ex;
        }
    }
}
