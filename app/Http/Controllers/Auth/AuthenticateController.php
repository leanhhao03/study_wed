<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthenticateController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function LoginUser(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
    
        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $user = Auth::user();
        
        // Lưu user_id vào session database
        session(['user_id' => $user->id]);
    
        Log::info('User in Session:', ['session' => session()->all()]); // Kiểm tra log
    
        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email, 
            'picture' => $user->picture
        ], 200);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'number_phone' => $user->number_phone,
            'date_of_birth' => Carbon::parse($user->date_of_birth)->format('d/m/Y'),
            'password' => $user->password,
            'picture' => $user->picture,
            'created_at' => Carbon::parse($user->created_at)->format('d/m/Y')
        ]);
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
