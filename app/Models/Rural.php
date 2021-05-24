<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Rural extends Model
{
    protected $table = "dims.rural";

    public static function index($district_id)
    {
        $rurals['data'] = self::filteredByDistrict($district_id)->get('rural as name', 'id');
        if (empty($rurals)) throw new ModelNotFoundException();
        $rurals['count'] = $rurals['data']->count();
        return $rurals;
    }

    public function scopeFilteredByDistrict($query, $district_id)
    {
        return $query->where('district_id', $district_id);
    }

}
