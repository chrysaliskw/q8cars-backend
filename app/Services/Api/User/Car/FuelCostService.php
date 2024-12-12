<?php

namespace App\Services\Api\User\Car;

use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PSpell\Config;

class FuelCostService
{
    public function calculateFuelCostPerMonth($kmsPerDay, int $carVersionId, int $daysInMonth = 30)
    {
        try {

            $fuelCostPerLiter = DB::table('configurations')->where('key', 'fuel_cost_per_liter')->value('value');
            // $fuelCostPerLiter = Configuration::fuelCostPerLiter();
            if ($fuelCostPerLiter === null) {
                throw new \Exception("Fuel cost not found in the database.");
            }

            $mileage = DB::table('car_versions')->where('id', $carVersionId)->value('mileage');

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
