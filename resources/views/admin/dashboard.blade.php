@extends('layouts.app')

@section('content')
<style>
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #343a40; color: #fff; border-left: 4px solid #dc3545; }
    .stat-card { transition: 0.3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .logout-btn { background: none; border: none; color: #ffc107; padding: 12px 20px; margin: 5px 15px; width: calc(100% - 30px); text-align: left; }
</style>

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
            <nav class="navbar navbar-white bg-white shadow-sm mb-4 px-4 py-2">
                <div class="container-fluid p-0">
                    <h5 class="fw-bold mb-0 text-dark">TRANG QUẢN TRỊ (ADMIN DASHBOARD)</h5>
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="badge bg-danger me-2 rounded-pill">Admin</span>
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-shield fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 mb-5">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-4 bg-white stat-card">
                            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Doanh thu thành công</small>
                                    <h4 class="fw-bold text-dark mt-1 mb-0">{{ number_format($doanhThu, 0, ',', '.') }}đ</h4>
                                </div>
                                <div class="bg-success p-3 rounded-circle text-success bg-opacity-10">
                                    <i class="fas fa-wallet fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-4 bg-white stat-card">
                            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Tổng số đơn hàng</small>
                                    <h4 class="fw-bold text-dark mt-1 mb-0">{{ $tongDonHang }} đơn</h4>
                                </div>
                                <div class="bg-primary p-3 rounded-circle text-primary bg-opacity-10">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-4 bg-white stat-card">
                            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Tổng tồn kho hiện tại</small>
                                    <h4 class="fw-bold text-dark mt-1 mb-0">{{ $tongTonKho ?? 0 }} chiếc</h4>
                                </div>
                                <div class="bg-warning p-3 rounded-circle text-warning bg-opacity-10">
                                    <i class="fas fa-boxes fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-4 bg-white stat-card">
                            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Nhân sự hệ thống</small>
                                    <h4 class="fw-bold text-dark mt-1 mb-0">{{ $tongNhanVien }} nhân sự</h4>
                                </div>
                                <div class="bg-danger p-3 rounded-circle text-danger bg-opacity-10">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-history text-danger me-2"></i>Nhật ký hoạt động hệ thống gần đây</h5>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">Xem toàn bộ log</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 text-center">
                                    <thead class="table-light text-muted small">
                                        <tr>
                                            <th>Mã Log</th>
                                            <th>Thời gian</th>
                                            <th>Người thực hiện</th>
                                            <th>Bộ phận / Vai trò</th>
                                            <th class="text-start">Hành động thực thi</th>
                                            <th>Bảng liên quan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                        <tr>
                                            <td class="text-muted">#{{ $log->id }}</td>
                                            <td class="small text-secondary">{{ date('d/m/Y H:i', strtotime($log->thoi_gian)) }}</td>
                                            <td class="fw-bold text-dark">{{ $log->ho_ten }}</td>
                                            <td>
                                                @if($log->vai_tro === 'admin')
                                                    <span class="badge bg-danger rounded-pill px-3 py-1">Quản trị viên</span>
                                                @elseif($log->vai_tro === 'nhanvien_kho')
                                                    <span class="badge bg-info text-white rounded-pill px-3 py-1">Nhân viên kho</span>
                                                @else
                                                    <span class="badge bg-primary rounded-pill px-3 py-1">Nhân viên bán lẻ</span>
                                                @endif
                                            </td>
                                            <td class="text-start fw-semibold text-secondary">{{ $log->hanh_dong }}</td>
                                            <td><span class="badge bg-light text-dark border">`{{ $log->bang_lien_quan }}`</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="py-4 text-muted">Hệ thống chưa ghi nhận log hoạt động nào.</td>
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
    </div>
</div>
@endsection