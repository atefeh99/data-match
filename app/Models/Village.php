<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};


class Village extends Model
{

    protected $table = 'villages';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'vk_id',
        'amar_id',
        'post_id',
    ];

    public static function index()
    {
        $villages['data'] = self::all(['name', 'id', 'post_id', 'vk_id', 'amar_id', 'creation_date']);
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

    public static function create($data): array
    {

        return self::create([
            'name'=>$data['name'],
            'post_id'=>$data['post_id'],
            'vk_id'=>$data['vk_id'],
            'amar_id'=>$data['amar_id'],
            'creation_date'=>$data['creation_date']
        ]);
    }

    public function remove($id)
    {
        self:: destroy($id);
    }

}

