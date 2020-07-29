<?php

namespace App\Http\Controllers;

use App\District;
use App\Favorite;
use App\Province;
use App\Regency;
use App\School;
use App\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stage' => 'required',
            'status' => 'required',
            'province' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $school = new School();
        $select = $school->select('uuid','name', 'address');
        $result = $select->where([
            'province_id' => $request->province,
            'educational_stage' => $request->stage,
        ]);
        
        if($request->has('regency')){
            $result = $select->where([
                'educational_stage' => $request->stage,
                'province_id' => $request->province, 
                'regency_id' => $request->regency
            ]);
        }
        if ($request->has('district')) {
            $result = $select->where([
                'educational_stage' => $request->stage,
                'province_id' => $request->province, 
                'regency_id' => $request->regency,
                'district_id' => $request->district,
            ]);
        }
        if($request->has('village')){
            $result = $select->where([
                'educational_stage' => $request->stage,
                'province_id' => $request->province, 
                'regency_id' => $request->regency,
                'district_id' => $request->district,
                'village_id' => $request->village
            ]);
        }

        $count = $result->count();
        $result = $result->with('Images')->paginate(15);

        return response()->json([
            'stage' => $request->stage,
            'result_count' => $count,
            'data' => $result
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'regency' => ['required'],
            'stage' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $school = new School();
        $school  = School::select('id','uuid','name', 'address', 'educational_stage');

        if ($request->has('desc')) {
            $orderBy = 'desc';
        }else{
            $orderBy = 'asc';
        }
        
        $result = $school->has('Favorite')
            ->where(['regency_id' => $request->regency, 'educational_stage' => $request->stage])
            ->with(['User' => function($query){
                $query->select('id', 'school_id', 'uuid', 'avatar');
            }, 'Images'])->orderBy('name', $orderBy);

        $count = $result->count();
        $result = $result->with('Images')->get();

        return response()->json([
            'stage' => $request->stage,
            'result_count' => $count,
            'data' => $result
        ]);
    }

    public function searchByName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if ($request->has('desc')) {
            $orderBy = 'desc';
        }else{
            $orderBy = 'asc';
        }

        $school = School::select('id','uuid','name', 'address')
            ->where('name', 'like' ,'%'.$request->name.'%')
            ->with(['User' => function($query){
                $query->select('id', 'school_id', 'uuid');
            }, 'Images'])->orderBy('name', $orderBy);

        $count = $school->get()->count();
        $school = $school->paginate(15);
        return response()->json([
            'old_value' => $request->name,
            'school_count' => $count,
            'data' => $school
        ]);
    }

    public function initSearch()
    {
        $province = Province::all();
        $regency = Regency::where('province_id', 11)->get()->take(15);
        $district = District::where('regency_id', 1105)->get()->take(15);
        $village = Village::where('district_id', 1105080)->get()->take(15);
        return response()->json([
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village
        ]);
    }
    public function getRegency($province)
    {
        $data = Regency::where('province_id', $province)->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getDistrict($regency)
    {
        $data = District::where('regency_id', $regency)->get();
        return response()->json([
            'data' => $data
        ]);
    }
    public function getVillage($district)
    {
        $data = Village::where('district_id', $district)->get();
        return response()->json([
            'data' => $data
        ]);
    }
}
