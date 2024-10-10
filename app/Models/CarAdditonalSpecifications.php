<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarAdditonalSpecifications extends Model
{
    use HasFactory;

    const TYPE_TEXT = 1;
    const TYPE_BOOLEAN = 2;

    const CATEGORY_ENGINE = 1;
    const CATEGORY_FUEL = 2;
    const CATEGORY_SUSPENSION = 3;
    const CATEGORY_DIMENSION = 4;
    const CATEGORY_COMFORT = 5;
    const CATEGORY_INTERIOR = 6;
    const CATEGORY_EXTERIOR = 7;
    const CATEGORY_SAFETY = 8;
    const CATEGORY_ENTERTAINMENT = 9;
    // const CATEGORY_KEY_SPEC = 10;
    // CONST CATEGORY_KEY_FEATURE = 11;

    const IS_KEY_FEATURE = 1;
    const IS_KEY_SPEC= 1;
}
