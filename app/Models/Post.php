<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Post extends Model
{

    protected $table = 'post';
    public $timestamps = false;

    public static function index($status, $rural_id, $is_matched)
    {


        $villages['data'] = self::status($status)
            ->rural($rural_id)
            ->isMatched($is_matched)
            ->get(['name', 'id']);
        if (empty($villages)) throw new ModelNotFoundException();
        $villages['count'] = $villages['data']->count();
        return $villages;
    }



    public static function isMatchedUpdate($id, $value)
    {
        self::where('id', $id)
            ->update((['is_matched' => $value]));
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
}
