<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị trang đăng ký
    public function showRegister() {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function postRegister(Request $request) {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dung,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique' => 'Email này đã được đăng ký.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.'
        ]);

        $user = new NguoiDung();
        $user->ho_ten = $request->ho_ten;
        $user->email = $request->email;
        $user->so_dien_thoai = $request->so_dien_thoai;
        $user->mat_khau = Hash::make($request->password);
        $user->vai_tro = 'khach_hang';
        $user->save();

        return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công! Mời bạn đăng nhập.');
    }

    // Hiển thị trang đăng nhập
    public function showLogin() {
        return view('auth.login');
    }

    // Xử lý đăng nhập và phân quyền chuyển hướng
    public function postLogin(Request $request)
{
    // Lấy email và mật khẩu từ form
    $credentials = [
        'email' => $request->email,
        'password' => $request->password, // Laravel vẫn cần key 'password' ở đây để xử lý hash
    ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        $user = Auth::user();

        // CHÚ Ý: So khớp chính xác với giá trị vai_tro trong Database của bạn
        switch ($user->vai_tro) {
            case 'admin':
                return redirect()->route('admin.dashboard');
                
            case 'nhanvien_banhang': // Kiểm tra kỹ trong DB có viết đúng như này không
                return redirect()->route('banhang.index');
                
            case 'nhanvien_kho': // Kiểm tra kỹ trong DB có viết đúng như này không
                return redirect()->route('kho.index');
                
            default:
                return redirect()->route('trang-chu');
        }
    }

    // Trả về kèm thông báo lỗi nếu sai tài khoản/mật khẩu
    return back()->withErrors([
        'email' => 'Email hoặc mật khẩu không chính xác.',
    ]);
}

       

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('trang-chu');
    }
}