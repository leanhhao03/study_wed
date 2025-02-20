<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json(['message' => 'Email không tồn tại trong hệ thống'], 404);
        }

        //token
        $token = Password::createToken($user);

        $details = [
            'title' => 'Thay đổi mật khẩu',
            'body' => "Cập nhật mật khẩu think sync của bạn:/n/n". url("/reset-password?token=$token&email=" . $request->email)
        ];

        Mail::to($request->email)->send(new TestMail($details));

        return response()->json(['message' => 'Email đã được gửi thành công!'], 200);
    }
}
