<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDataTypeTmps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervision_data_upload_tmps', function (Blueprint $table) {
            // $table->string('sev_cases')->change();
            // $table->string('sev_amoxdt')->change();
            // $table->string('sev_amoxsy')->change();
            // $table->string('sev_oxygen')->change();
            // $table->string('sev_ctx')->change();
            // $table->string('sev_benz')->change();
            $table->string('sev_benzgent')->change();
            // $table->string('sev_anti')->change();
            // $table->string('sev_other')->change();
            // $table->string('sev_notx')->change();
            // $table->string('pneu_cases')->change();
            // $table->string('pn_amox')->change();
            // $table->string('pn_amoxsy')->change();
            // $table->string('pn_oxygen')->change();
            // $table->string('pn_ctx')->change();
            // $table->string('pn_benz')->change();
            // $table->string('pn_gent')->change();
            // $table->string('pn_benzgent')->change();
            // $table->string('pn_anti')->change();
            // $table->string('pn_other')->change();
            // $table->string('pn_notx')->change();
            // $table->string('noc_cases')->change();
            // $table->string('noc_amox')->change();
            // $table->string('noc_amoxsy')->change();
            // $table->string('noc_oxygen')->change();
            // $table->string('noc_ctx')->change();
            // $table->string('noc_benz')->change();
            // $table->string('noc_gent')->change();
            // $table->string('noc_benzgent')->change();
            // $table->string('noc_anti')->change();
            // $table->string('noc_other')->change();
            // $table->string('noc_notx')->change();
            // $table->string('noclass_cases')->change();
            // $table->string('noclass_amox')->change();
            // $table->string('noclass_amoxsy')->change();
            // $table->string('noclass_oxygen')->change();
            // $table->string('noclass_ctx')->change();
            // $table->string('noclass_benz')->change();
            // $table->string('noclass_gent')->change();
            // $table->string('noclass_benzgent')->change();
            // $table->string('noclass_anti')->change();
            // $table->string('noclass_other')->change();
            // $table->string('noclass_notx')->change();
            // $table->string('d_shock_cases')->change();
            // $table->string('d_shock_cop')->change();
            // $table->string('d_shock_zinc')->change();
            // $table->string('d_shock_ors')->change();
            // $table->string('d_shock_iv')->change();
            // $table->string('d_shock_a')->change();
            // $table->string('d_shock_other')->change();
            // $table->string('d_shock_no')->change();
            // $table->string('d_sev_cases')->change();
            // $table->string('d_sev_cop')->change();
            // $table->string('d_sev_zinc')->change();
            // $table->string('d_sev_ors')->change();
            // $table->string('d_sev_iv')->change();
            // $table->string('d_sev_a')->change();
            // $table->string('d_sev_other')->change();
            // $table->string('d_sev_no')->change();
            // $table->string('d_some_cases')->change();
            // $table->string('d_some_cop')->change();
            // $table->string('d_some_zinc')->change();
            // $table->string('d_some_ors')->change();
            // $table->string('d_some_iv')->change();
            // $table->string('d_some_a')->change();
            // $table->string('d_some_other')->change();
            // $table->string('d_some_no')->change();
            // $table->string('d_nodehy_cases')->change();
            // $table->string('d_nodehy_cop')->change();
            // $table->string('d_nodehy_zinc')->change();
            // $table->string('d_nodehy_ors')->change();
            // $table->string('d_nodehy_iv')->change();
            // $table->string('d_nodehy_a')->change();
            // $table->string('d_nodehy_other')->change();
            // $table->string('d_nodehy_no')->change();
            // $table->string('d_dys_cases')->change();
            // $table->string('d_dys_cop')->change();
            // $table->string('d_dys_zinc')->change();
            // $table->string('d_dys_ors')->change();
            // $table->string('d_dys_iv')->change();
            // $table->string('d_dys_a')->change();
            // $table->string('d_dys_other')->change();
            // $table->string('d_dys_no')->change();
            // $table->string('d_noclass_cases')->change();
            // $table->string('d_noclass_cop')->change();
            // $table->string('d_noclass_zinc')->change();
            // $table->string('d_noclass_ors')->change();
            // $table->string('d_noclass_iv')->change();
            // $table->string('d_noclass_a')->change();
            // $table->string('d_noclass_other')->change();
            // $table->string('d_noclass_no')->change();
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
            // $table->integer('sev_cases')->change();
            // $table->integer('sev_amoxdt')->change();
            // $table->integer('sev_amoxsy')->change();
            // $table->integer('sev_oxygen')->change();
            // $table->integer('sev_ctx')->change();
            // $table->integer('sev_benz')->change();
            $table->integer('sev_benzgent')->change();
            // $table->integer('sev_anti')->change();
            // $table->integer('sev_other')->change();
            // $table->integer('sev_notx')->change();
            // $table->integer('pneu_cases')->change();
            // $table->integer('pn_amox')->change();
            // $table->integer('pn_amoxsy')->change();
            // $table->integer('pn_oxygen')->change();
            // $table->integer('pn_ctx')->change();
            // $table->integer('pn_benz')->change();
            // $table->integer('pn_gent')->change();
            // $table->integer('pn_benzgent')->change();
            // $table->integer('pn_anti')->change();
            // $table->integer('pn_other')->change();
            // $table->integer('pn_notx')->change();
            // $table->integer('noc_cases')->change();
            // $table->integer('noc_amox')->change();
            // $table->integer('noc_amoxsy')->change();
            // $table->integer('noc_oxygen')->change();
            // $table->integer('noc_ctx')->change();
            // $table->integer('noc_benz')->change();
            // $table->integer('noc_gent')->change();
            // $table->integer('noc_benzgent')->change();
            // $table->integer('noc_anti')->change();
            // $table->integer('noc_other')->change();
            // $table->integer('noc_notx')->change();
            // $table->integer('noclass_cases')->change();
            // $table->integer('noclass_amox')->change();
            // $table->integer('noclass_amoxsy')->change();
            // $table->integer('noclass_oxygen')->change();
            // $table->integer('noclass_ctx')->change();
            // $table->integer('noclass_benz')->change();
            // $table->integer('noclass_gent')->change();
            // $table->integer('noclass_benzgent')->change();
            // $table->integer('noclass_anti')->change();
            // $table->integer('noclass_other')->change();
            // $table->integer('noclass_notx')->change();
            // $table->integer('d_shock_cases')->change();
            // $table->integer('d_shock_cop')->change();
            // $table->integer('d_shock_zinc')->change();
            // $table->integer('d_shock_ors')->change();
            // $table->integer('d_shock_iv')->change();
            // $table->integer('d_shock_a')->change();
            // $table->integer('d_shock_other')->change();
            // $table->integer('d_shock_no')->change();
            // $table->integer('d_sev_cases')->change();
            // $table->integer('d_sev_cop')->change();
            // $table->integer('d_sev_zinc')->change();
            // $table->integer('d_sev_ors')->change();
            // $table->integer('d_sev_iv')->change();
            // $table->integer('d_sev_a')->change();
            // $table->integer('d_sev_other')->change();
            // $table->integer('d_sev_no')->change();
            // $table->integer('d_some_cases')->change();
            // $table->integer('d_some_cop')->change();
            // $table->integer('d_some_zinc')->change();
            // $table->integer('d_some_ors')->change();
            // $table->integer('d_some_iv')->change();
            // $table->integer('d_some_a')->change();
            // $table->integer('d_some_other')->change();
            // $table->integer('d_some_no')->change();
            // $table->integer('d_nodehy_cases')->change();
            // $table->integer('d_nodehy_cop')->change();
            // $table->integer('d_nodehy_zinc')->change();
            // $table->integer('d_nodehy_ors')->change();
            // $table->integer('d_nodehy_iv')->change();
            // $table->integer('d_nodehy_a')->change();
            // $table->integer('d_nodehy_other')->change();
            // $table->integer('d_nodehy_no')->change();
            // $table->integer('d_dys_cases')->change();
            // $table->integer('d_dys_cop')->change();
            // $table->integer('d_dys_zinc')->change();
            // $table->integer('d_dys_ors')->change();
            // $table->integer('d_dys_iv')->change();
            // $table->integer('d_dys_a')->change();
            // $table->integer('d_dys_other')->change();
            // $table->integer('d_dys_no')->change();
            // $table->integer('d_noclass_cases')->change();
            // $table->integer('d_noclass_cop')->change();
            // $table->integer('d_noclass_zinc')->change();
            // $table->integer('d_noclass_ors')->change();
            // $table->integer('d_noclass_iv')->change();
            // $table->integer('d_noclass_a')->change();
            // $table->integer('d_noclass_other')->change();
            // $table->integer('d_noclass_no')->change();
        });
    }
}
