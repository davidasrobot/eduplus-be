<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operator = User::where('active', 1)->get();
        return response()->json($operator);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Ban the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ban($id)
    {
        $operator = User::where('uuid', $id)->firstOrFail();
        $operator->update([
            'active' => 0
        ]);
        return response()->json([
            'success' => true
        ]);
    }
}
