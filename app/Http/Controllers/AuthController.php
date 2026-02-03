<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // สมัครสมาชิก
    public function register(Request $request) {
        $data = $request->validate([
            // ตรงนี้มันจะเช็คช่อง username ใน DB ได้ถูกต้องแล้ว เพราะเราเปลี่ยนชื่อช่องแล้ว
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable'
        ]);

        $user = User::create([
            'username' => $data['username'], // ใช้ username ได้เลย
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return response()->json([
            'message' => 'สมัครสมาชิกสำเร็จ',
            'user' => $user
        ]);
    }

    // เข้าสู่ระบบ
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // เปลี่ยนจาก name เป็น username
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return response()->json([
                'message' => 'เข้าสู่ระบบสำเร็จ',
                'user' => Auth::user()
            ]);
        }

        return response()->json(['message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'], 401);
    }

    // ... (ส่วน logout กับ me ใช้เหมือนเดิมได้เลย)
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out']);
    }

    public function me() {
        return response()->json(Auth::user());
    }
}
