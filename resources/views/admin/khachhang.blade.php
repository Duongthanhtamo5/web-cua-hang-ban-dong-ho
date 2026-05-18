@extends('layouts.app')

@section('content')
<style>
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .search-card { background: #fff; border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
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
                        <i class="fas fa-users text-danger me-2"></i>THỐNG KÊ & QUẢN LÝ KHÁCH HÀNG
                    </h5>
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="badge bg-danger me-2 rounded-pill">Admin</span>
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-shield fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="card search-card mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 rounded-start-3 text-muted">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 rounded-end-3 py-2" 
                                       placeholder="Nhập họ tên hoặc số điện thoại khách hàng cần tra cứu dữ liệu..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger rounded-3 w-100 fw-bold py-2">
                                    <i class="fas fa-filter me-1"></i> Tìm kiếm
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary rounded-3 py-2">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light text-secondary small fw-bold">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 80px;">ID</th>
                                    <th>Tên Khách Hàng</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Email Đăng Ký</th>
                                    <th>Số Đơn Đã Mua</th>
                                    <th>Tổng Tiền Đã Chi</th>
                                    <th>Phân Hạng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($khachHangs as $kh)
                                <tr>
                                    <td class="ps-4 text-muted">#{{ $kh->id }}</td>
                                    <td class="fw-bold text-dark text-start ps-5">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-soft-danger text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #fcebeb;">
                                                <i class="fas fa-user small"></i>
                                            </div>
                                            {{ $kh->ho_ten }}
                                        </div>
                                    </td>
                                    <td class="fw-semibold text-secondary">{{ $kh->so_dien_thoai ?? 'N/A' }}</td>
                                    <td class="text-muted small">{{ $kh->email }}</td>
                                    <td>
                                        <span class="badge bg-dark rounded-pill px-3 py-1fw-bold">
                                            {{ $kh->so_don_hang ?? 0 }} đơn
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        {{ number_format($kh->tong_tien_chi ?? 0, 0, ',', '.') }}đ
                                    </td>
                                    <td>
                                        @if(($kh->tong_tien_chi ?? 0) >= 5000000)
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold shadow-sm">
                                                <i class="fas fa-crown me-1"></i> VIP Member
                                            </span>
                                        @elseif(($kh->tong_tien_chi ?? 0) > 0)
                                            <span class="badge bg-light text-primary border border-primary rounded-pill px-3 py-1 fw-bold">
                                                Thân thiết
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border rounded-pill px-3 py-1">
                                                Mới tạo tài khoản
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-muted">
                                        <i class="fas fa-users-slash fa-3x mb-3 text-secondary"></i>
                                        <p class="mb-0 fw-bold">Hệ thống chưa tìm thấy thông tin khách hàng nào phù hợp.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection