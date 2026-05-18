@extends('layouts.app')

@section('content')
<style>
    /* Style đồng bộ theo file banhang của bạn */
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .logout-btn { background: none; border: none; color: #ffc107; padding: 12px 20px; margin: 5px 15px; width: calc(100% - 30px); text-align: left; }
    .readonly-input { background-color: #e9ecef !important; cursor: not-allowed; }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="container-fluid p-0">
    <div class="row g-0">
        @if(Auth::user()->vai_tro === 'admin')
    @include('partials.sidebar_admin')
@elseif(Auth::user()->vai_tro === 'nhanvien_kho')
    @include('partials.sidebar_kho')
@else
    @include('partials.sidebar_banhang')
@endif

        <div class="col-md-10 bg-light">
            <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm mb-4 px-4 py-2">
                <div class="container-fluid p-0">
                    <h5 class="fw-bold mb-0">QUẢN LÝ KHÁCH HÀNG</h5>
                    <button type="button" class="btn btn-success btn-sm rounded-pill px-4 ms-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalThemKhach">
                        <i class="fas fa-plus-circle me-1"></i> Thêm khách mới
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">ID</th>
                                    <th>Khách hàng</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($khachHangs as $kh)
                                <tr>
                                    <td class="ps-4">#{{ $kh->id }}</td>
                                    <td class="fw-bold text-primary">{{ $kh->ho_ten }}</td>
                                    <td>{{ $kh->so_dien_thoai }}</td>
                                    <td class="text-muted">{{ $kh->email ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary border-0 rounded-circle" data-bs-toggle="modal" data-bs-target="#modalSuaKhach{{ $kh->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
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

<div class="modal fade" id="modalThemKhach" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-start">
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="fw-bold text-success"><i class="fas fa-user-plus me-2"></i>THÊM KHÁCH HÀNG</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Họ và tên</label>
                        <input type="text" name="ho_ten" class="form-control rounded-3" placeholder="Nhập tên..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" class="form-control rounded-3" placeholder="Nhập số điện thoại..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Email</label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="email@example.com">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($khachHangs as $kh)
<div class="modal fade" id="modalSuaKhach{{ $kh->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-start">
            <form action="{{ route('admin.customers.update', $kh->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="fw-bold text-primary"><i class="fas fa-user-edit me-2"></i>SỬA THÔNG TIN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-dark">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Họ và tên</label>
                        <input type="text" name="ho_ten" class="form-control rounded-3" value="{{ $kh->ho_ten }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" class="form-control rounded-3" value="{{ $kh->so_dien_thoai }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Email (Không thể thay đổi)</label>
                        <input type="email" name="email" class="form-control rounded-3 readonly-input" value="{{ $kh->email }}" readonly>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Cập nhật ngay</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection