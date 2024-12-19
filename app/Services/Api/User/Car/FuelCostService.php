<?php

namespace App\Services\Api\User\Car;

use App\Models\CarVersion;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PSpell\Config;

class FuelCostService
{
    public function calculateFuelCostPerMonth($kmsPerDay, int $carVersionId, int $daysInMonth = 30)
    {
        try {
            $carVersion = CarVersion::find($carVersionId);
            switch ($carVersion->fuel_type) {
                case 1:
                    $fuelCostPerLiter = DB::table('configurations')->where('key', 'fuel_cost_petrol')->value('value');
                    break;
                case 2:
                        $fuelCostPerLiter = DB::table('configurations')->where('key', 'fuel_cost_diesel')->value('value');
                        break;
                case 3:
                        $fuelCostPerLiter = DB::table('configurations')->where('key', 'fuel_cost_cng')->value('value');
                        break;
                case 4:
                        $fuelCostPerLiter = DB::table('configurations')->where('key', 'fuel_cost_electric')->value('value');
                        break;
                
                default:
                    # code...
                    break;
            }
            // $fuelCostPerLiter = DB::table('configurations')->where('key', 'fuel_cost_per_liter')->value('value');
            // $fuelCostPerLiter = Configuration::fuelCostPerLiter();
            if ($fuelCostPerLiter === null) {
                throw new \Exception("Fuel cost not found in the database.");
            }

            $mileage = $carVersion->mileage;;

            if ($mileage === null) {
                throw new \Exception("Mileage not found in the database.");
            }

            $totalKmsPerMonth = $kmsPerDay * $daysInMonth;

            $fuelConsumption = $totalKmsPerMonth / $mileage;

            $monthlyFuelCost = $fuelConsumption * $fuelCostPerLiter;

            return currency_formatter($monthlyFuelCost);
        } catch (\Exception $e) {
            Log::error("Error calculating fuel cost: " . $e->getMessage());
            return 0.0;
        }
    }
}
