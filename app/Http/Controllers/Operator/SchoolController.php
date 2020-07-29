<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->id();
        $school = User::where('id', $user)->with(['Schools' => function($q){
            $q->with([
                'Costs', 
                'Facilities', 
                'Majors', 
                'Extracurricular', 
                'Registration', 
                'Images', 
                'Province', 
                'Regency', 
                'District',
                'Village',
            ]);
        }])->firstOrFail();
        return response()->json($school);
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
            'npsn' => ['required'],
            'name' => ['required'],
            'address' => ['required'],
            'province_id' => ['required'],
            'regency_id' => ['required'],
            'district_id' => ['required'],
            'village_id' => ['required'],
            'postal_code' => ['required'],
            'phone_number' => ['required'],
            'email' => ['required'],
            'website' => ['required'],
            'curriculum' => ['required'],
            'headmaster' => ['required'],
            'schools_hour' => ['required'],
            'total_student' => ['required'],
            'accreditation' => ['required'],
            'educational_stage' => ['required'],
            'status' => ['required'],
            'etf_cost' => ['required'],
            'spp_cost' => ['required'],
            'activities_cost' => ['required'],
            'book_cost' => ['required'],
            'discount' => ['required'],
            'registration' => ['required'],
            'annoucement' => ['required'],
            're_registration' => ['required'],
            'facilities' => ['required'],
            'major' => ['required'],
            'extracurricular' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = auth()->id();
        $school = User::findOrFail($user);
        $school->Schools->Costs()->update([
            'etf_cost' => $request->etf_cost,
            'spp_cost' => $request->spp_cost,
            'activities_cost' => $request->activities_cost,
            'book_cost' => $request->book_cost,
            'discount' => $request->discount
        ]);
        foreach ($request->facilities as $key) {
            $school->Schools->Facilities()->create([
                'name' => $key
            ]);
        }
        foreach ($request->major as $key) {
            $school->Schools->Majors()->create([
                'name' => $key
            ]);
        }
        foreach ($request->extracurricular as $key) {
            $school->Schools->Extracurricular()->create([
                'name' => $key
            ]);
        }
        $school->Schools->Registration()->update([
            'registration' => Carbon::make($request->registration),
            'annoucement' => Carbon::make($request->annoucement),
            're_registration' => Carbon::make($request->re_registration)
        ]);

        $school->Schools->pending()->create([
            'uuid' => Uuid::uuid4()->toString(),
            'npsn' => $request->npsn,
            'name' => $request->name,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'postal_code' => $request->postal_code,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'website' => $request->website,
            'curriculum' => $request->curriculum,
            'headmaster' => $request->headmaster,
            'schools_hour' => $request->school_hour,
            'total_student' => $request->total_student,
            'accreditation' => $request->accreditation,
            'educational_stage' => $request->educational_stage,
            'status' => $request->status,
        ]);
        return response()->json([
            'success' => true
        ]);
    }


    public function destroy_facility(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facility_id' => ['required', 'exists:facilities,id']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = auth()->id();
        $school = User::findOrFail($user)->Schools;
        $item = $school->Facilities()->findOrFail($request->facility_id);
        $item->delete();
        return response()->json([
            'success' => true
        ]);
    }
    public function destroy_extracurricular(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'extracurricular_id' => ['required', 'exists:extracurriculars,id']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = auth()->id();
        $school = User::findOrFail($user)->Schools;
        $item = $school->Facilities()->findOrFail($request->extracurricular_id);
        $item->delete();
        return response()->json([
            'success' => true
        ]);
    }
    public function destroy_major(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'major_id' => ['required', 'exists:majors,id']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = auth()->id();
        $school = User::findOrFail($user)->Schools;
        $item = $school->Facilities()->findOrFail($request->major_id);
        $item->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function upload_image(Request $request)
    {
        $id = auth()->id();
        $school = User::findOrFail($id)->Schools;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key) {
                $path = Storage::disk()->put('schools/'.$school->name, $key);
                $school->Images()->create([
                    'name' => $school->name.'-'.$key->getClientOriginalName(),
                    'image' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy_image($id)
    {
        $img = Image::where('id', $id)->firstOrFail();
        if (Storage::delete($img->image)) {
            $img->delete();
        }
        return response()->json(['success' => true]);

    }
}
