<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNewVillagesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS villages_view');

        DB::connection('pgsql')->statement('CREATE MATERIALIZED VIEW villages_view
                    AS
                    SELECT villages.name,villages.post_id,villages.vk_id,villages.amar_id,
                    post.province_name, post.county_name,post.district_name,rural_name,post.type
                    from villages
                    JOIN post on villages.post_id=post.id

    ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS villages_view');
    }
}
