<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\NhanVienController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| TRANG CHỦ & TÌM KIẾM
|--------------------------------------------------------------------------
*/
Route::get('/', [SanPhamController::class, 'danhSach'])->name('trang-chu');
Route::get('/tim-kiem', [SanPhamController::class, 'timKiem'])->name('san-pham.tim-kiem');

/*
|--------------------------------------------------------------------------
| ĐĂNG KÝ & ĐĂNG NHẬP
|--------------------------------------------------------------------------
*/
Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
Route::post('/dang-ky', [AuthController::class, 'postRegister'])->name('register.post');
Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
Route::post('/dang-nhap', [AuthController::class, 'postLogin'])->name('login.post');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| NHÓM ROUTE CẦN ĐĂNG NHẬP (MIDDLEWARE AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 1. GIỎ HÀNG & THANH TOÁN
    Route::get('/gio-hang', [GioHangController::class, 'index'])->name('gio-hang.index');
    Route::get('/gio-hang/them/{id}', [GioHangController::class, 'themSanPham'])->name('gio-hang.them');
    Route::patch('/cap-nhat-gio-hang', [GioHangController::class, 'capNhatGioHang'])->name('cart.update');
    Route::get('/thanh-toan', [GioHangController::class, 'showCheckout'])->name('cart.checkout');
    Route::post('/xac-nhan-dat-hang', [GioHangController::class, 'processOrder'])->name('cart.process');
    Route::get('/hoan-tat-don-hang', function() { return view('hoan-tat'); })->name('cart.complete');

    // 2. HỒ SƠ CÁ NHÂN (KHÁCH HÀNG)
    Route::get('/ho-so', [NguoiDungController::class, 'showProfile'])->name('profile.show_user');
    Route::post('/ho-so/cap-nhat', [NguoiDungController::class, 'updateProfile'])->name('profile.update_user');
    Route::get('/theo-doi-don-hang', [NguoiDungController::class, 'trackOrders'])->name('orders.tracking');
    Route::get('/lich-su-mua-hang', [NguoiDungController::class, 'orderHistory'])->name('orders.history');

    // 3. HỒ SƠ CÁ NHÂN & ĐỔI MẬT KHẨU (NHÂN VIÊN)
    Route::get('/tai-khoan', [NhanVienController::class, 'showProfile'])->name('profile.show');
    Route::put('/tai-khoan/cap-nhat', [NhanVienController::class, 'updateProfile'])->name('profile.update');
    Route::put('/tai-khoan/doi-mat-khau', [NhanVienController::class, 'changePassword'])->name('profile.password');

    // 4. HỆ THỐNG NHÂN VIÊN (BÁN HÀNG - ĐƠN HÀNG - KHÁCH HÀNG - KHO)
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');

    // Bán hàng tại quầy
    Route::get('/ban-hang', [NhanVienController::class, 'index'])->name('banhang.index');
    Route::get('/ban-hang/tim-khach', [NhanVienController::class, 'timKhachHang'])->name('banhang.timkhach');
    Route::get('/ban-hang/them/{id}', [NhanVienController::class, 'themVaoGio'])->name('banhang.giohang.them');
    Route::get('/ban-hang/xoa/{id}', [NhanVienController::class, 'xoaSanPham'])->name('banhang.xoa');
    Route::post('/ban-hang/cap-nhat/{id}', [NhanVienController::class, 'capNhatSoLuong'])->name('banhang.capnhat');
    Route::post('/ban-hang/xac-nhan', [NhanVienController::class, 'xacNhanDonHang'])->name('banhang.xacnhan');

    // Quản lý đơn hàng
    Route::get('/quan-ly-don-hang', [NhanVienController::class, 'quanLyDonHang'])->name('admin.orders.index');
    Route::post('/quan-ly-don-hang/cap-nhat/{id}', [NhanVienController::class, 'capNhatTrangThai'])->name('admin.orders.update');
    Route::post('/quan-ly-don-hang/them-moi', [NhanVienController::class, 'storeManual'])->name('admin.orders.store_manual');

    // Quản lý khách hàng
    Route::get('/quan-ly-khach-hang', [NhanVienController::class, 'danhSachKhachHang'])->name('admin.customers.index');
    Route::post('/quan-ly-khach-hang/them', [NhanVienController::class, 'themKhachHangMoi'])->name('admin.customers.store');
    Route::put('/quan-ly-khach-hang/cap-nhat/{id}', [NhanVienController::class, 'capNhatKhachHang'])->name('admin.customers.update');

    // Quản lý kho
    Route::get('/kho', [NhanVienController::class, 'danhSachKho'])->name('kho.index');
    Route::post('/kho/them-san-pham', [NhanVienController::class, 'storeSanPham'])->name('kho.store');
    Route::post('/kho/nhap-them/{id}', [NhanVienController::class, 'nhapThemHang'])->name('kho.nhap_them');
    // Thêm dòng này vào trong nhóm route quản lý kho của web.php
    Route::put('/kho/cap-nhat/{id}', [NhanVienController::class, 'capNhatSanPham'])->name('kho.update');
    Route::get('/kho/nhap-hang', [NhanVienController::class, 'showNhapHang'])->name('kho.nhap');
    Route::get('/kho/lich-su-nhap', [NhanVienController::class, 'lichSuNhap'])->name('kho.lichsu');
    // Route hiển thị trang đổi mật khẩu
Route::get('/kho/profile', [NhanVienController::class, 'showProfile'])->name('profile.show');

// Route xử lý cập nhật mật khẩu khi bấm nút gửi form
Route::post('/kho/profile/doi-mat-khau', [NhanVienController::class, 'doiMatKhau'])->name('profile.updatePassword');
});


// QUẢN LÝ NHÂN VIÊN CHUYÊN BIỆT CHO ADMIN
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::put('/admin/quan-ly-don-hang/cap-nhat/{id}', [NhanVienController::class, 'capNhatTrangThaiDon'])->name('admin.orders.update_status');
    Route::get('/admin/quan-ly-nhan-vien', [NhanVienController::class, 'danhSachNhanVien'])->name('admin.users.index');
    Route::post('/admin/quan-ly-nhan-vien/them', [NhanVienController::class, 'themNhanVien'])->name('admin.users.store');
    Route::put('/admin/quan-ly-nhan-vien/doi-trang-thai/{id}', [NhanVienController::class, 'doiTrangThaiNhanVien'])->name('admin.users.toggle_status');
    Route::get('/admin/log-hoat-dong', [NhanVienController::class, 'hienThiLogHeThong'])->name('admin.logs.index');
    // PHÂN HỆ THỐNG KÊ BÁO CÁO CHO ADMIN
    Route::get('/admin/thong-ke-bao-cao', [AdminController::class, 'thongKeBaoCao'])->name('admin.reports.index');
    // 1. Route cập nhật dành cho nhân viên (Form dùng chung nhánh else)
Route::put('/kho/cap-nhat/{id}', [NhanVienController::class, 'capNhatSanPham'])->name('kho.update');

// 2. Route cập nhật dành cho Admin (Form dùng chung nhánh if - Sẽ giải quyết triệt để lỗi chưa định nghĩa)
Route::put('/admin/san-pham/cap-nhat/{id}', [NhanVienController::class, 'capNhatSanPham'])->name('admin.products.update');