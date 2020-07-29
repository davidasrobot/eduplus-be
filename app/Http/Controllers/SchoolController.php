<?php

namespace App\Http\Controllers;

use App\District;
use App\Province;
use App\Regency;
use App\School;
use App\Village;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;



class SchoolController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schools = School::with([
            'Province', 
            'Regency', 
            'District', 
            'Village', 
            'Images', 
            'Costs', 
            'Facilities', 
            'Majors', 
            'Extracurricular', 
            'Registration',
            'User' => function($query){
                $query->select(['name', 'uuid', 'avatar']);
            }
        ])->where('uuid', $id)->firstOrFail();
        return response()->json($schools, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        // District::chunkById(100, function($data){
        //     foreach ($data as $d) {
        //         School::where(
        //             [
        //                 'regency_id' => $d->regency_id,
        //                 'district_id' => 'Kec. '.$d->name
        //             ])->update([
        //             'district_id' => $d->id
        //         ]);
        //     }
        // });

        // School::chunkById(100, function($data){
        //     foreach ($data as $d) {
        //         School::where('id', $d->id)
        //         ->update([
        //             'uuid' => Uuid::uuid4()->toString()
        //         ]);
        //     }
        // });

        return response()->json(['success' => true]);
    }

    // public function regency_edit()
    // {
    //     $regency =  new Regency();
    //     $regName = $regency->all();
    //     foreach ($regName as $r) {
    //         $regency->where('name', $r->name)->update([
    //             'name' => str_replace("KABUPATEN ", "", $r->name)
    //         ]);
    //     }
    //     return response()->json($regName);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    // public function destroy(School $school)
    // {
    //     //
    // }
}
