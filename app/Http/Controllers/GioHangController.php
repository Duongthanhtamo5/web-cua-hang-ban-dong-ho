<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\HoaDon; // Giả sử bạn có model HoaDon
use Illuminate\Support\Facades\Auth;
use App\Models\ChiTietHoaDon;

class GioHangController extends Controller
{
    public function index()
    {
        $gioHang = session()->get('gioHang', []);
        return view('gio-hang', compact('gioHang'));
    }

    public function themSanPham($id)
    {
        $sanPham = SanPham::findOrFail($id);
        $gioHang = session()->get('gioHang', []);

        // Nếu đã có sản phẩm này thì tăng số lượng
        if(isset($gioHang[$id])) {
            $gioHang[$id]['so_luong']++;
        } else {
            // Nếu chưa có thì thêm mới
            $gioHang[$id] = [
                "ten" => $sanPham->ten_san_pham,
                "so_luong" => 1,
                "gia" => $sanPham->gia_ban,
                "hinh" => $sanPham->hinh_anh
            ];
        }

        session()->put('gioHang', $gioHang);
        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }


    public function thanhToan(Request $request)
{
    $gioHang = session()->get('gioHang');

    if (!$gioHang) {
        return redirect()->back()->with('error', 'Giỏ hàng trống!');
    }

    // 1. Tạo hóa đơn mới
    $hoaDon = new HoaDon();
    $hoaDon->id_nguoi_dung = Auth::id();
    $hoaDon->ngay_lap = now();
    $hoaDon->tong_tien = array_sum(array_map(function($item) {
        return $item['gia'] * $item['so_luong'];
    }, $gioHang));
    $hoaDon->trang_thai = 'Cho xac nhan';
    $hoaDon->save();

    // 2. Lưu chi tiết hóa đơn (Nếu bạn có bảng chi_tiet_hoa_don)
    // foreach($gioHang as $id => $details) { ... }

    // 3. Xóa giỏ hàng sau khi đặt thành công
    session()->forget('gioHang');

    return redirect()->route('cart.complete')->with('success', 'Đặt hàng thành công!');

    
}

public function showCheckout(Request $request) 
{
    // 1. Lấy giỏ hàng hiện tại trong Session ra
    $gioHang = session()->get('gioHang', []);
    
    if(empty($gioHang)) {
        return redirect()->route('gio-hang.index');
    }

    // 2. CẬP NHẬT: Kiểm tra xem Form có gửi số lượng (qty) sang không
    if($request->has('qty')) {
        foreach($request->qty as $id => $so_luong) {
            if(isset($gioHang[$id])) {
                // Cập nhật số lượng mới vào biến $gioHang
                $gioHang[$id]['so_luong'] = $so_luong;
            }
        }
        // 3. Quan trọng: Lưu đè biến $gioHang đã cập nhật vào Session
        session()->put('gioHang', $gioHang);
    }
    
    // 4. Trả về giao diện thanh toán với dữ liệu ĐÃ CẬP NHẬT
    return view('thanh-toan', compact('gioHang'));
}


public function processOrder(Request $request) {
    // 1. Kiểm tra giỏ hàng
    $gioHang = session()->get('gioHang', []);
    if(empty($gioHang)) return redirect()->route('trang-chu');

    // 2. Lưu Hóa Đơn (Bảng hoa_don)
    $hoaDon = new HoaDon();
    $hoaDon->nguoi_dung_id = Auth::id(); // Sửa lại đúng tên cột trong ảnh database của bạn
    $hoaDon->ngay_lap = now();
    $hoaDon->dia_chi_giao = $request->dia_chi;
    
    // Lưu ý: Nếu DB của bạn chưa có cột sdt_nhan thì bỏ qua dòng này để tránh lỗi
    // $hoaDon->sdt_nhan = $request->sdt; 

    $hoaDon->loai_hoa_don = $request->payment; // Sửa phuong_thuc_tt thành loai_hoa_don theo DB
    $hoaDon->trang_thai = 'Cho xac nhan';

    $tongTien = 0;
    foreach($gioHang as $item) {
        $tongTien += $item['gia'] * $item['so_luong'];
    }
    $hoaDon->tong_tien = $tongTien;
    $hoaDon->save();

    // 3. Lưu Chi Tiết Hóa Đơn (Bảng chi_tiet_hoa_don)
    foreach($gioHang as $id => $item) {
        $chiTiet = new \App\Models\ChiTietHoaDon(); // Dùng đường dẫn tuyệt đối để chắc chắn không lỗi
        $chiTiet->hoa_don_id = $hoaDon->id;       // Sửa id_hoa_don thành hoa_don_id theo DB
        $chiTiet->san_pham_id = $id;             // Sửa id_san_pham thành san_pham_id theo DB
        $chiTiet->so_luong = $item['so_luong'];
        $chiTiet->gia_ban_luc_do = $item['gia']; // Sửa gia_ban thành gia_ban_luc_do theo DB
        $chiTiet->save();
    }

    // 4. Xóa giỏ hàng sau khi lưu thành công
    session()->forget('gioHang');
    return redirect()->route('cart.complete');
}

public function capNhatGioHang(Request $request)
{
    if($request->id && $request->so_luong){
        $gioHang = session()->get('gioHang');
        $gioHang[$request->id]["so_luong"] = $request->so_luong;
        session()->put('gioHang', $gioHang);
        return response()->json(['success' => true]);
    }
}

}