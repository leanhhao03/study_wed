<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $details = [
            'title' => 'Thay đổi mật khẩu',
            'body' => 'Cập nhật mật khẩu think sync của bạn'
        ];

        Mail::to($request->email)->send(new TestMail($details));

        return response()->json(['message' => 'Email đã được gửi thành công!']);
    }
}
