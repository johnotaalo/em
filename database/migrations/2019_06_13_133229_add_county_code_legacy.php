<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountyCodeLegacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervision_data_legacy', function (Blueprint $table) {
            $table->integer('county_id')->after('county')->nullable();
            $table->integer('sub_county_id')->after('sub_county')->nullable();
        });

        Schema::table('supervision_data_legacy_tmps', function (Blueprint $table) {
            $table->integer('county_id')->after('county')->nullable();
            $table->integer('sub_county_id')->after('sub_county')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supervision_data_legacy', function (Blueprint $table) {
            $table->dropColumn(['county_id', 'sub_county_id']);
        });

        Schema::table('supervision_data_legacy_tmps', function (Blueprint $table) {
            $table->dropColumn(['county_id', 'sub_county_id']);
        });
    }
}
