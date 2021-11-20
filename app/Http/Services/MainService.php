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
use App\Helpers\Date;
use Illuminate\Support\Arr;


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
            $query = Amar::index($rural_id, $is_matched);
        } elseif ($dataset_name === 'keshvar') {
            $query = Keshvar::index($status, $rural_id, $is_matched);
        }
        $data = $query['data'];
        $count = $query['count'];
        return ['data' => $data, 'count' => $count];
    }

    public static function storeMatchedVillages($data)
    {

        $count = Village::getVillageCount($data['post_id'], $data['vk_id'], $data['amar_id']);
        if ($count != 0) {
            return false;
        }

        $data['name'] = keshvar::getVillageName($data['vk_id']); //village_name


//        $data['creation_date'] = Date::convertCarbonToJalali(Carbon::now());


        Village::createItem($data);

        Post::isMatchedUpdate($data['post_id'], true);
        Keshvar::isMatchedUpdate($data['vk_id'], true);
        Amar::isMatchedUpdate($data['amar_id'], true);

        return true;

    }

    public static function getMatchedVillages()
    {
        $out_fields = ['name',
            'vk_id',
            'amar_id',
            'post_id',];
        $query = Village::index($out_fields);
        $data = $query['data'];
        $count = $query['count'];
        return ['data' => $data, 'count' => $count];
    }

    public static function deleteMatchedVillages($id, $data)
    {
        $item = Village::remove($id);
        if ($item) {
            Post::isMatchedUpdate($data['post_id'], false);
            Keshvar::isMatchedUpdate($data['vk_id'], false);
            Amar::isMatchedUpdate($data['amar_id'], false);
            return true;
        } else {
            return false;
        }
    }

    public static function export()
    {

        $matched = Village::joinVillageWithPost();

        dd(
          $matched
        );
    }

    public static function addPostData()
    {
//        $data_attach = Post::getData();

        if (isset($data_attach['province_id'])) {
            $data_attach['province'] = Province::getOne($data_attach['province_id']);
            unset($data_attach['province_id']);
        }
        if (isset($data_attach['county_id'])) {
            $data_attach['county'] = County::getOne($data_attach['county_id']);
            unset($data_attach['county_id']);
        }
        if (isset($data_attach['district_id'])) {
            $data_attach['district'] = district::getOne($data_attach['district_id']);
            unset($data_attach['district_id']);
        }
        if (isset($data_attach['rural_id'])) {
            $data_attach['rural'] = rural::getOne($data_attach['rural_id']);
            unset($data_attach['rural_id']);
        }
        unset($data_attach['is_matched']);
        unset($data_attach['id']);
        return $data_attach;
//
    }
}
