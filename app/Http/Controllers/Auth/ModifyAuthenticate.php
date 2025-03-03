<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ModifyAuthenticate extends Controller
{
    public function updateProfile(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User không tồn tại!'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'number_phone' => 'nullable|string|max:15',
            'gender' => 'nullable|in:male,female,other',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('profile_pictures', $imageName, 'public');
            $user->profile_picture = $imagePath;
        }

        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }

        if ($request->filled('number_phone')) {
            $user->number_phone = $request->input('number_phone');
        }

        if ($request->filled('gender')) {
            $user->gender = $request->input('gender');
        }

        $user->save();

        return response()->json([
            'message' => 'Cập nhật thông tin thành công!',
            'user' => $user
        ], 200);
    }

    /**
     * Cập nhật email
     */
    public function updateEmail(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User không tồn tại!'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'new_email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->input('email') !== $user->email) {
            return response()->json(['message' => 'Email không đúng!'], 400);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Mật khẩu không đúng!'], 400);
        }

        $user->email = $request->input('new_email');
        $user->save();

        return response()->json([
            'message' => 'Cập nhật email thành công!',
            'user' => $user
        ], 200);
    }

    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User không tồn tại!'], 404);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Mật khẩu cũ không đúng!'], 400);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return response()->json([
            'message' => 'Cập nhật mật khẩu thành công!',
            'user' => $user
        ], 200);
    }
}
