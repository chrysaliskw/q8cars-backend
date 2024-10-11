<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;

class BankController extends ApiBaseController
{
    public function __invoke()
    {
        $banks = Bank::active()->get();

        $data = BankResource::collection($banks);

        return $this->success(['data' => $data], 'Bank Listing', Response::HTTP_OK);
    }
}
