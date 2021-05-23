<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


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
        $villages['data'] = self::all('name, id, post_id, vk_id, amar_id, creation_date');
        if (empty($districts)) throw new ModelNotFoundException();
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

    public static function create(array $data): array
    {
        return self::create($data);
    }

    public function delete($id)
    {
        self:: destroy($id);
    }

}

