<?php

namespace App\Services;

/**
 * generate html for rating
 */
class FaRatingHtmlService
{
    const MAX_RATING = 5;

    /**
     * average rating
     *
     * @var float
     */
    protected $avgRating;


    public function __construct($rating)
    {
        $this->avgRating = $rating;
    }

    /**
     * generate html
     *
     * @return string
     */
    public function handle()
    {
        $res = "";
        if ($this->avgRating > self::MAX_RATING) {
            return $res;
        }

        $empty = floor(self::MAX_RATING - $this->avgRating);
        $half = ($this->avgRating - floor($this->avgRating)) > 0 ? 1 : 0;
        $full = floor($this->avgRating);

        while ($full--) {
            $res .= "<i class='fa fa-star text-warning'></i>";
        }
        while ($half--) {
            $res .= "<i class='fa fa-star-half-alt text-warning'></i>";
        }
        while ($empty--) {
            $res .= "<i class='fa fa-star'></i>";
        }

        return $res;
    }
}
