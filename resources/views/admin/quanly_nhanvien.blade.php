@extends('layouts.app')

@section('content')
<style>
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        @if(Auth::user()->vai_tro === 'admin')
            @include('partials.sidebar_admin')
        @endif

        <div class="col-md-10 p-4 bg-light" style="min-height: 100vh;">
            
            <nav class="navbar navbar-white bg-white shadow-sm mb-4 px-4 py-2 rounded-4">
                <div class="container-fluid p-0">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-users-cog text-danger me-2"></i>QUẢN LÝ TÀI KHOẢN NHÂN VIÊN
                    </h5>
                    <button type="button" class="btn btn-success btn-sm rounded-pill px-4 ms-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalThemNhanVien">
                        <i class="fas fa-user-plus me-1"></i> Cấp tài khoản mới
                    </button>
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="badge bg-danger me-2 rounded-pill">Admin</span>
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-shield fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 fw-bold">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light text-secondary small fw-bold">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 80px;">Mã NV</th>
                                    <th>Họ Và Tên</th>
                                    <th>Email Đăng Nhập</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Vai Trò Quyền</th>
                                    <th>Trạng Thái</th>
                                    <th style="width: 180px;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nhanViens as $nv)
                                <tr>
                                    <td class="ps-4 text-muted">#{{ $nv->id }}</td>
                                    <td class="fw-bold text-dark">{{ $nv->ho_ten }}</td>
                                    <td class="text-secondary">{{ $nv->email }}</td>
                                    <td>{{ $nv->so_dien_thoai }}</td>
                                    <td>
                                        @if($nv->vai_tro === 'admin')
                                            <span class="badge bg-danger rounded-pill px-3 py-1">Quản trị tối cao</span>
                                        @elseif($nv->vai_tro === 'nhanvien_kho')
                                            <span class="badge bg-info text-white rounded-pill px-3 py-1">Nhân viên Kho</span>
                                        @else
                                            <span class="badge bg-primary rounded-pill px-3 py-1">Nhân viên Bán hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(($nv->trang_thai ?? 1) == 1)
                                            <span class="badge bg-success rounded-pill px-3 py-1"><i class="fas fa-check me-1"></i>Đang chạy</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-1"><i class="fas fa-lock me-1"></i>Bị khóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nv->id !== Auth::id())
                                            <form action="{{ route('admin.users.toggle_status', $nv->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                @if(($nv->trang_thai ?? 1) == 1)
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
                                                        <i class="fas fa-user-slash me-1"></i>Khóa lại
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold text-white">
                                                        <i class="fas fa-user-check me-1"></i>Mở khóa
                                                    </button>
                                                @endif
                                            </form>
                                        @else
                                            <span class="text-muted small italic">Đang trực tuyến</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalThemNhanVien" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-start text-dark">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="fw-bold mb-0"><i class="fas fa-user-plus me-2"></i>CẤP TÀI KHOẢN NHÂN VIÊN MỚI</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Họ và tên nhân viên</label>
                        <input type="text" name="ho_ten" class="form-control rounded-3" placeholder="Nhập tên đầy đủ..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Số điện thoại liên hệ</label>
                        <input type="text" name="so_dien_thoai" class="form-control rounded-3" placeholder="Ví dụ: 0352..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Email (Tên đăng nhập hệ thống)</label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="name@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Mật khẩu khởi tạo ban đầu</label>
                        <input type="password" name="mat_khau" class="form-control rounded-3" placeholder="Tối thiểu 6 ký tự..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Phân quyền chức vụ bộ phận</label>
                        <select name="vai_tro" class="form-select rounded-3" required>
                            <option value="">-- Chọn vai trò bộ phận --</option>
                            <option value="nhanvien_kho">📦 Bộ phận quản lý kho hàng</option>
                            <option value="nhanvien_banhang">🛒 Bộ phận bán lẻ tại quầy</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">Kích hoạt tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection