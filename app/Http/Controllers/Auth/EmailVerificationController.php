<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verification($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->update([
            'email_verified_at' => now()
        ]);
        return response()->json([
            'success' => true,
        ]);
    }
}
