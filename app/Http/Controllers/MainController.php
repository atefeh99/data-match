<?php

namespace App\Http\Controllers;


//use App\Http\Controllers\RulesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\MainService;
use Illuminate\Support\MessageBag;
use App\Helpers\OdataQueryParser;


class MainController extends ApiController
{
    use RulesTrait;

    public function filter($request)
    {
        $validate = Validator::make($request->all(), [
            '$filter' => 'string|required'

        ]);
        if ($validate->fails()) {
            return $this->respondInvalidParams('1000', $validate->errors(), 'bad request');
        }

        $odata_query = \App\Helpers\OdataQueryParser::parse($request->fullUrl());
        if (OdataQueryParser::isFailed()) {
            return $this->respondInvalidParams('1001', OdataQueryParser::getErrors(), 'bad request');
        }

        $province_id = null;
        $county_id = null;
        $district_id = null;
        $rural_id = null;
        $dataset_name = null;
        $amar_id = null;
        $post_id = null;
        $vk_id = null;
//        if (isset($odata_query['skip'])) {
//            $validate = Validator::make(['skip' => $odata_query['skip']], [
//                'skip' => 'integer|required'
//            ]);
//            if ($validate->fails()) {
//                return $filter_rules->respondInvalidParams('1021', $validate->errors(), 'bad request');
//            }
//            $skip = $odata_query['skip'];
//        } else {
//            $skip = env('DEFAULT_SKIP');
//        }
//
//        if (isset($odata_query['top'])) {
//            $validate = Validator::make(['top' => $odata_query['top']], [
//                'top' => 'integer|required|lte:20'
//            ]);
//            if ($validate->fails()) {
//                return $filter_rules->respondInvalidParams('1022', $validate->errors(), 'bad request');
//            }
//            $take = $odata_query['top'];
//        } else {
//            $take = env('DEFAULT_TOP');
//        }
//        if (isset($odata_query['orderBy'])) {
//            $validate = Validator::make(['orderBy' => $odata_query['orderBy']], [
//                'orderBy' => 'required'
//            ]);
//            if ($validate->fails()) {
//                return $filter_rules->respondInvalidParams('1025', $validate->errors(), 'bad request');
//            }
//            $sort = true;
//        } else {
//            $sort = env('SORTED');
//        }

        if (isset($odata_query['filter'])) {
            foreach ($odata_query['filter'] as $item) {
                if ($item['left'] == 'province_id' && $item['operator'] == '=') {
                    $validate = Validator::make(['province_id' => (float)$item['right']], [
                        'province_id' => 'numeric|between:1,38'
                    ]);
                    dd($item['right']);
                    if ($validate->fails()) {
                        return $this->respondInvalidParams('1002', $validate->errors(), 'bad request');
                    }
                    $province_id = (float)$item['right'];


                }

                if ($item['left'] == 'county_id' && $item['operator'] == '=') {
                    $validate = Validator::make(['county_id' => (float)$item['right']], [
                        'county_id' => 'numeric|between:1,443'
                    ]);
                    if ($validate->fails()) {
                        return $this->respondInvalidParams('1003', $validate->errors(), 'bad request');
                    }
                    $county_id = (float)$item['right'];
                }

                if ($item['left'] == 'district_id' && $item['operator'] == '=') {
                    $validate = Validator::make(['district_id' => (float)$item['right']], [
                        'district_id' => 'numeric|between:1,653'
                    ]);
                    if ($validate->fails()) {
                        return $this->respondInvalidParams('1004', $validate->errors(), 'bad request');
                    }
                    $district_id = $item['right'];
                }

                if ($item['left'] == 'rural_id' && $item['operator'] == '=') {
                    $validate = Validator::make(['rural_id' => (float)$item['right']], [
                        'rural_id' => 'numeric|between:1,2643'
                    ]);
                    if ($validate->fails()) {
                        return $this->respondInvalidParams('1005', $validate->errors(), 'bad request');
                    }
                    $rural_id = $item['right'];
                }
                if ($item['left'] == 'dataset_name' && $item['operator'] == '=') {
                    $validate = Validator::make(['dataset_name' => (float)$item['right']], [
                        'dataset_name' => 'required'
                    ]);
//                    |in:' . implode(',', ['post', 'vk', 'amar'])
                    if ($validate->fails()) {
                        return $this->respondInvalidParams('1006', $validate->errors(), 'bad request');
                    }
                    $dataset_name = $item['right'];
                }
                if ($item['left'] == 'post_id' && $item['operator'] == '=') {
                    $validate = Validator::make(['post_id' => (float)$item['right']], [
                        'post_id' => 'numeric|between:1,146571'
                    ]);
                    if ($validate->fails()) {
                        return $this->respondInvalidParams('1007', $validate->errors(), 'bad request');
                    }
                    $post_id = $item['right'];
                }
                if ($item['left'] == 'vk_id' && $item['operator'] == '=') {
                    $validate = Validator::make(['vk_id' => (float)$item['right']], [
                        'vk_id' => 'numeric|between:1,8268'
                    ]);
                    if ($validate->fails()) {
                        return $this->respondInvalidParams('1008', $validate->errors(), 'bad request');
                    }
                    $vk_id = $item['right'];
                }
                if ($item['left'] == 'amar_id' && $item['operator'] == '=') {
                    $validate = Validator::make(['amar_id' => (float)$item['right']], [
                        'amar_id' => 'numeric'
                    ]);
//                    |between:46086,47267
                    if ($validate->fails()) {
                        return $this->respondInvalidParams('1009', $validate->errors(), 'bad request');
                    }
                    $amar_id = $item['right'];
                }
            }
        }
        return [
            'province_id' => $province_id,
            'county_id' => $county_id,
            'district_id' => $district_id,
            'rural_id' => $rural_id,
            'dataset_name' => $dataset_name,
            'post_id' => $post_id,
            'vk_id' => $vk_id,
            'amar_id' => $amar_id];
    }


    public function adminList(Request $request, $administrative_level)
    {
        $result = null;

        if ($administrative_level != 'provinces') {
            $input = $this->filter($request);
            if (!(is_object($input))) {
                $province_id = $input['province_id'];
                $county_id = $input['county_id'];
                $district_id = $input['district_id'];
                $fields = new MessageBag();
                if ($administrative_level == 'counties') {
                    if ($province_id) {
                        $result = MainService::adminList($administrative_level, $province_id, null, null);
                    } else {
                        $fields->add('province_id', trans('messages.custom.province_id'));
                        return $this->respondInvalidParams('2000', $fields, 'bad request');
                    }
                } elseif ($administrative_level == 'districts') {
                    if ($county_id) {
                        $result = MainService::adminList($administrative_level, $province_id, $county_id, null);
                    } else {
                        $fields->add('county_id', trans('messages.custom.county_id'));
                        return $this->respondInvalidParams('2001', $fields, 'bad request');
                    }
                } elseif ($administrative_level == 'rurals') {
                    if ($district_id) {
                        $result = MainService::adminList($administrative_level, $province_id, $county_id, $district_id);
                    } else {
                        $fields->add('district_id', trans('messages.custom.district_id'));
                        return $this->respondInvalidParams('2002', $fields, 'bad request');
                    }
                }
            } else {
                return $input;
            }
        } else {
            $result = MainService::adminList($administrative_level, null, null, null);
        }
        if ($result['data']) {
            return $this->respondArrayResult($result['data'], $result['count']);
        } else {
            return $this->respondNoFound('not found', 2003);
        }


    }

    public function notMatchedVillages(Request $request)
    {
        $input = $this->filter($request);
        $fields = new MessageBag();

        if (!(is_object($input))) {
            $rural_id = $input['rural_id'];
            $dataset_name = $input['dataset_name'];
            if ($rural_id and $dataset_name) {
                $result = MainService::notMatchedVillages($rural_id, $dataset_name);
                if ($result['data']) {
                    return $this->respondArrayResult($result['data'], $result['count']);
                } else {
                    return $this->respondNoFound('not found', 3000);
                }
            } else {
                $fields->add('rural_id&dataset', trans('messages.custom.rural_dataset'));
                return $this->respondInvalidParams('3001', $fields, 'bad request');
            }

        } else {
            return $input;
        }
    }

    public function storeMatchedVillages(Request $request)
    {
        if ($request->has(['amar_id', 'post_id', 'vk_id'])) {
            $data = self::checkRules($request, __FUNCTION__, 4000);
            if (MainService::storeMatchedVillages($data)) {
                return $this->respondSuccessCreate(null, trans('messages.custom.matched'));
            } else {
                return $this->respondError(trans('messages.custom.recordExists'), 422, 4001);
            }

        } else {
            return $this->respondInvalidParams(4001, null, trans('messages.custom.db_id'));
        }


    }

    public function getMatchedVillages()
    {
        $result = MainService::getMatchedVillages();
        if ($result['data']) {
            return $this->respondArrayResult($result['data'], $result['count']);
        } else {
            return $this->respondNoFound('not found', 5000);
        }
    }

    public function deleteMatchedVillages(Request $request, $id)
    {
        $input = $this->filter($request);
        if (!(is_object($input))) {
            $data['amar_id'] = $input['amar_id'];
            $data['vk_id'] = $input['vk_id'];
            $data['post_id'] = $input['post_id'];
            $res = MainService::deleteMatchedVillages($id, $data);
            if ($res) {
                return $this->respondSuccessDelete($id, trans('messages.custom.delete'));
            } else {
                return $this->respondNoFound('not found', 6000);
            }
        } else {
            return $input;
        }

    }

}
