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

        $username = explode('@', $user->email)[0];

        // Nội dung email
        $details = [
            'title' => 'Thay đổi mật khẩu',
            'body' => "Chúng tôi  gửi  email này để thông báo rằng bạn đã thực hiện thay đổi mật khẩu cho tài khoản Thinksyns của mình. 
Đây là mã OTP của bạn, vui lòng không chia sẻ mã này cho bất kỳ ai.
Cảm ơn bạn đã tin tưởng đồng hành cùng Thinksync. 
Trân trọng,
        $username
        ******$otp******",
        ];

        // Gửi email
        Mail::to($user->email)->send(new TestMail($details));

        return response()->json(['message' => 'Mã xác nhận đã được gửi đến email!'], 200);
    }
}
