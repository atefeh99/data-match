<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Amar extends Model
{
    protected $table = "amar";
    public $timestamps = false;
    protected $fillable = [
        'is_matched',
    ];
    protected $primaryKey = 'fid';
    public static function index($rural_id, $is_matched)
    {

        $villages['data'] = self::rural($rural_id)
            ->isMatched($is_matched)
            ->orderBy('name')
            ->get(['name','fid as id', 'lat', 'long']);
        if (empty($villages)) throw new ModelNotFoundException();
        $villages['count'] = $villages['data']->count();
        return $villages;
    }

    public static function isMatchedUpdate($id, $value)
    {
        $item = self::where('fid', $id)->firstOrFail();

        $item->update(['is_matched' => $value]);
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
