<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|string|email|max:200|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            //bikin token untuk orang yang regisyer
            $token = $user->createToken('register_token')->plainTextToken;
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Register Success',
                'data' => $user,
                'access_token' => $token,
                'type' => 'Bearer'
            ]);
        } catch (Exception $e) {

        }
    }
}
