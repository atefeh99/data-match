<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};


class Village extends Model
{
    use Common;

    protected $table = 'villages';
    protected $hidden =[ 'created_at', 'updated_at'];

    protected $fillable = [
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
        $villages['data'] = self::get($out_fields);
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

    public static function joinVillageWithPost()
    {
        $query = self::with([
            'post',
            'post.province',
            'post.county',
            'post.district',
        ])->get()
            ->toArray();
//        dd($query);
        foreach ($query as $q) {
            $q = array_merge($q, $q['post']);
            unset($q['post']);
            $q['province'] = $q['province']['province'];
            $q['county'] = $q['county']['county'];
            $q['district'] = $q['district']['district'];

            dd($q);


        }
//        return

    }

    public function post()
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }


    public static function remove($id)
    {
        $item = self::findOrFail($id);
        $item->delete();
        return $item;
    }
}

