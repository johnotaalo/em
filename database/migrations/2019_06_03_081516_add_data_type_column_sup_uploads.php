<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataTypeColumnSupUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervision_uploads', function (Blueprint $table) {
            $table->boolean('is_legacy')->after('path')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supervision_uploads', function (Blueprint $table) {
            $table->dropColumn('is_legacy');
        });
    }
}
