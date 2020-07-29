<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\PendingSchool;
use App\School;
use App\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param string $order
     * @param string $orderBy
     * @param string $educational_stage
     * @return \Illuminate\Http\Response
     */
    public function operators($order = 'asc', $orderBy = 'id', $educational_stage = null)
    {
        $operator = User::where('active', 0);
        if ($educational_stage !== null) {
            $operator = $operator->with(['Schools' => function($qr){
                $qr->select('id', 'uuid', 'name');
            }])->whereHas('Schools', function($q)use($educational_stage){
                $q->where('educational_stage', $educational_stage);
            });
        }
        $operator = $operator->orderBy($orderBy, $order);
        $operator = $operator->get();
        return response()->json($operator);
    }

    /**
     * Display a listing of the resource.
     * @param string $order
     * @param string $orderBy
     * @param string $educational_stage
     * @param string $publish
     * @return \Illuminate\Http\Response
     */
    public function schools($order = 'asc', $orderBy = 'id', $educational_stage = null)
    {
        $schools = new PendingSchool();
        if ($educational_stage !== null) {
            $operator = $schools->where('educational_stage', $educational_stage);
        }
        $operator = $operator->orderBy($orderBy, $order);
        $operator = $operator->get();
        return response()->json($operator);
    }

    public function operator_acc($id)
    {
        $operator = User::where('uuid', $id)->firstOrFail();
        $operator->update([
            'active' => 1
        ]);
        return response()->json([
            'success' => true
        ]);
    }

    public function school_acc($id)
    {
        $update = PendingSchool::where('uuid', $id)->firstOrFail();
        $school = School::findOrFail($update->school_id);
        $school->update([
            'npsn' => $update->npsn,
            'name' => $update->name,
            'address' => $update->address,
            'province_id' => $update->province_id,
            'regency_id' => $update->regency_id,
            'district_id' => $update->district_id,
            'village_id' => $update->village_id,
            'postal_code' => $update->postal_code,
            'phone_number' => $update->phone_number,
            'email' => $update->email,
            'website' => $update->website,
            'curriculum' => $update->curriculum,
            'headmaster' => $update->headmaster,
            'schools_hour' => $update->school_hour,
            'total_student' => $update->total_student,
            'accreditation' => $update->accreditation,
            'status' => $update->status,
            'educational_stage' => $update->educational_stage,
            'publish' => 1
        ]);
        $update->delete();
        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        $operator = User::where('uuid', $id)->firstOrFail();
        $operator->delete();
        return response()->json($operator);
    }
}
