<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ModifyAuthenticate extends Controller
{
    public function modifyProfile(Request $request)
    {
        // Lấy thông tin user đang đăng nhập
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Kiểm tra xem user có đúng là model `User` không
        if (!$user instanceof User) {
            return response()->json(['message' => 'User instance not found!'], 500);
        }

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'numberphone' => 'nullable|digits_between:9,15',
            'role' => 'nullable|string|in:admin,user,moderator',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // Hình ảnh <= 2MB
        ]);

        // Nếu có ảnh đại diện được upload
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('profile_pictures', $imageName, 'public'); // Lưu vào storage
            
            // Cập nhật trực tiếp vào DB
            $user->update(['profile_picture' => $imagePath]);
        }

        // Cập nhật thông tin user
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'numberphone' => $request->input('numberphone'),
            'gender' => $request->input('gender'),
            'date_of_birth' => $request->input('date_of_birth'),
        ]);

        return response()->json([
            'message' => 'Cập nhật hồ sơ thành công!',
            'user' => $user
        ], 200);
    }
}
