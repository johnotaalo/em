<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSevBenzGentColumnSupervision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervision_data', function (Blueprint $table) {
            $table->integer('sev_benzgent')->after('sev_gent')->nullable();
        });

        Schema::table('supervision_data_upload_tmps', function (Blueprint $table) {
            $table->integer('sev_gent')->after('sev_benz')->nullable();
            $table->integer('sev_benzgent')->after('sev_gent')->nullable();
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
            $table->dropColumn('sev_benzgent');
        });

        Schema::table('supervision_data_upload_tmps', function (Blueprint $table) {
            $table->dropColumn(['sev_benz', 'sev_benzgent']);
        });
    }
}
