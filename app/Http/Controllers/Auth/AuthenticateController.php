<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            'email' => 'required|email|',
            'password' => 'required|min:6'
        ]);

        if($validate->fails()){
            return response()->json(['errors' => $validate->errors()],422); 
        }
        
        $credentials = $request->only(['email', 'password']);
     
            if(Auth::attempt($credentials)) {
                $request->session()->put('user', Auth::user());
                return response()->json(['message' => 'đăng nhập thành công'],200);
            
            }
                throw ValidationException::withMessages([
                    'msg' => 'Email or password invalid'
                ]);
          
        return response()->json(['message' => 'Đăng nhập thành công'],200);  
    }

    //logout seesion user
    public function LogoutUser(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Đăng xuất thành công'], 200);
    }
}
