<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Rural extends Model
{
    protected $table = "dims.rural";
    protected $connection = "pgsql";


    public static function index($district_id)
    {
        $rurals['data'] = self::filteredByDistrict($district_id)->orderBy('rural')->get(['rural as name', 'id']);
        if (empty($rurals)) throw new ModelNotFoundException();
        $rurals['count'] = $rurals['data']->count();
        return $rurals;
    }
    public static function getOne($id)
    {
        $item = self::where('id',$id)->get()->toArray();
        if(count($item)>0){
            return $item[0]['rural'];
        }
        throw new ModelNotFoundException();
    }
    public function scopeFilteredByDistrict($query, $district_id)
    {
        return $query->where('district_id', $district_id);
    }

}
