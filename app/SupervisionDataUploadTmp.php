<?php

namespace App;

use \Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class SupervisionDataUploadTmp extends Model
{
    protected $fillable = [
		'supname',
		'cname',
		'county',
		'scname',
		'sub_county',
		'fname',
		'facility_name',
		'incharge',
		'mobile',
		'sev_cases',
		'sev_amoxdt',
		'sev_amoxsy',
		'sev_oxygen',
		'sev_ctx',
		'sev_benz',
		'sev_anti',
		'sev_other',
		'sev_notx',
		'pneu_cases',
		'pn_amox',
		'pn_amoxsy',
		'pn_oxygen',
		'pn_ctx',
		'pn_benz',
		'pn_gent',
		'pn_benzgent',
		'pn_anti',
		'pn_other',
		'pn_notx',
		'noc_cases',
		'noc_amox',
		'noc_amoxsy',
		'noc_oxygen',
		'noc_ctx',
		'noc_benz',
		'noc_gent',
		'noc_benzgent',
		'noc_anti',
		'noc_other',
		'noc_notx',
		'noclass_cases',
		'noclass_amox',
		'noclass_amoxsy',
		'noclass_oxygen',
		'noclass_ctx',
		'noclass_benz',
		'noclass_gent',
		'noclass_benzgent',
		'noclass_anti',
		'noclass_other',
		'noclass_notx',
		'd_shock_cases',
		'd_shock_cop',
		'd_shock_zinc',
		'd_shock_ors',
		'd_shock_iv',
		'd_shock_a',
		'd_shock_other',
		'd_shock_no',
		'd_sev_cases',
		'd_sev_cop',
		'd_sev_zinc',
		'd_sev_ors',
		'd_sev_iv',
		'd_sev_a',
		'd_sev_other',
		'd_sev_no',
		'd_some_cases',
		'd_some_cop',
		'd_some_zinc',
		'd_some_ors',
		'd_some_iv',
		'd_some_a',
		'd_some_other',
		'd_some_no',
		'd_nodehy_cases',
		'd_nodehy_cop',
		'd_nodehy_zinc',
		'd_nodehy_ors',
		'd_nodehy_iv',
		'd_nodehy_a',
		'd_nodehy_other',
		'd_nodehy_no',
		'd_dys_cases',
		'd_dys_cop',
		'd_dys_zinc',
		'd_dys_ors',
		'd_dys_iv',
		'd_dys_a',
		'd_dys_other',
		'd_dys_no',
		'd_noclass_cases',
		'd_noclass_cop',
		'd_noclass_zinc',
		'd_noclass_ors',
		'd_noclass_iv',
		'd_noclass_a',
		'd_noclass_other',
		'd_noclass_no',
		'upload_id',
		'assessment_type_id',
		'period'
    ];

    protected $columns = [
    	'id',
		'supname',
		'cname',
		'county',
		'scname',
		'sub_county',
		'fname',
		'facility_name',
		'incharge',
		'mobile',
		'sev_cases',
		'sev_amoxdt',
		'sev_amoxsy',
		'sev_oxygen',
		'sev_ctx',
		'sev_benz',
		'sev_anti',
		'sev_other',
		'sev_notx',
		'pneu_cases',
		'pn_amox',
		'pn_amoxsy',
		'pn_oxygen',
		'pn_ctx',
		'pn_benz',
		'pn_gent',
		'pn_benzgent',
		'pn_anti',
		'pn_other',
		'pn_notx',
		'noc_cases',
		'noc_amox',
		'noc_amoxsy',
		'noc_oxygen',
		'noc_ctx',
		'noc_benz',
		'noc_gent',
		'noc_benzgent',
		'noc_anti',
		'noc_other',
		'noc_notx',
		'noclass_cases',
		'noclass_amox',
		'noclass_amoxsy',
		'noclass_oxygen',
		'noclass_ctx',
		'noclass_benz',
		'noclass_gent',
		'noclass_benzgent',
		'noclass_anti',
		'noclass_other',
		'noclass_notx',
		'd_shock_cases',
		'd_shock_cop',
		'd_shock_zinc',
		'd_shock_ors',
		'd_shock_iv',
		'd_shock_a',
		'd_shock_other',
		'd_shock_no',
		'd_sev_cases',
		'd_sev_cop',
		'd_sev_zinc',
		'd_sev_ors',
		'd_sev_iv',
		'd_sev_a',
		'd_sev_other',
		'd_sev_no',
		'd_some_cases',
		'd_some_cop',
		'd_some_zinc',
		'd_some_ors',
		'd_some_iv',
		'd_some_a',
		'd_some_other',
		'd_some_no',
		'd_nodehy_cases',
		'd_nodehy_cop',
		'd_nodehy_zinc',
		'd_nodehy_ors',
		'd_nodehy_iv',
		'd_nodehy_a',
		'd_nodehy_other',
		'd_nodehy_no',
		'd_dys_cases',
		'd_dys_cop',
		'd_dys_zinc',
		'd_dys_ors',
		'd_dys_iv',
		'd_dys_a',
		'd_dys_other',
		'd_dys_no',
		'd_noclass_cases',
		'd_noclass_cop',
		'd_noclass_zinc',
		'd_noclass_ors',
		'd_noclass_iv',
		'd_noclass_a',
		'd_noclass_other',
		'd_noclass_no',
		'upload_id',
		'assessment_type_id',
		'period',
		'created_at',
		'updated_at'];

    public function getSubmissionDateAttr($value){
    	return Carbon::parse($value)->format('Y-m-d');
    }

	public function scopeExclude($query,$value = array()) 
	{
	return $query->select( array_diff( $this->columns,(array) $value) );
	}
}
