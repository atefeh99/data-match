<?php

namespace App\Http\Services;

use App\Models\{
    County,
    District,
    Province,
    Rural,
    Post,
    Amar,
    Keshvar
};
use Carbon\Carbon;
use App\Models\Village;
use App\Helper\Date;


class MainService
{
    public static function adminList($administrative_level, $province_id, $county_id, $district_id)
    {
        $query = null;

        if ($administrative_level == 'provinces') {

            $query = Province::index();
        } elseif ($administrative_level == 'counties') {
            $query = County::index($province_id);
        } elseif ($administrative_level == 'districts') {
            $query = District::index($county_id);
        } elseif ($administrative_level == 'rurals') {
            $query = Rural::index($district_id);
        }

        $data = $query['data'];
        $count = $query['count'];

        return ['data' => $data, 'count' => $count];

    }

    public static function notMatchedVillages($rural_id, $dataset_name)
    {
        $query = null;
        $status = 'روستا';
        $is_matched = false;
        if ($dataset_name === 'post') {
            $query = Post::index($status, $rural_id, $is_matched);
        } elseif ($dataset_name === 'amar') {
            $query = Amar::index();
        } elseif ($dataset_name === 'keshvar') {
            $query = Keshvar::index($status, $rural_id, $is_matched);
        }
        $data = $query['data'];
        $count = $query['count'];
        return ['data' => $data, 'count' => $count];
    }

    public static function storeMatchedVillages($data)
    {
        $count = Village::getVillageCount();
        if ($count != 0) {
            return false;
        }
        $data['name'] = Post::getVillageName($data['post_id']); //village_name
        $data['creation_data'] = Date::convertCarbonToJalali(Carbon::now());
        Village::create($data);
        Post::isMatchedUpdate($data['post_id'], true);
        Keshvar::isMatchedUpdate($data['vk_id'], true);
        Amar::isMatchedUpdate($data['amar_id'], true);
        return true;

    }

    public static function getMatchedVillages()
    {
        $query = Village::index();
        $data = $query['data'];
        $count = $query['count'];
        return ['data' => $data, 'count' => $count];
    }

    public static function deleteMatchedVillages($id, $data)
    {
        $item = Village::delete($id);
        if ($item) {
            Post::isMatchedUpdate($data['post_id'], false);
            Keshvar::isMatchedUpdate($data['vk_id'], false);
            Amar::isMatchedUpdate($data['amar_id'], false);

            return true;
        } else {
            return false;
        }
    }
}
