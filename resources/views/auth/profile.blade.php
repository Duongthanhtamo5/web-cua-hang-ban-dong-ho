@extends('layouts.app')

@section('content')
<style>
    /* Style đồng bộ Hệ thống Sidebar từ file banhang của bạn */
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .logout-btn { background: none; border: none; color: #ffc107; padding: 12px 20px; margin: 5px 15px; width: calc(100% - 30px); text-align: left; }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        
        @if(Auth::user()->vai_tro === 'admin')
            @include('partials.sidebar_admin')
        @elseif(Auth::user()->vai_tro === 'nhanvien_kho')
            @include('partials.sidebar_kho')
        @elseif(Auth::user()->vai_tro === 'nhanvien_banhang')
            @include('partials.sidebar_banhang')
        @endif

        <div class="{{ in_array(Auth::user()->vai_tro, ['admin', 'nhanvien_kho', 'nhanvien_banhang']) ? 'col-md-10' : 'col-md-12' }} bg-light">
            <nav class="navbar navbar-white bg-white shadow-sm mb-4 px-4 py-2">
                <div class="container-fluid p-0">
                    <h5 class="fw-bold mb-0 text-dark">CÀI ĐẶT TÀI KHOẢN</h5>
                    <div class="ms-auto d-flex align-items-center user-pill">
                        @if(Auth::user()->vai_tro === 'admin')
                            <span class="badge bg-danger me-2 rounded-pill">Quản trị viên</span>
                        @elseif(Auth::user()->vai_tro === 'nhanvien_kho')
                            <span class="badge bg-info text-white me-2 rounded-pill">Nhân viên Kho</span>
                        @elseif(Auth::user()->vai_tro === 'nhanvien_banhang')
                            <span class="badge bg-primary me-2 rounded-pill">Nhân viên Bán hàng</span>
                        @else
                            <span class="badge bg-secondary me-2 rounded-pill">Khách hàng</span>
                        @endif
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="container py-2 px-4 mb-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body p-4 p-md-5">
                                <h3 class="fw-bold mb-4">
                                    <i class="fas fa-user-cog me-2 text-danger"></i>THÔNG TIN TÀI KHOẢN
                                </h3>

                                @if(session('success'))
                                    <div class="alert alert-success border-0 shadow-sm rounded-pill px-4 mb-4">
                                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small">Họ và tên</label>
                                            <input type="text" name="ho_ten" class="form-control rounded-pill px-3" value="{{ Auth::user()->ho_ten }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small">Số điện thoại</label>
                                            <input type="text" name="so_dien_thoai" class="form-control rounded-pill px-3" value="{{ Auth::user()->so_dien_thoai }}">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small">Email (Tên đăng nhập)</label>
                                        <input type="email" class="form-control rounded-pill px-3 bg-light" value="{{ Auth::user()->email }}" readonly title="Không thể thay đổi email">
                                    </div>

                                    <div class="p-4 bg-light rounded-4 border">
                                        <h5 class="fw-bold mb-3 text-danger"><i class="fas fa-lock me-2"></i>ĐỔI MẬT KHẨU</h5>
                                        <p class="small text-muted mb-4 italic">* Để trống nếu bạn không muốn thay đổi mật khẩu.</p>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Mật khẩu cũ</label>
                                            <input type="password" name="old_password" class="form-control rounded-pill px-3" placeholder="Xác nhận mật khẩu hiện tại...">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold small">Mật khẩu mới</label>
                                                <input type="password" name="new_password" class="form-control rounded-pill px-3" placeholder="Nhập mật khẩu mới...">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold small">Xác nhận mật khẩu mới</label>
                                                <input type="password" name="new_password_confirmation" class="form-control rounded-pill px-3" placeholder="Nhập lại mật khẩu mới...">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-5 d-flex justify-content-center gap-3">
                                        @if(Auth::user()->vai_tro === 'admin')
                                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-lg px-5 rounded-pill fw-bold">HỦY BỎ</a>
                                        @elseif(Auth::user()->vai_tro === 'nhanvien_kho')
                                            <a href="{{ route('kho.index') }}" class="btn btn-outline-secondary btn-lg px-5 rounded-pill fw-bold">HỦY BỎ</a>
                                        @elseif(Auth::user()->vai_tro === 'nhanvien_banhang')
                                            <a href="{{ route('banhang.index') }}" class="btn btn-outline-secondary btn-lg px-5 rounded-pill fw-bold">HỦY BỎ</a>
                                        @else
                                            <a href="{{ route('trang-chu') }}" class="btn btn-outline-secondary btn-lg px-5 rounded-pill fw-bold">HỦY BỎ</a>
                                        @endif
                                        <button type="submit" class="btn btn-danger btn-lg px-5 rounded-pill fw-bold shadow">LƯU THAY ĐỔI</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection