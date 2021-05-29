<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class District extends Model
{
    protected $table = "dims.district";

    public static function index($county_id)
    {
        $districts['data'] = self::filteredByCounty($county_id)->get(['district as name', 'id']);
        if (empty($districts)) throw new ModelNotFoundException();
        $districts['count'] = $districts['data']->count();
        return $districts;
    }

    public function scopeFilteredByCounty($query, $county_id)
    {
        return $query->where('county_id', $county_id);
    }

}
