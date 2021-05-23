<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Amar extends Model
{
    protected $table = 'amar';

    public static function index()
    {
        $villages['data'] = self::get('name,fid as id, lat, long');
        if (empty($districts)) throw new ModelNotFoundException();
        $villages['count'] = $villages['data']->count();
        return $villages;
    }

    public static function isMatchedUpdate($id, $value)
    {
        self::where('fid', $id)
            ->update((['is_matched' => $value]));
    }
}
