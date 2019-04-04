<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSupervisionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervision_data', function (Blueprint $table) {
            $table->dropColumn(["supervision_no", "SubmissionDate", "datesup"]);
            $table->increments('id')->first();
            $table->renameColumn('`facility name`', 'facility_name');
            $table->renameColumn('`sub county`', 'sub_county');
            $table->integer('assessment_type_id');
            $table->string("period");
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
        Schema::table('supervision_data', function (Blueprint $table) {
            $table->dropColumn(["period", "id", "created_at", "updated_at", "assessment_type_id"]);
            $table->integer("supervision_no")->nullable();
            $table->date("SubmissionDate")->nullable();
            $table->date("datesup")->nullable();
            $table->renameColumn('facility_name', '`facility name`');
            $table->renameColumn('sub_county', '`sub county`');
        });
    }
}
