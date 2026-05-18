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
                        <i class="fas fa-history text-danger me-2"></i>NHẬT KÝ HOẠT ĐỘNG HỆ THỐNG
                    </h5>
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="badge bg-danger me-2 rounded-pill">Admin</span>
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-shield fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light text-secondary small fw-bold">
                                <tr>
                                    <th class="ps-4 py-3" style="width: 80px;">Mã Log</th>
                                    <th>Thời Gian Thực</th>
                                    <th>Người Thực Hiện</th>
                                    <th>Bộ Phận / Vai Trò</th>
                                    <th>Hành Động Thực Thi</th>
                                    <th class="text-start" style="width: 40%;">Nội Dung Nhật Ký Chi Tiết</th>
                                    <th>Bảng Liên Quan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4 text-muted">#{{ $log->id }}</td>
                                    <td class="small fw-semibold text-secondary">{{ date('d/m/Y H:i:s', strtotime($log->thoi_gian)) }}</td>
                                    <td class="fw-bold text-dark">{{ $log->ho_ten ?? 'Hệ thống tự động' }}</td>
                                    <td>
                                        @if($log->vai_tro === 'admin')
                                            <span class="badge bg-danger rounded-pill px-2 py-1">Quản trị viên</span>
                                        @elseif($log->vai_tro === 'nhanvien_kho')
                                            <span class="badge bg-info text-white rounded-pill px-2 py-1">Nhân viên Kho</span>
                                        @elseif($log->vai_tro === 'nhanvien_banhang')
                                            <span class="badge bg-primary rounded-pill px-2 py-1">Nhân viên Bán hàng</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-2 py-1">Khách hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->hanh_dong === 'Nhập hàng')
                                            <span class="badge bg-soft-info text-info border border-info rounded-3 px-3 py-1fw-bold">📥 {{ $log->hanh_dong }}</span>
                                        @elseif($log->hanh_dong === 'Bán hàng')
                                            <span class="badge bg-soft-success text-success border border-success rounded-3 px-3 py-1 fw-bold">🛒 {{ $log->hanh_dong }}</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning border border-warning rounded-3 px-3 py-1 fw-bold">📝 {{ $log->hanh_dong }}</span>
                                        @endif
                                    </td>
                                    <td class="text-start text-dark small fw-medium">{{ $log->chi_tiet }}</td>
                                    <td>
                                        <span class="badge bg-light text-muted border small">{{ $log->bang_lien_quan ?? 'none' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                        <p class="mb-0 fw-bold">Hệ thống chưa ghi nhận bất kỳ lịch sử hoạt động nào gần đây.</p>
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