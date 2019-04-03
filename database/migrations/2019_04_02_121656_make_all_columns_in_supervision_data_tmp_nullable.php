<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeAllColumnsInSupervisionDataTmpNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervision_data_upload_tmps', function (Blueprint $table) {
            $table->string('SubmissionDate')->nullable()->change();
            $table->string('datesup')->nullable()->change();
            $table->string('supname')->nullable()->change();
            $table->string('cname')->nullable()->change();
            $table->string('county')->nullable()->change();
            $table->string('scname')->nullable()->change();
            $table->string('sub_county')->nullable()->change();
            $table->string('fname')->nullable()->change();
            $table->string('facility_name')->nullable()->change();
            $table->string('incharge')->nullable()->change();
            $table->string('mobile')->nullable()->change();
            $table->dropColumn(['SubmissionDate', 'datesup']);
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
            $table->string('SubmissionDate')->nullable(false)->change();
            $table->string('datesup')->nullable(false)->change();
            $table->string('supname')->nullable(false)->change();
            $table->string('cname')->nullable(false)->change();
            $table->string('county')->nullable(false)->change();
            $table->string('scname')->nullable(false)->change();
            $table->string('sub_county')->nullable(false)->change();
            $table->string('fname')->nullable(false)->change();
            $table->string('facility_name')->nullable(false)->change();
            $table->string('incharge')->nullable(false)->change();
            $table->string('mobile')->nullable(false)->change();
            $table->string('SubmissionDate');
            $table->string('datesup');
        });
    }
}
