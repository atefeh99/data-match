<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};


class Province extends Model
{
    protected $table = "dims.province";

    public static function index()
    {
        $provinces['data'] = self::all('province as name', 'id');
        if (empty($provinces)) throw new ModelNotFoundException();
        $provinces['count'] = $provinces['data']->count();
        return $provinces;
    }
}
