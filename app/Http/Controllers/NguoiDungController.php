<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoaDon;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NguoiDungController extends Controller
{
    // 1. Hiển thị hồ sơ cá nhân của khách hàng
    public function showProfile() {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    // 2. Cập nhật thông tin hồ sơ & Mật khẩu mới
    public function updateProfile(Request $request) {
        $user = Auth::user(); 
        
        $user->ho_ten = $request->ho_ten;
        $user->so_dien_thoai = $request->so_dien_thoai;

        // Nếu người dùng có nhập mật khẩu mới thì tiến hành mã hóa và cập nhật
        if ($request->filled('new_password')) {
            $user->mat_khau = Hash::make($request->new_password); 
        }

        $user->save();
        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // 3. Theo dõi các đơn hàng đang xử lý (Chờ xác nhận, Đang giao)
    public function trackOrders() {
        $donHangs = HoaDon::with('chiTiet.sanPham')
                    ->where('nguoi_dung_id', Auth::id())
                    ->whereIn('trang_thai', ['Cho xac nhan', 'Dang giao'])
                    ->orderBy('ngay_lap', 'desc')
                    ->get();
                    
        return view('auth.tracking', compact('donHangs'));
    }

    // 4. Xem lịch sử toàn bộ đơn hàng và tổng chi tiêu tích lũy
    public function orderHistory() {
        $donHangs = HoaDon::with('chiTiet.sanPham')
                    ->where('nguoi_dung_id', Auth::id())
                    ->orderBy('ngay_lap', 'desc')
                    ->get();
        
        // Tối ưu hóa tính tổng chi tiêu: Tính tổng các đơn đã hoàn tất thành công
        $tongChiTieu = $donHangs->whereIn('trang_thai', ['Da thanh toan', 'Da giao'])->sum('tong_tien');
        
        return view('auth.history', compact('donHangs', 'tongChiTieu'));
    }
}