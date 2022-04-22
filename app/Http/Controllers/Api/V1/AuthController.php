<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPostRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(UserPostRequest $request)
    {
        dd($request);
    }
}
