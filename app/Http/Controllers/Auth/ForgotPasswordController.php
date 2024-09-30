<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // แสดงฟอร์มสำหรับขอรีเซ็ตรหัสผ่าน
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // ส่งลิงก์รีเซ็ตรหัสผ่านไปยังอีเมล
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', trans($response))
            : back()->withErrors(['email' => trans($response)]);
    }
}
