<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisionDataLegacy extends Model
{
	protected $table = 'supervision_data_legacy';
	
    protected $fillable = ['county',
							'sub_county',
							'facility_name',
							'facility_code',
							'sum_tx_ors_zn',
							'sum_tx_ors',
							'sum_tx_zn',
							'sum_tx_antibiotics',
							'sum_other',
							'sum_tx_none',
							'sum_no_dehydration',
							'sum_some_dehydration',
							'sum_sev_dehydration',
							'sum_shock',
							'sum_dysentry',
							'sum_no_class',
							'sum_tx_amox',
							'sum_tx_cotri',
							'sum_tx_benz',
							'sum_tx_genta',
							'sum_tx_chlor',
							'sum_tx_pn_other',
							'sum_tx_pn_none',
							'sum_no_pneu',
							'sum_pneu',
							'sum_sev_pneu',
							'sum_very_sev_dis',
							'sum_no_pn_class',
							'period',
							'upload_id',
							'assessment_type_id'];
}
