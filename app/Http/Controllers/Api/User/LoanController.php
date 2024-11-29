<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Faq;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoanResource;
use App\Http\Resources\FaqListResource;
use Illuminate\Support\Facades\Validator;
use App\Services\Api\User\Bank\LoanService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;

class LoanController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_id' => 'required|exists:banks,id',
            'base_gross_income' => 'required|numeric|min:0|max:99999999',
            'loanTenureYears' => 'required|integer|min:1|max:7',
            'base_interest_rate' => 'required|numeric|min:7|max:100',
            'base_other_emi' => 'nullable|numeric|min:0|max:99999999',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $bankId = $request->bank_id;
        $bank = Bank::find($bankId);
        // $data = LoanResource::collection($banks);
        // $data = new LoanResource($bank);
        $service = new LoanService($request, $bankId);

        $loanEligibility = $service->calculateLoanEligibility();

        $loanDetails = new LoanResource([
            'bank_id' => $bank->id,
            'bank_name' => $bank->bank_name,
            'maxLoanAmount' => $loanEligibility['maxLoanAmount'],
            'eligibleEmi' => $loanEligibility['eligibleEmi'],
            'interestRate' => $request->input('base_interest_rate'),
            'eligibility' => $loanEligibility['eligibility'],
        ]);

        $faqs = Faq::active()->paginate(20);
        $faqData = FaqListResource::collection($faqs);

        $data = ([
            'loan_details' => $loanDetails,
            'faqs' => $faqData,
        ]);

        $faqs = Faq::active()->Orderby('sort_order','asc')->paginate(20);
        $faqData = FaqListResource::collection($faqs);
        $data['faqs'] = $faqData;

        return $this->success(['data' => $data], 'Loan details', Response::HTTP_OK);

    }
}
