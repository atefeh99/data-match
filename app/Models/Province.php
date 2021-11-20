<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};


class Province extends Model
{
    protected $table = "dims.province";
    protected $connection = "datamatch";



    public static function index()
    {
        $provinces['data'] = self::all('province as name', 'id');
        if (empty($provinces)) throw new ModelNotFoundException();
        $provinces['count'] = $provinces['data']->count();
        return $provinces;
    }
    public static function getOne($id)
    {
        $item = self::where('id',$id)->get()->toArray();
        if(count($item)>0){

            return $item[0]['province'];
        }
        throw new ModelNotFoundException();
    }
}
