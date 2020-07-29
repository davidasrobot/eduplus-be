<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promos = Promo::all();
        return response()->json($promos);
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
            'name' => ['required'],
            'description' => ['required'],
            'image' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }
        
        if ($request->hasFile('image')) {
            $path = Storage::disk()->put('promo/', $request->file('image'));
        }
        Promo::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path
        ]);
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promo = Promo::findOrFail($id);
        return response()->json($promo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }
        if ($request->hasFile('image')) {
            $path = Storage::disk()->put('promo/', $request->file('image'));
            Promo::findOrFail($id)->update([
                'image' => $path
            ]);
        }
        Promo::findOrFail($id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
