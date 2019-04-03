<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountySupervisionToTmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervision_data_upload_tmps', function (Blueprint $table) {
            $table->integer("upload_id");
            $table->integer("assessment_type_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supervision_data_upload_tmps', function (Blueprint $table) {
            $table->dropColumn(["upload_id", "assessment_type_id"]);
        });
    }
}
