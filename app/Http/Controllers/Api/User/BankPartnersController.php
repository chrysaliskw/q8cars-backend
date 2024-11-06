<?php

namespace App\Http\Controllers\Api\User;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\BankPartners;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class BankPartnersController extends ApiBaseController
{
    public function __invoke()
    {
        try {
            
            $banks = Bank::active()->get();
            $data = BankPartners::collection($banks);
            // dd($banks);
            return $this->success(['data' => $data], 'Bank listing Successful ', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->error(__('app.error'), Response::HTTP_INTERNAL_SERVER_ERROR . ' - ' . $e->getMessage());
        }
    }
}
