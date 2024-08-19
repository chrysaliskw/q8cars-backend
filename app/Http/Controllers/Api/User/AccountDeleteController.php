<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiBaseController;

class AccountDeleteController extends ApiBaseController
{   
    
    public function __invoke()
    {
        $user = User::find(Auth::id());
        if($user){          
            $user->delete();
        } 
        return $this->success(['data' => []], 'Account deleted successfully', Response::HTTP_OK);       
    }
}
