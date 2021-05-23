<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\RequestRulesException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

trait RulesTrait
{

    protected static $process_filters = [
        'state',
        'date',
        'province'
    ];


    public static function rules()
    {
        return [
            MainController::class => [
                'storeMatchedVillages' => [
                    'post_id' => 'numeric|required',
                    'vk_id' => 'numeric|required',
                    'amar_id' => 'numeric|required'


                ],

            ]
        ];
    }

    public static function checkRules($data, $function, $code)
    {
        $controller = __CLASS__;
        if (is_object($data)) {
            $validation = Validator::make(
                $data->all(),
                self::rules()[$controller][$function]
            );
        } else {
            $validation = Validator::make(
                $data,
                self::rules()[$controller][$function]
            );
        }
        if ($validation->fails()) {
            new RequestRulesException($validation->errors()->getMessages(), $code);
        }
        try {
            return $validation->validated();
        } catch (ValidationException $e) {
        }
    }
}
