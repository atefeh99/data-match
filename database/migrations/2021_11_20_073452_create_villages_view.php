<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateVillagesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('pgsql')->statement('CREATE MATERIALIZED VIEW villages_view
                    AS
                    SELECT villages.name,villages.post_id,villages.vk_id,villages.amar_id,
                    post.partnumber,post.tournumber, post.ostantitle, post.bakhshtitle,post.shahrestantitle,
                    post.dehestantitle,post.roostatitle,post.shahrtitle,post.abadititle,post.shahrroostaabadiid,post.status
                   from villages
                    JOIN post on villages.post_id=post.id

    ');
    }
//
//
//JOIN dims.county on post.county_id=dims.county.id
//JOIN dims.district on post.district_id=dims.district.id
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP MATERIALIZED VIEW villages_view");    }
}
