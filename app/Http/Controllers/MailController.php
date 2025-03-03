<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\TestMail;
use App\Models\User;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email không tồn tại!'], 404);
        }

        // Tạo token (OTP)
        $otp = rand(100000, 999999);
        
        // Lưu token vào database với thời gian hết hạn 15 phút
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($otp), 'created_at' => now()]
        );

        // Nội dung email
        $details = [
            'title' => 'Thay đổi mật khẩu',
            'body' => "Mã xác nhận đặt lại mật khẩu của bạn là: **$otp** (có hiệu lực trong 15 phút)",
        ];

        // Gửi email
        Mail::to($user->email)->send(new TestMail($details));

        return response()->json(['message' => 'Mã xác nhận đã được gửi đến email!'], 200);
    }
}
