<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham; // Nhớ thêm dòng này để gọi được bảng sản phẩm

class SanPhamController extends Controller
{
    // Tên hàm phải viết CHÍNH XÁC là danhSach (viết hoa chữ S)
   public function danhSach()
{
    // Đổi tên biến ở đây cho khớp với View
    $sanpham = SanPham::all(); 
    return view('san-pham.danh-sach', compact('sanpham'));
}

public function timKiem(Request $request)
{
    $tuKhoa = $request->input('query');

    // Đổi tên biến ở đây luôn
    $sanpham = SanPham::where('ten_san_pham', 'LIKE', "%{$tuKhoa}%")
                        ->orWhere('thuong_hieu', 'LIKE', "%{$tuKhoa}%")
                        ->get();

    return view('san-pham.danh-sach', compact('sanpham', 'tuKhoa'));
}
}