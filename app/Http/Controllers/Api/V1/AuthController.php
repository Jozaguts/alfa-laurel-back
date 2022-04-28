<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Cassandra\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserPostRequest;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $user = User::where('email', '=', request()->email)->first();

        if (! $user || ! Hash::check(request()->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'Las credenciales proveídas son incorrectas.']);
        }
        // ejemplo para trabajar con tokens
        $token = $user->createToken('token');

        return $token->plainTextToken;
    }

    public function logout() {

        // ejemplo para trabajar con tokens
        $user = auth()->user();
        foreach ($user->tokens as $token) {
            $token->delete();
        }

        return response()->json('Usuario cerro sesión...',200);
    }

}
