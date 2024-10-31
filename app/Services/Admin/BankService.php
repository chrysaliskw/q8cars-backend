<?php

namespace App\Services\Admin;

use Exception;
use App\Models\User;
use App\Models\Bank;
use Illuminate\Http\Request;
use App\Jobs\JunkFileDeleteJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\BankRequest;

class BankService
{
    protected $partner_bank;
    protected $request;

    public function create(BankRequest $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $request->file('logo')->store(Bank::FILE_DIR);
                $data['logo'] = $request->file('logo')->hashName();
            }

            // logger('Data being inserted:', $data);

            $partner_bank = Bank::create($data);

            DB::commit();
            return $partner_bank;
        }
        catch(Exception $ex){
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
    }

    public function update(BankRequest $request, Bank $partner_bank)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                if ($partner_bank->logo) {
                    Storage::disk('public')->delete($partner_bank->logo);
                }
                $request->file('logo')->store(Bank::FILE_DIR);
                $data['logo'] = $request->file('logo')->hashName();
            }

            $partner_bank->update($data);

            DB::commit();
            return $partner_bank;
        }
        catch(Exception $ex){
            DB::rollBack();
            logger($ex);
            return back()->with('error', __('app.error'))->withInput();
        }
    }

}
