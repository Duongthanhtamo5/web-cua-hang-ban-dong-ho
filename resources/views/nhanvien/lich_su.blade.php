@extends('layouts.app')

@section('content')
<style>
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .logout-btn { background: none; border: none; color: #ffc107; padding: 12px 20px; margin: 5px 15px; width: calc(100% - 30px); text-align: left; }
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
                    <h5 class="fw-bold mb-0">LỊCH SỬ NHẬP KHO HÀNG</h5>
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light text-secondary">
                                <tr>
    <th class="ps-3" style="width: 70px;">STT</th>
    <th>Mã phiếu nhập</th>
    <th>Mã sản phẩm</th>
    <th class="text-start">Tên sản phẩm</th>
    <th>Số lượng nhập</th>
    <th>Giá nhập lô này</th>
    <th>Tổng tiền vốn</th>
    <th>Người nhập</th> </tr>
                            </thead>
                            <tbody>
                                @forelse($lichSu as $index => $ls)
                                <tr>
    <td class="ps-3 text-muted">{{ $index + 1 }}</td>
    <td><span class="badge bg-danger rounded-pill px-3 py-1">PN-{{ $ls->nhap_hang_id }}</span></td>
    <td><span class="badge bg-light text-dark border">#{{ $ls->san_pham_id }}</span></td>
    <td class="text-start fw-bold text-dark">{{ $ls->sanPham->ten_san_pham ?? 'Sản phẩm đã bị xóa' }}</td>
    <td class="fw-bold text-success">+ {{ $ls->so_luong_nhap }} chiếc</td>
    <td class="text-secondary fw-semibold">{{ number_format($ls->gia_nhap, 0, ',', '.') }}đ</td>
    <td class="fw-bold text-dark">{{ number_format($ls->so_luong_nhap * $ls->gia_nhap, 0, ',', '.') }}đ</td>
    
    <td>
        <span class="fw-bold text-secondary small">
            <i class="fas fa-user me-1 text-muted"></i>{{ $ls->nguoiNhap->ho_ten ?? 'N/A' }}
        </span>
    </td>
</tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-muted">Chưa có lịch sử nhập kho nào được ghi nhận.</td>
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