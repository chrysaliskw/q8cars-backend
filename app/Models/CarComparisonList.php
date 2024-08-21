<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarComparisonList extends Model
{
    use HasFactory;

    const HOME_PAGE = 1;
    const CAR_DETAIL_PAGE = 2;
    
}
