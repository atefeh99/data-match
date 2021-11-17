<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVillagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('villages', function (Blueprint $table) {
            $table->string('partnumber')->nullable();
            $table->string('tournumber')->nullable();
            $table->string('ostantitle')->nullable();
            $table->string('bakhshtitle')->nullable();
            $table->string('shahrestantitle')->nullable();
            $table->string('dehestantitle')->nullable();
            $table->string('roostatitle')->nullable();
            $table->string('shahrtitle')->nullable();
            $table->string('abadititle')->nullable();
            $table->string('shahrroostaabadiid')->nullable();
            $table->string('status')->nullable();
            $table->string('province')->nullable();
            $table->string('county')->nullable();
            $table->string('district')->nullable();
            $table->string('rural')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn('partnumber');
            $table->dropColumn('tournumber');
            $table->dropColumn('ostantitle');
            $table->dropColumn('bakhshtitle');
            $table->dropColumn('shahrestantitle');
            $table->dropColumn('dehestantitle');
            $table->dropColumn('roostatitle');
            $table->dropColumn('shahrtitle');
            $table->dropColumn('abadititle');
            $table->dropColumn('shahrroostaabadiid');
            $table->dropColumn('status');
            $table->dropColumn('province');
            $table->dropColumn('county');
            $table->dropColumn('district');
            $table->dropColumn('rural');

        });
    }
}
