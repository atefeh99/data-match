<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Post extends Model
{

    protected $table = 'post';
    protected $connection = "datamatch";
    protected $hidden =[ 'province_id', 'district_id','county_id'];


    public $timestamps = false;
    protected $fillable = [
        'is_matched',
    ];

    public static function index($status, $rural_id, $is_matched)
    {


        $villages['data'] = self::
//        status($status)->
            rural($rural_id)
            ->isMatched($is_matched)
            ->get(['name', 'id']);
        if (empty($villages)) throw new ModelNotFoundException();
        $villages['count'] = $villages['data']->count();
        return $villages;
    }
public static function getData($ids)
{
    $item = self::whereIn('id',$ids)->get()->toArray();
    if(count($item)>0){
        return $item[0];
    }
    throw new ModelNotFoundException();


}


    public static function isMatchedUpdate($id, $value)
    {
        $item = self::findOrFail($id);
        $item->update(['is_matched' => $value]);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRural($query, $rural_id)
    {
        return $query->where('rural_id', $rural_id);
    }

    public function scopeIsMatched($query, $is_matched)
    {
        return $query->where('is_matched', $is_matched);
    }
    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function county()
    {
        return $this->hasOne(County::class, 'id', 'county_id');

    }
    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id');

    }
}
