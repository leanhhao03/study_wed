<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|comfirmed',
        ]);

        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()],422); 
        }
        
        try {
            User::created([
                'us_name' => $request->name,
                'us_email' => $request->email,
                'us_password' => bcrypt($request->password),
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Đăng ký thất bại'],422);  
        }

        return response()->json(['message' => 'Đăng ký thành công'],422);  
    }
}
