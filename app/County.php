<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    protected $table="counties";

    public $timestamps = false;

    public function supervisions(){
    	return $this->hasMany('\App\Supervision', 'cname', 'cto_id');
    }

    public function facilities(){
    	return $this->hasMany('\App\Facility', 'COUNTY_ID', 'cto_id');
    }

    public function subcounties(){
    	return $this->hasMany('\App\Subcounty', 'COUNTY_ID', 'cto_id');
    }
}
