<?php

namespace App\Services\Admin;

use App\Models\BrandColorMapping;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\JunkFileDeleteJob;

class ColorService
{
    protected $color;

    protected $request;

    public function __construct(Request $request, BrandColorMapping $color = null)
    {
        $this->color = $color;
        $this->request = $request;
    }

    public function handle()
    {
        if (! $this->color) {
            return $this->create();
        }

        return $this->update();
    }

    private function create()
    {
        DB::beginTransaction();
        try {
            $insertData = [];
            foreach ($this->request->brand as $label) {
                $insertData[] = [
                    'name' => $this->request->name,
                    'code' => $this->request->color_code,
                    'brand_id' => $label,
                    'status' => $this->request->status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            BrandColorMapping::insert($insertData);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
           
        }
    }

    private function update()
    {
        try {

            DB::beginTransaction();
           
                $this->color->brand_id = $this->request->brand;
                $this->color->status = $this->request->status;
                $this->color->name = $this->request->name;
                $this->color->code = $this->request->color_code;
                $this->color->save();
                DB::commit();

                return $this->color;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
