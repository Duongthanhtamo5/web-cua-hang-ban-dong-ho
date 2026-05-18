<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HoaDon;
use App\Models\ChiTietHoaDon;
use App\Models\SanPham;



class AdminController extends Controller
{
    public function index()
    {
        // 1. Tính tổng doanh thu thực tế từ hóa đơn đã thanh toán
        $doanhThu = DB::table('hoa_don')
            ->where('trang_thai', 'Da thanh toan')
            ->sum('tong_tien');

        // 2. Tính tổng số đơn hàng hiện có
        $tongDonHang = DB::table('hoa_don')->count();

        // 3. Tính tổng số lượng chiếc đồng hồ tồn kho
        $tongTonKho = DB::table('san_pham')->sum('so_luong_kho');

        // 4. Tính tổng nhân sự hệ thống (admin, kho, banhang)
        $tongNhanVien = DB::table('nguoi_dung')
            ->whereIn('vai_tro', ['admin', 'nhanvien_kho', 'nhanvien_banhang'])
            ->count();

        // 5. Lấy danh sách nhật ký log hoạt động mới nhất
        $logs = DB::table('log_hoat_dong')
            ->join('nguoi_dung', 'log_hoat_dong.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->select('log_hoat_dong.*', 'nguoi_dung.ho_ten', 'nguoi_dung.vai_tro')
            ->orderBy('log_hoat_dong.id', 'desc')
            ->limit(5)
            ->get();

        // BẮT BUỘC: Phải truyền đầy đủ danh sách biến này ra view admin.dashboard
        return view('admin.dashboard', compact('doanhThu', 'tongDonHang', 'tongTonKho', 'tongNhanVien', 'logs'));
    }

   


public function thongKeBaoCao(Request $request)
{
    // 1. THỐNG KÊ DOANH THU - GIÁ VỐN - LỢI NHUẬN THEO ĐƠN "Da thanh toan"
    // Doanh thu = Tổng các hóa đơn thành công
    $doanhThuTotal = DB::table('hoa_don')
        ->where('trang_thai', 'Da thanh toan')
        ->sum('tong_tien');

    // Giá vốn = Tổng (Số lượng bán * gia_von của sản phẩm) từ các đơn đã thanh toán
    $giaVonTotal = DB::table('chi_tiet_hoa_don')
        ->join('hoa_don', 'chi_tiet_hoa_don.hoa_don_id', '=', 'hoa_don.id')
        ->join('san_pham', 'chi_tiet_hoa_don.san_pham_id', '=', 'san_pham.id')
        ->where('hoa_don.trang_thai', 'Da thanh toan')
        ->sum(DB::raw('chi_tiet_hoa_don.so_luong * san_pham.gia_von'));

    $loiNhuanTotal = $doanhThuTotal - $giaVonTotal;

    // 2. BIỂU ĐỒ DOANH THU (Nhóm theo ngày phát sinh hóa đơn)
    $bieuDoData = DB::table('hoa_don')
        ->where('trang_thai', 'Da thanh toan')
        ->groupBy(DB::raw('DATE(ngay_lap)'))
        ->select(
            DB::raw('DATE(ngay_lap) as ngay'), 
            DB::raw('SUM(tong_tien) as doanh_thu')
        )
        ->orderBy('ngay', 'asc')
        ->get();

    // 3. THỐNG KÊ TOP 5 MẪU ĐỒNG HỒ BÁN CHẠY NHẤT
    $sanPhamBanChay = DB::table('chi_tiet_hoa_don')
        ->join('san_pham', 'chi_tiet_hoa_don.san_pham_id', '=', 'san_pham.id')
        ->select(
            'san_pham.ten_san_pham',
            'san_pham.hinh_anh',
            'san_pham.gia_ban',
            DB::raw('SUM(chi_tiet_hoa_don.so_luong) as tong_da_ban')
        )
        ->groupBy('chi_tiet_hoa_don.san_pham_id', 'san_pham.ten_san_pham', 'san_pham.hinh_anh', 'san_pham.gia_ban')
        ->orderBy('tong_da_ban', 'desc')
        ->take(5)
        ->get();

    // 4. PHAN TÍCH HÀNH VI MUA SẮM (Dựa trên trường dia_chi_giao của hóa đơn)
    // Mua tại quầy POS: Khi cột dia_chi_giao trống
    $donTaiQuay = DB::table('hoa_don')
        ->where('trang_thai', 'Da thanh toan')
        ->where(function($q) {
            $q->whereNull('dia_chi_giao')->orWhere('dia_chi_giao', '');
        })->count();

    // Đặt giao hàng Online: Khi cột dia_chi_giao có giá trị địa chỉ nhận hàng
    $donOnline = DB::table('hoa_don')
        ->where('trang_thai', 'Da thanh toan')
        ->whereNotNull('dia_chi_giao')
        ->where('dia_chi_giao', '!=', '')
        ->count();

    return view('admin.thongke_baocao', compact(
        'doanhThuTotal', 'giaVonTotal', 'loiNhuanTotal', 
        'bieuDoData', 'sanPhamBanChay', 'donTaiQuay', 'donOnline'
    ));
}
}