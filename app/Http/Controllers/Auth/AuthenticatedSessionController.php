<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Xử lý đăng nhập người dùng.
     */
    public function store(LoginRequest $request)
    {
        // Thử đăng nhập
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::user();
    // Lưu user_id vào session
    session(['user_id' => $user->id]);
    
    return response()->json([
        'message' => 'Đăng nhập thành công',
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'picture' => $user->picture,
        'role' => $user->role
    ], 200);
    }

    /**
     * Lấy thông tin user đang đăng nhập.
     */
    public function getUser()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'number_phone' => $user->number_phone,
            'date_of_birth' => $user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : null,
            'picture' => $user->picture,
            'created_at' => $user->created_at->format('d/m/Y')
        ], 200);
    }
    public function getNonZeroRoleUsers()
    {
        $users = User::where('role', '!=', 1)->get();
        return response()->json($users, 200);
    }


    /**
     * Đăng xuất user.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Đăng xuất thành công'], 200);
    }
}
