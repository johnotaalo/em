<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityUploadTmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_upload_tmps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('COUNTY');
            $table->string('COUNTY_ID');
            $table->string('SUB_COUNTY');
            $table->string('SUB_COUNTY_ID');
            $table->string('FACILITY_NAME');
            $table->string('FACILITY_TYPE')->nullable();
            $table->string('STATUS');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facility_upload_tmps');
    }
}
