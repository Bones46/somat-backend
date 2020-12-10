<?php

namespace App\Repositories;

use App\Models\Village;
use App\Models\Subdistricts;
use App\Models\Districts;
use App\Models\Province;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class RegionRepository  extends Repository //implements libraryContract
{

    public static function getRegion($id = null, $name = null)
    {
        switch ($name) {
            case  "village":
                return Village::where('subdistricts_id', $id)->get(); 
            case "subdistrict":
                return Subdistricts::where('districts_id', $id)->get(); 
            case "district":
                return Districts::where('province_id', $id)->get(); 
            case "province":
                return Province::where('country_id', $id)->get(); 
            case "country":
                return country::where('country_id', $id)->get();
            default: 
                return null;

        }  
    }

    
}