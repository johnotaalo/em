<?php

namespace App\Imports;

use App\SupervisionDataUploadTmp;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupervisionDataImport implements ToModel, WithHeadingRow
{
    protected $upload_id;
    protected $assessment_type_id;
    protected $period;
    protected $step;

    public function __construct($upload_id, $assessment_type_id, $period, $step){
        $this->upload_id = $upload_id;
        $this->assessment_type_id = $assessment_type_id;
        $this->period = $period;
        $this->step = $step;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // echo "<pre>";print_r($row);die;
        $treatmentsAndCases = [
            'sev_cases',
            'sev_amoxdt',
            'sev_amoxsy',
            'sev_oxygen',
            'sev_ctx',
            'sev_benz',
            'sev_gent',
            'sev_benzgent',
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
            'd_noclass_no'
        ];

        $theKeys = [];
        // echo "<pre>";print_r($row);die;
        $keyData = [
            'supname' => ($row["supname"]) ? $row["supname"] : "",
            'cname' => ($row["cname"]) ? $row["cname"] : "",
            'county' => ($row["county"]) ? $row["county"] : "" ,
            'scname' => ($row["scname"]) ? $row["scname"]: "",
            'sub_county' => $row["sub_county"],
            'fname' => $row["fname"],
            'facility_name' => $row["facility_name"],
            'incharge' => $row["incharge"],
            'mobile' => $row["mobile"],
            'upload_id'             =>  $this->upload_id,
            'period'                =>  $this->period,
            'assessment_type_id'    =>  $this->assessment_type_id,
            'step'                  =>  $this->step,
        ];
        
        foreach ($row as $key => $value) {
            if (in_array($key, $treatmentsAndCases)) {
                $theKeys[$key] = $value;
            }
        }        

        $keyData = $keyData + $theKeys;

        // echo "<pre>";print_r($keyData);die;

        return new SupervisionDataUploadTmp($keyData);
    }
}
