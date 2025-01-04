<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthenticateController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function LoginUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'us_name' => 'required|email|',
            'us_password' => 'required|min:6'
        ]);

        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()],422); 
        }
        
     
            if(!Auth::attempt([
                'us_name' => $request -> us_name,
                'us_password' => $request -> us_password
            ])) {
                throw ValidationException::withMessages([
                    'msg' => 'Email or password invalid'
                ]);
            }
          
        return response()->json(['message' => 'Đăng nhập thành công'],422);  
    }
}
