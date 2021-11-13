<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};

class Keshvar extends Model
{
    protected $table = 'keshvar';
    protected $connection = "pgsql";

    public $timestamps = false;
    protected $fillable = [
        'is_matched',
    ];

    public static function index($status, $rural_id, $is_matched)
    {

        $villages['data'] = self::kind($status)
            ->rural($rural_id)
            ->isMatched($is_matched)
            ->get(['name_ok as name', 'id', 'shenas_meli']);
        if (empty($villages)) throw new ModelNotFoundException();
        $villages['count'] = $villages['data']->count();
        return $villages;
    }
    public static function getVillageName($id)
    {
        $village = self::findOrFail($id);
        return $village->name_ok;
    }

    public static function isMatchedUpdate($id, $value)
    {
        $item = self::findOrFail($id);
        $item->update(['is_matched' => $value]);

    }

    public function scopeKind($query, $status)
    {
        return $query->where('kind', $status);
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
