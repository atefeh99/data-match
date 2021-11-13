<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};


class Village extends Model
{
    use Common;

    protected $table = 'villages';
    protected $connection = "default";

    protected $fillable = [
        'name',
        'vk_id',
        'amar_id',
        'post_id',
        'partnumber',
        'tournumber',
        'ostantitle',
        'bakhshtitle',
        'shahrestantitle',
        'dehestantitle',
        'roostatitle',
        'shahrtitle',
        'abadititle',
        'shahrroostaabadiid',
        'status',
        'province',
        'county',
        'district',
        'rural',
    ];

    public static function index()
    {
        $villages['data'] = self::all();
        if (empty($villages)) throw new ModelNotFoundException();
        $villages['count'] = $villages['data']->count();
        return $villages;
    }


    /**
     * @param array $data
     * @return array
     */
    public static function getVillageCount($post_id, $vk_id, $amar_id)
    {

        return self::where('post_id', $post_id)
            ->orWhere('vk_id', $vk_id)
            ->orWhere('amar_id', $amar_id)
            ->count();

    }

    public static function createItem($data)
    {
        return self::create($data);
    }

    public static function remove($id)
    {
        $item = self::findOrFail($id);
        $item->delete();
        return $item;
    }
}

