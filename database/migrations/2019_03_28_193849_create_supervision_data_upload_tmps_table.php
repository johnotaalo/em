<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisionDataUploadTmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervision_data_upload_tmps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('SubmissionDate');
            $table->string('datesup');
            $table->string('supname');
            $table->string('cname');
            $table->string('county');
            $table->string('scname');
            $table->string('sub_county');
            $table->string('fname');
            $table->string('facility_name');
            $table->string('incharge');
            $table->string('mobile');
            $table->integer('sev_cases')->nullable();
            $table->integer('sev_amoxdt')->nullable();
            $table->integer('sev_amoxsy')->nullable();
            $table->integer('sev_oxygen')->nullable();
            $table->integer('sev_ctx')->nullable();
            $table->integer('sev_benz')->nullable();
            $table->integer('sev_anti')->nullable();
            $table->integer('sev_other')->nullable();
            $table->integer('sev_notx')->nullable();
            $table->integer('pneu_cases')->nullable();
            $table->integer('pn_amox')->nullable();
            $table->integer('pn_amoxsy')->nullable();
            $table->integer('pn_oxygen')->nullable();
            $table->integer('pn_ctx')->nullable();
            $table->integer('pn_benz')->nullable();
            $table->integer('pn_gent')->nullable();
            $table->integer('pn_benzgent')->nullable();
            $table->integer('pn_anti')->nullable();
            $table->integer('pn_other')->nullable();
            $table->integer('pn_notx')->nullable();
            $table->integer('noc_cases')->nullable();
            $table->integer('noc_amox')->nullable();
            $table->integer('noc_amoxsy')->nullable();
            $table->integer('noc_oxygen')->nullable();
            $table->integer('noc_ctx')->nullable();
            $table->integer('noc_benz')->nullable();
            $table->integer('noc_gent')->nullable();
            $table->integer('noc_benzgent')->nullable();
            $table->integer('noc_anti')->nullable();
            $table->integer('noc_other')->nullable();
            $table->integer('noc_notx')->nullable();
            $table->integer('noclass_cases')->nullable();
            $table->integer('noclass_amox')->nullable();
            $table->integer('noclass_amoxsy')->nullable();
            $table->integer('noclass_oxygen')->nullable();
            $table->integer('noclass_ctx')->nullable();
            $table->integer('noclass_benz')->nullable();
            $table->integer('noclass_gent')->nullable();
            $table->integer('noclass_benzgent')->nullable();
            $table->integer('noclass_anti')->nullable();
            $table->integer('noclass_other')->nullable();
            $table->integer('noclass_notx')->nullable();
            $table->integer('d_shock_cases')->nullable();
            $table->integer('d_shock_cop')->nullable();
            $table->integer('d_shock_zinc')->nullable();
            $table->integer('d_shock_ors')->nullable();
            $table->integer('d_shock_iv')->nullable();
            $table->integer('d_shock_a')->nullable();
            $table->integer('d_shock_other')->nullable();
            $table->integer('d_shock_no')->nullable();
            $table->integer('d_sev_cases')->nullable();
            $table->integer('d_sev_cop')->nullable();
            $table->integer('d_sev_zinc')->nullable();
            $table->integer('d_sev_ors')->nullable();
            $table->integer('d_sev_iv')->nullable();
            $table->integer('d_sev_a')->nullable();
            $table->integer('d_sev_other')->nullable();
            $table->integer('d_sev_no')->nullable();
            $table->integer('d_some_cases')->nullable();
            $table->integer('d_some_cop')->nullable();
            $table->integer('d_some_zinc')->nullable();
            $table->integer('d_some_ors')->nullable();
            $table->integer('d_some_iv')->nullable();
            $table->integer('d_some_a')->nullable();
            $table->integer('d_some_other')->nullable();
            $table->integer('d_some_no')->nullable();
            $table->integer('d_nodehy_cases')->nullable();
            $table->integer('d_nodehy_cop')->nullable();
            $table->integer('d_nodehy_zinc')->nullable();
            $table->integer('d_nodehy_ors')->nullable();
            $table->integer('d_nodehy_iv')->nullable();
            $table->integer('d_nodehy_a')->nullable();
            $table->integer('d_nodehy_other')->nullable();
            $table->integer('d_nodehy_no')->nullable();
            $table->integer('d_dys_cases')->nullable();
            $table->integer('d_dys_cop')->nullable();
            $table->integer('d_dys_zinc')->nullable();
            $table->integer('d_dys_ors')->nullable();
            $table->integer('d_dys_iv')->nullable();
            $table->integer('d_dys_a')->nullable();
            $table->integer('d_dys_other')->nullable();
            $table->integer('d_dys_no')->nullable();
            $table->integer('d_noclass_cases')->nullable();
            $table->integer('d_noclass_cop')->nullable();
            $table->integer('d_noclass_zinc')->nullable();
            $table->integer('d_noclass_ors')->nullable();
            $table->integer('d_noclass_iv')->nullable();
            $table->integer('d_noclass_a')->nullable();
            $table->integer('d_noclass_other')->nullable();
            $table->integer('d_noclass_no')->nullable();
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
        Schema::dropIfExists('supervision_data_upload_tmps');
    }
}
