<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VillageView extends model
{
    public static function refreshView()
    {
        DB::statemnent('refresh materialized view villages_view');
    }


}
