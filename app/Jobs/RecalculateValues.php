<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RecalculateValues implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      \Log::debug("Job has been dispatched");
        try{
          \Log::debug("Getting diarrhoea data");
            $diarrhoeaData = \DB::select('SELECT * FROM diarrhoea_case_tx_aggreagate');
            $diarrhoeaInsertData = collect($diarrhoeaData)->map(function($data){
                $c = \App\County::where('county', $data->county)->first();
                if (!is_null($c)) {
                  $county = $c->cto_id;
                }else{
                  $county = $data->cname;
                }
                
                $sub_county = \App\Subcounty::where("subcounty_name", $data->sub_county)->first();
                // \Log::debug("Subcounty: " . json_encode($sub_county));
                $subcounty_id = ($sub_county) ? $sub_county->cto_id : 0;
                if($subcounty_id == 0){
                  $subcounty_id = \App\Facility::where('SURVEY_CTO_ID', $data->fname)->first()->SUB_COUNTY_ID;
                }

                return [
                  'cname'                 =>  $county,
                  'scname'                =>  $subcounty_id,
                  'fname'                 =>  (int)$data->fname,
                  'assessment'            =>  $data->assessment,
                  'assessment_type'       =>  $data->assessment_type,
                  'assessment_type_step'  =>  $data->assessment_step,
                  'NO_CLASS_CASES'        =>  $data->NO_CLASS_CASES,
                  'classified'            =>  $data->classified,
                  'total_cases'           =>  $data->total_cases,
                  'ANTIBIOTICS'           =>  $data->ANTIBIOTICS,
                  'IV'                    =>  $data->IV,
                  'NOTX'                  =>  $data->NOTX,
                  'COP'                   =>  $data->COP,
                  'ZINC'                  =>  $data->ZINC,
                  'ORS'                   =>  $data->ORS,
                  'OTHER'                 =>  $data->OTHER,
                  'TOTAL_TX'              =>  $data->TOTAL_TX,
                  'DIFFERENCE'            =>  $data->DIFFERENCE     
                ];
              })->toArray();

          \Log::debug("Diarrhoea data received");
          \Log::debug("Getting pneumonia data");

          $pneumoniaClassificationData = \DB::select("SELECT c.id, c.county,
            c.sub_county,
            c.fname,
            c.assessment,
            c.assessment_type,
            c.assessment_step,
            c.TOTAL_CLASSIFIED,
            c.TOTAL_TX,
            c.TOTAL_TX_NOTX_EX,
            c.NO_CLASSIFICATION,
            c.TREATMENT_DIFF,
            c.ABS_TREATMENT_DIFF,
            c.NO_CLASSIFICATION_INCL_DIFF,
            c.TOTAL_CASES_AFTER_DIF,
            c.TOTAL_TX_AFTER_DIF,
            t.AMOXDT,
            t.AMOX_SYRUP,
            t.OXYGEN,
            t.CTX,
            t.GENT,
            t.BENZ,
            t.BENZ_GENT,
            t.INJECTABLES,
            t.ANTIBIOTICS,
            t.OTHER,
            t.ANTI_OTHER,
            t.NOTX FROM pneumonia_facility_classification c JOIN pneumonia_facility_treatment_data t ON t.id = c.id");
          $pneuClassInsertData = collect($pneumoniaClassificationData)->map(function($data){
            if($data->sub_county){
              $county = \App\County::where('county', $data->county)->first()->cto_id;
              $sub_county = \App\Subcounty::where("subcounty_name", $data->sub_county)->first();
              // dd($sub_county);
              // \Log::debug("PNEU Subcounty: " . json_encode($sub_county));
              $subcounty_id = ($sub_county) ? $sub_county->cto_id : 0;
              // \Log::debug("{$data->id} Sub County ID: {$subcounty_id}");
              if($subcounty_id == 0){
                $facility = \App\Facility::where('SURVEY_CTO_ID', $data->fname)->first();
                $subcounty_id = ($facility) ? $facility->SUB_COUNTY_ID : 0;
              }
              return [
                'cname' => $county,
                'scname' => $subcounty_id,
                'fname' =>  (int)$data->fname,
                'assessment' => $data->assessment,
                'assessment_type' => $data->assessment_type,
                'assessment_type_step'  =>  $data->assessment_step, 
                'total_classified' => $data->TOTAL_CLASSIFIED,
                'total_tx' => $data->TOTAL_TX,
                'total_tx_notx_ex' => $data->TOTAL_TX_NOTX_EX,
                'no_classification' => $data->NO_CLASSIFICATION,
                'treatment_diff' => $data->TREATMENT_DIFF,
                'abs_treatment_diff' => $data->ABS_TREATMENT_DIFF,
                'no_clasification_incl_diff' => $data->NO_CLASSIFICATION_INCL_DIFF,
                'total_cases_after_dif' => $data->TOTAL_CASES_AFTER_DIF,
                'total_tx_after_dif' => $data->TOTAL_TX_AFTER_DIF,
                'AMOXDT'  =>  $data->AMOXDT,
                'AMOX_SYRUP'  =>  $data->AMOX_SYRUP,
                'OXYGEN'  =>  $data->OXYGEN,
                'CTX' =>  $data->CTX,
                'GENT'  =>  $data->GENT,
                'BENZ'  =>  $data->BENZ,
                'BENZ_GENT' =>  $data->BENZ_GENT,
                'INJECTABLES' =>  $data->INJECTABLES,
                'ANTIBIOTICS' =>  $data->ANTIBIOTICS,
                'OTHER' =>  $data->OTHER,
                'ANTI_OTHER'  =>  $data->ANTI_OTHER,
                'NOTX'  =>  $data->NOTX
              ];
            }else{
              \Log::debug("$data->id has no subcounty");
            }
          });

          $pneuClassInsertData = $pneuClassInsertData->filter(function($value){
            return !is_null($value);
          })->toArray();

          \Log::debug("Pneumonia Data received");
    }
    catch (\Exception $ex){
        \Log::error($ex->getMessage());
        die($ex->getMessage());
    }

    // dd($pneuClassInsertData);
    // \Log::debug(json_encode($pneuClassInsertData));

    \Log::debug("Truncating data from pneumonia and diarrhoea calculated values");
    \App\PneumoniaCalculatedValue::query()->truncate();
    \App\DiarrhoeaCalculatedValue::query()->truncate();
    \Log::debug("Successfully truncated data");
    \Log::debug("Inserting data");

    foreach (array_chunk($pneuClassInsertData, 1000) as $d) {
      \App\PneumoniaCalculatedValue::insert($d);
    }
    
    \App\DiarrhoeaCalculatedValue::insert($diarrhoeaInsertData);
    \Log::debug("Successfully inserted data");
    // dd($pneuClassInsertData);
    }
}
