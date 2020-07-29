<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Jumbotron;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JumbotronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jumbotrons = Jumbotron::all();
        return response()->json($jumbotrons);
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
            'background' => ['required'],
            'image' => ['required'],
            'heading' => ['required'],
            'text' => ['required'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        if($request->hasFile('background')){
            if($request->hasFile('image')){
                $path_background = Storage::disk()->put('jumbotron/', $request->file('background'));
                $path_image = Storage::disk()->put('jumbotron/', $request->file('image'));
                Jumbotron::create([
                    'background' => $path_background,
                    'image' => $path_image,
                    'heading' => $request->heading,
                    'text' => $request->text
                ]);
            }
        }
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
        $jumbotron = Jumbotron::findOrFail($id);
        return response()->json($jumbotron);
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
            'heading' => ['required'],
            'text' => ['required'],
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        if($request->hasFile('background')){
            $path_background = Storage::disk()->put('jumbotron/', $request->file('background'));
            Jumbotron::findOrFail($id)->update([
                'background' => $path_background
            ]);
        }
        if($request->hasFile('image')){
            $path_image = Storage::disk()->put('jumbotron/', $request->file('image'));
            Jumbotron::findOrFail($id)->update([
                'image' => $path_image
            ]);
        }
        Jumbotron::findOrFail($id)->update([
            'heading' => $request->heading,
            'text' => $request->text
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
        $jumbotron = Jumbotron::findOrFail($id);
        $jumbotron->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
