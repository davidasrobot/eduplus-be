<?php

namespace App\Http\Controllers;

use App\District;
use App\Promo;
use App\Province;
use App\Regency;
use App\School;
use App\Village;
use Illuminate\Http\Request;

class MainpageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($stage)
    {
        $promo = Promo::take(10);

        $data = Province::withCount(['Favorite' => function($query)use($stage){
            $query->with(['School' => function($q)use($stage){
                $q->where('educational_stage', $stage);
            }]);
        }])->get();
        
        return response()->json([
            'school' => $data,
            'promo' => $promo
        ]);
    }

    public function province($province, $stage)
    {
        $provinceName = Province::findOrFail($province);
        $data = Regency::withCount(['Favorite' => function($query)use($stage){
            $query->with(['School' => function($q)use($stage){
                $q->where('educational_stage', $stage);
            }]);
        }])->where('province_id', $province)->get();

        return response()->json([
            'province_name' => $provinceName,
            'school' => $data,
        ]);
    }

    public function regency($regency, $stage)
    {
        $regencyName = Regency::findOrFail($regency);
        $data = District::with(['Favorite' => function($query)use($stage){
            $query->with(['School' => function($q)use($stage){
                $q->where('educational_stage', $stage);
            }]);
        }])->where('regency_id', $regency)->get();

        return response()->json([
            'regency_name' => $regencyName,
            'school' => $data,
        ]);
    }

    public function district($district, $stage)
    {
        $districtName = District::findOrFail($district);
        // use WithCount to count school
        $data = Village::with(['Schools' => function($query)use($stage){
            $query->where([
                ['educational_stage', $stage],
                ['accreditation', 'A']
            ]);
        }])->where('district_id', $district)->get();

        return response()->json([
            'district_name' => $districtName,
            'school' => $data,
        ]);
    }

    public function village($village, $stage)
    {
        $villageName = Village::findOrFail($village);
        $data = Village::with(['Schools' => function($query)use($stage){
            $query->where([
                ['educational_stage', $stage],
                ['accreditation', 'A']
            ]);
        }])->where('id', $village)->get();

        return response()->json([
            'village_name' => $villageName,
            'school' => $data,
        ]);
    }
}
