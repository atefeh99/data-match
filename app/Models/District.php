<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class District extends Model
{
    protected $table = "dims.district";
    protected $connection = "pgsql";


    public static function index($county_id)
    {
        $districts['data'] = self::filteredByCounty($county_id)->get(['district as name', 'id']);
        if (empty($districts)) throw new ModelNotFoundException();
        $districts['count'] = $districts['data']->count();
        return $districts;
    }
    public static function getOne($id)
    {
        $item = self::where('id',$id)->get()->toArray();
        if(count($item)>0){
            return $item[0]['district'];
        }
        throw new ModelNotFoundException();
    }

    public function scopeFilteredByCounty($query, $county_id)
    {
        return $query->where('county_id', $county_id);
    }

}
