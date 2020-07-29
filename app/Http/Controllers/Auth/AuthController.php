<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserEmailVerification;
use App\School;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'school_name' => 'required',
            'email' => 'required|unique:users',
            'npsn' => 'required',
            'position' => 'required',
            'phone_number' => 'required|unique:users|max:16',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        // $request->request->add(['uuid' => Uuid::uuid4()->toString()]);
        // $request->request->add(['role_id' => 3]); //operator
        $password = $this->randomPassword();

        if ($request->has('uuid')) {
            $sch_id = School::where('uuid', $request->uuid)->first();
            $user = User::create([
                'uuid' => Uuid::uuid4()->toString(),
                'name' => $request->name,
                'email' => $request->email,
                'position' => $request->position,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($password),
                'school_id' => $sch_id->id,
                'avatar' => '/images/avatar/default.png'
            ]);
            
        }else{
            $school = School::create([
                'uuid' => Uuid::uuid4()->toString(),
                'name' => $request->school_name,
                'npsn' => $request->npsn,
                'publish' => 0
            ]);
            $school->Costs()->create();
            $school->Registration()->create();
            $user = $school->User()->create([
                'uuid' => Uuid::uuid4()->toString(),
                'name' => $request->name,
                'email' => $request->email,
                'position' => $request->position,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($password),
                'avatar' => '/images/avatar/default.png'
            ]);
        }
        
        Mail::to($request->email)->send(new UserEmailVerification($user, $password));
        return response()->json([
            'success' => true,
        ]);
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890#@!';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function authenticate(Request $request)
    {
        $credential = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credential)) {
            return response()->json([
                'error' => 'Unautorized'
            ], 400);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60
        ]);
    }
}
