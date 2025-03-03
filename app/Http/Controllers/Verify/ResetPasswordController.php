<?php

namespace App\Http\Controllers\Verify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required|string',
        'password' => 'required|confirmed|min:8',
    ]);

    $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

    if (!$record || !Hash::check($request->otp, $record->token)) {
        return response()->json(['message' => 'Mã OTP không hợp lệ hoặc đã hết hạn!'], 400);
    }

    // Kiểm tra thời gian hết hạn (10 phút)
    if (now()->diffInMinutes($record->created_at) > 10) {
        return response()->json(['message' => 'Mã OTP đã hết hạn!'], 400);
    }

    // Cập nhật mật khẩu mới
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Xóa OTP sau khi đặt lại mật khẩu thành công
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Mật khẩu đã được thay đổi!'], 200);
}

}
