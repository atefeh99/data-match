<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};


class Village extends Model
{
    use Common;

    protected $table = 'villages';
    protected $connection = "pgsql";

    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'vk_id',
        'amar_id',
        'post_id',

    ];

    public static function index($out_fields = ['name',
        'vk_id',
        'amar_id',
        'post_id',


    ])
    {
        $villages['data'] = self::select($out_fields)
            ->leftjoin('post', 'post_id', '=', 'post.id')
            ->leftjoin('keshvar', 'vk_id', '=', 'keshvar.id')
            ->leftjoin('amar', 'amar_id', '=', 'amar.id')
            ->orderBy('villages.name')
            ->get()
            ->toArray();
        if (empty($villages)) throw new ModelNotFoundException();
        $villages['count'] = count($villages['data']);
        return $villages;
    }


    /**
     * @param array $data
     * @return array
     */
    public static function getVillageCount($post_id, $vk_id, $amar_id)
    {
        if(!empty($vk_id)) {
            return self::where('post_id', $post_id)
                ->orWhere('vk_id', $vk_id)
                ->orWhere('amar_id', $amar_id)
                ->count();
        }else{
            return self::where('post_id', $post_id)
                ->orWhere('amar_id', $amar_id)
                ->count();
        }




    }

    public static function createItem($data)
    {
        $item = self::create($data);
        return $item;
    }

    public static function remove($id)
    {
        $item = self::findOrFail($id);
        $item->delete();
        return $item;
    }
}

