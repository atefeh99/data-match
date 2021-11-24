<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model,  ModelNotFoundException};

class  County extends Model
{
    protected $table = "dims.county";
//    protected $connection = "datamatch";


    public static function index($province_id)
    {
        $counties['data'] = self::FilteredByProvince($province_id)->get(['county as name','id']);
        if (empty($counties)) throw new ModelNotFoundException();
        $counties['count'] = $counties['data']->count();
        return $counties;
    }
    public static function getOne($id)
    {
        $item = self::where('id',$id)->get()->toArray();
        if(count($item)>0){
            return $item[0]['county'];
        }
        throw new ModelNotFoundException();
    }

    public function scopeFilteredByProvince($query, $province_id)
    {
        return $query->where('province_id', $province_id);
    }
}
