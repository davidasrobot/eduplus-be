<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OperatorController extends Controller
{
    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required'],
            'password' => ['required', 'different:old_password'],
            'c_password' => ['required', 'same:password']
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        
        $user = User::findOrFail(auth()->id());
        if(Hash::check($request->old_password, $user->password)){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'success' => true
            ]);
        }else{
            return response()->json([
                'errors' => 'password does not match'
            ]);
        }
    }

    public function update_avatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $user = User::findOrFail(auth()->id());

        if($request->hasFile('avatar')){
            $path = Storage::disk()->put('schools/'.$user->Schools->name, $request->file('avatar'));
            $user->update([
                'avatar' => $path
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }
}
