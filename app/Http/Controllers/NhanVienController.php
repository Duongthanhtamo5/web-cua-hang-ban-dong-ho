<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\HoaDon;
use App\Models\NguoiDung; // Dùng chung bảng người dùng
use App\Models\ChiTietHoaDon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ChiTietNhapHang;

class NhanVienController extends Controller
{
    public function index() {
        $sanPham = SanPham::all();
        $gioHang = session()->get('gioHangNhanVien', []);

        // Tải kèm người dùng và chi tiết (kèm sản phẩm bên trong chi tiết)
        $donHangs = HoaDon::with(['nguoiDung', 'chiTiet.sanPham'])
                          ->orderBy('ngay_lap', 'desc')
                          ->get();

        return view('nhanvien.banhang', compact('sanPham', 'gioHang', 'donHangs'));
    }

    // AJAX tìm khách hàng theo SĐT hoặc Tên
    public function timKhachHang(Request $request) {
        $search = $request->get('query');
        $khachHang = NguoiDung::where('vai_tro', 0) // Chỉ tìm người là khách hàng
            ->where(function($q) use ($search) {
                $q->where('so_dien_thoai', 'LIKE', "%{$search}%")
                  ->orWhere('ho_ten', 'LIKE', "%{$search}%");
            })->get();
        return response()->json($khachHang);
    }

    public function themVaoGio($id) {
        $sp = SanPham::findOrFail($id);
        $gioHang = session()->get('gioHangNhanVien', []);
        if(isset($gioHang[$id])) {
            $gioHang[$id]['so_luong']++;
        } else {
            $gioHang[$id] = [
                "ten" => $sp->ten_san_pham,
                "so_luong" => 1,
                "gia" => $sp->gia_ban,
                "hinh" => $sp->hinh_anh
            ];
        }
        session()->put('gioHangNhanVien', $gioHang);
        return redirect()->back();
    }

    public function capNhatSoLuong(Request $request, $id) {
        $gioHang = session()->get('gioHangNhanVien', []);
        if(isset($gioHang[$id])) {
            $so_luong = $request->so_luong < 1 ? 1 : $request->so_luong;
            $gioHang[$id]['so_luong'] = $so_luong;
            session()->put('gioHangNhanVien', $gioHang);
        }
        return redirect()->back();
    }

    public function xoaSanPham($id) {
        $gioHang = session()->get('gioHangNhanVien', []);
        if(isset($gioHang[$id])) {
            unset($gioHang[$id]);
            session()->put('gioHangNhanVien', $gioHang);
        }
        return redirect()->back();
    }

    public function xacNhanDonHang(Request $request) {
        $gioHang = session()->get('gioHangNhanVien', []);
        if(empty($gioHang)) return redirect()->back()->with('error', 'Giỏ hàng trống!');

        // 1. Tìm hoặc tạo khách hàng mới
        $khachHang = NguoiDung::where('so_dien_thoai', $request->so_dien_thoai)->first();
        if (!$khachHang && $request->so_dien_thoai) {
            $khachHang = NguoiDung::create([
                'ho_ten' => $request->ten_khach ?? 'Khách lẻ',
                'so_dien_thoai' => $request->so_dien_thoai,
                'email' => $request->so_dien_thoai . '@khachhang.com', // Tạo email giả để tránh lỗi unique
                'mat_khau' => Hash::make('123456'), // Mật khẩu mặc định
                'vai_tro' => 0 // Khách hàng
            ]);
        }

        // 2. Lưu Hóa đơn
        $tongTien = 0;
        foreach($gioHang as $item) { $tongTien += $item['gia'] * $item['so_luong']; }

        $hoaDon = new HoaDon();
        $hoaDon->nguoi_dung_id = $khachHang ? $khachHang->id : Auth::id(); // Ưu tiên lưu ID khách
        $hoaDon->ngay_lap = now();
        $hoaDon->tong_tien = $tongTien;
        $hoaDon->trang_thai = 'Da thanh toan';
        $hoaDon->save();

        // 3. Lưu Chi tiết
        foreach($gioHang as $id => $item) {
            $chiTiet = new ChiTietHoaDon();
            $chiTiet->hoa_don_id = $hoaDon->id;
            $chiTiet->san_pham_id = $id;
            $chiTiet->so_luong = $item['so_luong'];
            $chiTiet->gia_ban_luc_do = $item['gia'];
            $chiTiet->save();
        }

        session()->forget('gioHangNhanVien');

        // Bẫy vết Log hoạt động: Bán hàng trực tiếp tại quầy POS
        NguoiDung::ghiLog('Bán hàng', 'Đã lập hóa đơn bán lẻ thành công tại quầy #' . $hoaDon->id . ' với tổng số tiền ' . number_format($tongTien) . 'đ', 'hoa_don');

        return redirect()->route('banhang.index')->with('success', 'Đã chốt đơn thành công!');
    }

    // Hàm cập nhật trạng thái đơn hàng
    public function capNhatTrangThai(Request $request, $id)
    {
        $hoaDon = HoaDon::findOrFail($id);
        
        // Nếu là thao tác hủy đơn
        if ($request->trang_thai == 'Da huy') {
            $request->validate([
                'ly_do_huy' => 'required|min:5'
            ], [
                'ly_do_huy.required' => 'Vui lòng nhập lý do hủy đơn.'
            ]);
            $hoaDon->ly_do_huy = $request->ly_do_huy;
        }
        
        $hoaDon->trang_thai = $request->trang_thai;
        $hoaDon->save();

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    }

    public function storeManual(Request $request) {
        // 1. Tìm hoặc tạo khách hàng mới
        $khach = NguoiDung::firstOrCreate(
            ['so_dien_thoai' => $request->so_dien_thoai],
            ['ho_ten' => $request->ho_ten, 'mat_khau' => Hash::make('123456'), 'vai_tro' => 0]
        );

        // 2. Tạo hóa đơn
        $hoaDon = new HoaDon();
        $hoaDon->nguoi_dung_id = $khach->id;
        $hoaDon->ngay_lap = now();
        $hoaDon->dia_chi_giao = $request->dia_chi_giao;
        $hoaDon->trang_thai = 'Cho xac nhan'; // Đơn ngoài hệ thống mặc định chờ xác nhận
        $hoaDon->tong_tien = 0; 
        $hoaDon->save();

        // 3. Lưu chi tiết và tính tổng tiền
        $total = 0;
        foreach($request->san_pham as $key => $spId) {
            $sp = SanPham::find($spId);
            $sl = $request->so_luong[$key];
            
            ChiTietHoaDon::create([
                'hoa_don_id' => $hoaDon->id,
                'san_pham_id' => $spId,
                'so_luong' => $sl,
                'gia_ban_luc_do' => $sp->gia_ban
            ]);
            $total += $sp->gia_ban * $sl;
        }

        $hoaDon->update(['tong_tien' => $total]);

        // Bẫy vết Log hoạt động: Tạo đơn giao hàng ngoài hệ thống
        NguoiDung::ghiLog('Bán hàng', 'Đã thêm thủ công đơn đặt giao hàng tận nơi #' . $hoaDon->id . ' cho khách hàng ' . $khach->ho_ten, 'hoa_don');

        return redirect()->back()->with('success', 'Đã thêm đơn hàng thành công!');
    }

    // Lưu khách hàng mới
    public function themKhachHangMoi(Request $request) {
        $request->validate([
            'so_dien_thoai' => 'required|unique:nguoi_dung,so_dien_thoai',
            'ho_ten' => 'required',
        ], [
            'so_dien_thoai.unique' => 'Số điện thoại này đã tồn tại trong hệ thống!',
        ]);

        NguoiDung::create([
            'ho_ten' => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'email' => $request->email ?? ($request->so_dien_thoai . '@khachhang.com'),
            'mat_khau' => Hash::make('123456'), // Mật khẩu mặc định
            'vai_tro' => 0
        ]);

        return redirect()->back()->with('success', 'Đã thêm khách hàng mới thành công!');
    }

    public function capNhatKhachHang(Request $request, $id) {
        $khach = NguoiDung::findOrFail($id);
        
        $khach->update([
            'ho_ten' => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật thông tin khách hàng!');
    }

    public function updateProfile(Request $request) {
        $user = Auth::user();
        $user->update([
            'ho_ten' => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
        ]);
        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePassword(Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng!');
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    // Hàm hiển thị danh sách kho (Đã bổ sung lấy Danh mục để chọn trên Form)
    public function danhSachKho(Request $request) {
        // 1. Khởi tạo câu truy vấn kèm theo mối quan hệ danh mục
        $query = SanPham::with('danhMuc');

        // 2. Xử lý chức năng Tìm kiếm theo tên hoặc ID (Nếu có nhập text)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_san_pham', 'LIKE', "%{$search}%")
                  ->orWhere('id', $search); // Tìm chính xác theo ID sản phẩm
            });
        }

        // 3. Xử lý chức năng lọc theo Danh mục (Nếu có chọn danh mục cụ thể)
        if ($request->has('danh_muc_id') && $request->danh_muc_id != '') {
            $query->where('danh_muc_id', $request->danh_muc_id);
        }

        // 4. Thực thi lấy dữ liệu ra theo thứ tự mới nhất
        $sanPhams = $query->orderBy('id', 'desc')->get();
        
        // 5. Lấy toàn bộ danh mục thực tế để hiển thị lên thanh lọc và form thêm
        $danhMucs = \App\Models\DanhMuc::all(); 

        // Truyền dữ liệu ra View
        return view('nhanvien.kho', compact('sanPhams', 'danhMucs'));
    }

    // 1. Hàm lưu phiếu nhập kho và tự động lưu vết lịch sử
    public function storeSanPham(Request $request) {
        $hinhThuc = $request->input('hinh_thuc_nhap', 'cu_san_pham');
        
        // Tạo một mã phiếu nhập ngẫu nhiên hoặc tính theo thời gian (ví dụ: 1001, 1002...) 
        // để điền vào cột nhap_hang_id dưới DB
        $maPhieuNhap = rand(1000, 9999); 

        if ($hinhThuc === 'cu_san_pham') {
            $request->validate([
                'san_pham_id' => 'required',
                'so_luong' => 'required|numeric|min:1',
                'gia_von' => 'required|numeric|min:0',
                'gia_ban' => 'required|numeric|min:0',
            ]);

            $sp = SanPham::findOrFail($request->san_pham_id);
            $soLuongThem = intval($request->so_luong);
            $giaNhapMoi = doubleval($request->gia_von);

            // Tính toán bình quân gia quyền tồn kho
            $tonKhoCu = intval($sp->so_luong_kho ?? 0);
            $giaVonCu = doubleval($sp->gia_von ?? 0);
            $tongSoLuongMoi = $tonKhoCu + $soLuongThem;
            $giaVonMoi = (($tonKhoCu * $giaVonCu) + ($soLuongThem * $giaNhapMoi)) / $tongSoLuongMoi;

            $sp->so_luong_kho = $tongSoLuongMoi;
            $sp->gia_von = $giaVonMoi;
            $sp->gia_ban = $request->gia_ban;
            if ($request->hasFile('hinh_anh')) {
                $sp->hinh_anh = $request->file('hinh_anh')->store('products', 'public');
            }
            $sp->save();

            // Ghi lịch sử nhập thêm hàng có sẵn
            ChiTietNhapHang::create([
                'nhap_hang_id'  => $maPhieuNhap,
                'san_pham_id'   => $sp->id,
                'so_luong_nhap' => $soLuongThem,
                'gia_nhap'      => $giaNhapMoi,
                'nguoi_dung_id' => Auth::id()
            ]);

            // Bẫy vết Log hoạt động: Nhập thêm hàng có sẵn
            NguoiDung::ghiLog('Nhập hàng', 'Đã nhập thêm ' . $soLuongThem . ' chiếc vào mẫu đồng hồ có sẵn ID #' . $sp->id . ' (' . $sp->ten_san_pham . ')', 'chi_tiet_nhap_hang');

            return redirect()->route('kho.index')->with('success', 'Đã nhập thêm hàng vào kho thành công!');

        } else {
            // --- TRƯỜNG HỢP NHẬP SẢN PHẨM MỚI TOÀN BỘ ---
            $request->validate([
                'ten_san_pham' => 'required',
                'gia_von'      => 'required|numeric|min:0',
                'gia_ban'      => 'required|numeric|min:0',
                'danh_muc_id'  => 'required',
            ]);

            $path = null;
            if ($request->hasFile('hinh_anh')) {
                $path = $request->file('hinh_anh')->store('products', 'public');
            }

            // Tạo sản phẩm mới
            $spMoi = SanPham::create([
                'ten_san_pham' => $request->ten_san_pham,
                'so_luong_kho' => $request->so_luong ?? 0,
                'gia_von'      => $request->gia_von,
                'gia_ban'      => $request->gia_ban,
                'hinh_anh'     => $path,
                'danh_muc_id'  => $request->danh_muc_id
            ]);

            // Ghi lịch sử nhập cho sản phẩm mới (Đã sửa lỗi gạch đỏ $spMoi)
            ChiTietNhapHang::create([
                'nhap_hang_id'  => $maPhieuNhap,
                'san_pham_id'   => $spMoi->id,
                'so_luong_nhap' => $request->so_luong ?? 0,
                'gia_nhap'      => $request->gia_von,
                'nguoi_dung_id' => Auth::id()
            ]);

            // Bẫy vết Log hoạt động: Nhập mới hoàn toàn sản phẩm
            NguoiDung::ghiLog('Nhập hàng', 'Đã khai báo và nhập kho mới hoàn toàn mẫu đồng hồ: ' . $request->ten_san_pham . ' (Số lượng: ' . ($request->so_luong ?? 0) . ' chiếc)', 'san_pham');

            return redirect()->route('kho.index')->with('success', 'Đã thêm mới sản phẩm vào kho thành công!');
        }
    }

    // 2. Hàm lấy dữ liệu lịch sử đổ ra View
    public function lichSuNhap() {
        $lichSu = ChiTietNhapHang::with(['sanPham', 'nguoiNhap'])->orderBy('id', 'desc')->get();
        return view('nhanvien.lich_su', compact('lichSu'));
    }
        
    // Hàm nhập thêm hàng (Xử lý logic cộng dồn số lượng)
    public function nhapThemHang(Request $request, $id) {
        $sp = SanPham::findOrFail($id);
        $sp->so_luong += $request->so_luong_them;
        $sp->save();

        return redirect()->back()->with('success', 'Đã cập nhật số lượng cho ' . $sp->ten_san_pham);
    }

    // Hàm hiển thị giao diện trang Nhập kho mới
    public function showNhapHang() {
        $danhMucs = \App\Models\DanhMuc::all();
        $sanPhams = SanPham::orderBy('id', 'desc')->get();
        return view('nhanvien.nhap', compact('danhMucs', 'sanPhams'));
    }

    // Hàm hiển thị trang thông tin tài khoản
    public function showProfile() {
        return view('auth.profile'); 
    }

    // 💡 HÀM ĐÃ ĐƯỢC CHUẨN HÓA BỘ LỌC ĐA KÊNH VÀ TRỎ ĐÚNG THƯ MỤC VIEW ĐƯỢC TẠO MỚI
    public function quanLyDonHang(Request $request)
    {
        // 1. Khởi tạo câu lệnh truy vấn lấy Hóa đơn kèm mối quan hệ thực tế dưới SQL
        $query = HoaDon::with(['nguoiDung', 'chiTiet.sanPham'])->orderBy('id', 'desc');

        // 2. Bộ lọc thông minh: Phân biệt đơn bán tại quầy POS và đơn đặt giao
        if ($request->filled('loai_don')) {
            if ($request->loai_don == 'tai_cua_hang') {
                $query->where(function($q) {
                    $q->whereNull('dia_chi_giao')
                      ->orWhere('dia_chi_giao', '');
                });
            } elseif ($request->loai_don == 'dat_giao') {
                $query->whereNotNull('dia_chi_giao')
                      ->where('dia_chi_giao', '!=', '');
            }
        }

        // 3. Bộ lọc: Trạng thái đơn hàng (Cho xac nhan, Dang giao, Da thanh toan, Da huy)
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // 4. Bộ lọc: Tìm kiếm theo Mã đơn hàng hoặc Số điện thoại khách hàng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('nguoiDung', function($userQuery) use ($search) {
                      $userQuery->where('so_dien_thoai', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 5. Thực thi câu lệnh SQL để lấy danh sách
        $donHangs = $query->get();
        $allSanPham = SanPham::all();

        // 💡 QUAN TRỌNG: Sửa đường dẫn từ 'quanly_donhang' thành 'admin.quanly_donhang' để nhận diện đúng thư mục mới
        return view('admin.quanly_donhang', compact('donHangs', 'allSanPham'));
    }

    // Hàm bổ trợ cập nhật nhanh trạng thái đơn hàng từ giao diện Admin/Nhân viên
    public function capNhatTrangThaiDon(Request $request, $id) 
    {
        // Kiểm tra xem đơn hàng có tồn tại không
        $donHang = HoaDon::findOrFail($id);
        
        // Nhận dữ liệu trạng thái mới gửi lên từ thẻ select
        $donHang->trang_thai = $request->trang_thai_moi;
        
        // Lưu thay đổi xuống cơ sở dữ liệu SQL
        $donHang->save();

        // Quay lại trang trước đó kèm theo thông báo thành công xanh mượt
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng #' . $id . ' thành công!');
    }

    // 1. Hiển thị danh sách toàn bộ nhân sự hệ thống
    public function danhSachNhanVien() {
        // Chỉ lấy các tài khoản là nhân sự hệ thống, bỏ qua vai trò khách hàng
        $nhanViens = NguoiDung::whereIn('vai_tro', ['admin', 'nhanvien_kho', 'nhanvien_banhang'])
                              ->orderBy('id', 'desc')
                              ->get();
        return view('admin.quanly_nhanvien', compact('nhanViens'));
    }

    // 2. Thêm mới tài khoản cấp quyền nhân sự
    public function themNhanVien(Request $request) {
        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:nguoi_dung,email',
            'so_dien_thoai' => 'required|string|max:15',
            'mat_khau' => 'required|min:6',
            'vai_tro' => 'required|in:nhanvien_kho,nhanvien_banhang'
        ], [
            'email.unique' => 'Địa chỉ Email đăng nhập này đã tồn tại trên hệ thống!',
            'mat_khau.min' => 'Mật khẩu thiết lập phải có độ dài từ 6 ký tự trở lên.'
        ]);

        NguoiDung::create([
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'mat_khau' => Hash::make($request->mat_khau), // Mã hóa bảo mật mật khẩu
            'vai_tro' => $request->vai_tro,
            'trang_thai' => 1 // Mặc định kích hoạt hoạt động
        ]);

        return redirect()->back()->with('success', 'Đã cấp tài khoản nhân sự mới thành công!');
    }

    // 3. Khóa hoặc Mở khóa tài khoản nhân viên chỉ với 1 Click
    public function doiTrangThaiNhanVien($id) {
        $nhanVien = NguoiDung::findOrFail($id);
        
        // Không cho phép Admin tự khóa chính mình gây lỗi hệ thống
        if ($nhanVien->id === Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không được phép tự khóa tài khoản quản trị của chính mình!');
        }

        // Đảo ngược trạng thái: Nếu đang 1 (mở) -> 0 (khóa), nếu đang 0 -> 1
        $nhanVien->trang_thai = $nhanVien->trang_thai == 1 ? 0 : 1;
        $nhanVien->save();

        $thongBao = $nhanVien->trang_thai == 1 ? 'Đã mở khóa tài khoản thành công!' : 'Đã khóa tài khoản nhân viên thành công!';
        return redirect()->back()->with('success', $thongBao);
    }

    // Hàm Quản lý khách hàng tính toán số đơn và số tiền tích lũy
    public function danhSachKhachHang(Request $request) 
    {
        // 1. Khởi tạo truy vấn lọc tài khoản Khách hàng (vai_tro = 0)
        // Sử dụng mối quan hệ 'hoaDons' vừa khai báo trong Model để đếm đơn và tính tổng tiền
        $query = NguoiDung::where('vai_tro', 0)
            ->withCount(['hoaDons as so_don_hang' => function($q) {
                // Chỉ đếm các đơn hàng đã hoàn tất thanh toán hoặc đang giao (tùy nhu cầu của bạn)
                $q->whereIn('trang_thai', ['Da thanh toan', 'Dang giao']);
            }])
            ->withSum(['hoaDons as tong_tien_chi' => function($q) {
                // Tính tổng tiền dựa trên cột tong_tien của bảng hoa_don
                $q->where('trang_thai', 'Da thanh toan');
            }], 'tong_tien'); // Lấy tổng của cột tong_tien

        // 2. Tính năng bộ lọc tìm kiếm theo Tên hoặc Số điện thoại
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ho_ten', 'LIKE', "%{$search}%")
                  ->orWhere('so_dien_thoai', 'LIKE', "%{$search}%");
            });
        }

        // 3. Sắp xếp danh sách khách hàng mới lên đầu
        $khachHangs = $query->orderBy('id', 'desc')->get();

        // 4. Trả kết quả ra view
        return view('admin.khachhang', compact('khachHangs'));
    }

    // 💡 BỔ SUNG: Hàm điều hướng lấy dữ liệu nhật ký hệ thống hoạt động thời gian thực đổ ra View Admin

    // Hàm lấy danh sách nhật ký hoạt động hệ thống đổ ra cho Admin
   // Hàm hiển thị trang Nhật ký hoạt động dành riêng cho Admin
    public function hienThiLogHeThong()
    {
        // Kiểm tra dữ liệu Log từ bảng hệ thống và kết nối tên người thực hiện
        $logs = \DB::table('he_thong_log')
            ->leftJoin('nguoi_dung', 'he_thong_log.nguoi_dung_id', '=', 'nguoi_dung.id')
            ->select('he_thong_log.*', 'nguoi_dung.ho_ten', 'nguoi_dung.vai_tro')
            ->orderBy('he_thong_log.id', 'desc')
            ->get();

        // Trả về giao diện nằm trong thư mục views/admin/
        return view('admin.quanly_log', compact('logs'));
    }
    // 💡 BỔ SUNG: Hàm cập nhật thông tin và tải ảnh mới cho mẫu đồng hồ
    // 💡 Hãy thay thế hoặc kiểm tra lại hàm capNhatSanPham khớp với cột so_luong_kho
    // 💡 HÀM CẬP NHẬT SẢN PHẨM CHUẨN: Tự động tạo thư mục và lưu ảnh thật vào ổ cứng
    public function capNhatSanPham(Request $request, $id)
    {
        $request->validate([
            'ten_san_pham' => 'required|string|max:255',
            'danh_muc_id'  => 'required|integer',
            'gia_ban'      => 'required|numeric|min:0',
            'hinh_anh'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $sp = SanPham::findOrFail($id);

        // XỬ LÝ LƯU TỆP TIN ẢNH THẬT XUỐNG Ổ CỨNG
        if ($request->hasFile('hinh_anh')) {
    $file = $request->file('hinh_anh');

    // Tạo tên file duy nhất dựa theo thời gian để tránh trùng lặp
    $fileName = time() . '_' . $file->getClientOriginalName();

    // Di chuyển file ảnh thật vào thẳng thư mục public/products trên máy tính
    $file->move(public_path('products'), $fileName);

    // Lưu lại chuỗi tên file vào database
    $sp->hinh_anh = $fileName;
}

        // Cập nhật các trường thông tin cơ bản theo cấu trúc Database mới
        $sp->ten_san_pham = $request->ten_san_pham;
        $sp->danh_muc_id = $request->danh_muc_id;
        $sp->gia_ban = $request->gia_ban;
        
        // Xử lý cộng dồn kho nếu form dùng chung có truyền số lượng nhập thêm
        if ($request->filled('so_luong_them') && intval($request->so_luong_them) > 0) {
            $sp->so_luong_kho += intval($request->so_luong_them);
        }

        $sp->save();

        // Ghi nhật ký hệ thống để Admin tiện theo dõi
        NguoiDung::ghiLog('Cập nhật', 'Đã thay đổi thông tin/hình ảnh chi tiết của mẫu đồng hồ ID #' . $sp->id . ' (' . $sp->ten_san_pham . ')', 'san_pham');

        return redirect()->route('kho.index')->with('success', 'Đã lưu và cập nhật hình ảnh sản phẩm thành công!');
    }
}