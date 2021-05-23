<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model,  ModelNotFoundException};

class County extends Model
{
    protected $table = 'dims.county';

    public static function index($province_id)
    {
        $counties['data'] = self::filteredByProvince($province_id)->get('county as name','id');
        if (empty($provinces)) throw new ModelNotFoundException();
        $counties['count'] = $counties['data']->count();
        return $counties;
    }
    public function scopeFilteredByProvince($query, $province_id)
    {
        return $query->where('province_id', $province_id);
    }
}
