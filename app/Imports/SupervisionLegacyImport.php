<?php

namespace App\Imports;

use App\SupervisionDataLegacyTmp;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupervisionLegacyImport implements ToModel, WithHeadingRow
{
   	protected $upload_id;
    protected $assessment_type_id;
    protected $period;

    public function __construct($upload_id, $assessment_type_id, $period){
        $this->upload_id = $upload_id;
        $this->assessment_type_id = $assessment_type_id;
        $this->period = $period;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        $treatmentandCases = ['county',
    						'county_id',
							'sub_county',
							'sub_county_id',
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
		$row['period'] = $this->period;
		$row['upload_id'] = $this->upload_id;
		$row['assessment_type_id'] = $this->assessment_type_id;

		return new SupervisionDataLegacyTmp($row);
    }
}
