<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PSpell\Config;

class Configuration extends Model
{
    use HasFactory;


    public static function fuelCostPerLiter()
    {
        return Configuration::where('key', 'fuel_cost_per_liter')->value('value');
    }
}
