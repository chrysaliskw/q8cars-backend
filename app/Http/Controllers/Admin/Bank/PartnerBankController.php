<?php

namespace App\Http\Controllers\Admin\Bank;

use Exception;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BankRequest;
use App\DataGrids\Admin\PartnerBankDataGrid;
use App\Services\Admin\BankService;

class PartnerBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grid = new PartnerBankDataGrid(request()->query());
        return view('admin.banks.partner-banks.index', compact('grid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banks.partner-banks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BankRequest $request)
    {
        try {
            $service = new BankService();
            $partner_bank = $service->create($request);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.partner-banks.show', $partner_bank)->with('success', 'Bank added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $partner_bank)
    {
        $viewData = [
            'Bank Name' => empty($partner_bank->bank_name) ? 'NIL' : $partner_bank->bank_name,
            'Branch Name' => empty($partner_bank->branch_name) ? 'NIL' : $partner_bank->branch_name,
            'City' => empty($partner_bank->city) ? 'NIL' : $partner_bank->city,
            // 'Base Gross Income' => empty($partner_bank->base_gross_income) ? 'NIL' : 'KWD '. $partner_bank->base_gross_income,
            // 'Base Other EMI' => empty($partner_bank->base_other_emi) ? 'NIL' : 'KWD '. $partner_bank->base_other_emi,
            // 'Base Interest Rate' => empty($partner_bank->base_interest_rate) ? 'NIL' : 'KWD '. $partner_bank->base_interest_rate,
            'Eligible EMI Percentage' => empty($partner_bank->eligible_emi_percentage) ? 'NIL' : $partner_bank->eligible_emi_percentage . '%',
            'Logo' => empty($partner_bank->logo) ? 'NIL' : '<img src="' . url(file_asset('files-banks', $partner_bank->logo)) . '" alt="Logo" style="max-width: 200px;"/>',
            'Status' => $partner_bank->status == Bank::STATUS_ACTIVE ? 'Active' : ($partner_bank->status == Bank::STATUS_INACTIVE ? 'Inactive' : 'Unknown'),
            'Created At' => dateTimeFormat($partner_bank->created_at),
            'Updated At' => dateTimeFormat($partner_bank->updated_at),
        ];

        return view('admin.banks.partner-banks.show', compact('partner_bank', 'viewData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $partner_bank)
    {
        return view('admin.banks.partner-banks.edit', compact('partner_bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankRequest $request, Bank $partner_bank)
    {

        if (!isset($request->logo)) {
            $request->merge(['logo' => $partner_bank->logo]);
        }
        try {
            $service = new BankService();
            $service->update($request, $partner_bank);
        } catch (Exception $ex) {
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.partner-banks.show', $partner_bank)->with('success', 'Bank updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $partner_bank)
    {
        try {
            $partner_bank->delete();
        } catch (Exception $ex) {
            return back()->with('error', __('app.error'))->withInput();
        }

        return redirect()->route('admin.partner-banks.index')->with('success', 'Bank deleted successfully!');
    }
}
