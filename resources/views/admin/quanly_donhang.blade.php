@extends('layouts.app')

@section('content')
<style>
    /* Style đồng bộ Hệ thống Sidebar từ file layouts của bạn */
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .filter-card { background: #fff; border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
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
                        <i class="fas fa-tasks text-danger me-2"></i>HỆ THỐNG QUẢN LÝ ĐƠN HÀNG TOÀN DIỆN (ADMIN)
                    </h5>
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

            <div class="card filter-card mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary mb-1">Hình thức mua hàng</label>
                            <select name="loai_don" class="form-select rounded-3">
                                <option value="">-- Tất cả loại đơn --</option>
                                <option value="tai_cua_hang" {{ request('loai_don') == 'tai_cua_hang' ? 'selected' : '' }}>🛒 Bán tại cửa hàng (POS)</option>
                                <option value="dat_giao" {{ request('loai_don') == 'dat_giao' ? 'selected' : '' }}>🚚 Đơn đặt giao hàng</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary mb-1">Trạng thái đơn hàng</label>
                            <select name="trang_thai" class="form-select rounded-3">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="Da thanh toan" {{ request('trang_thai') == 'Da thanh toan' ? 'selected' : '' }}>✅ Đã thanh toán / Hoàn tất</option>
                                <option value="Cho xac nhan" {{ request('trang_thai') == 'Cho xac nhan' ? 'selected' : '' }}>⏳ Chờ xác nhận</option>
                                <option value="Dang giao" {{ request('trang_thai') == 'Dang giao' ? 'selected' : '' }}>🚚 Đang giao hàng</option>
                                <option value="Da huy" {{ request('trang_thai') == 'Da huy' ? 'selected' : '' }}>❌ Đã hủy đơn</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold text-secondary mb-1">Từ khóa tìm kiếm</label>
                            <input type="text" name="search" class="form-control rounded-3" placeholder="Nhập mã đơn hàng hoặc SĐT khách..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger rounded-3 w-100 fw-bold">
                                    <i class="fas fa-filter me-1"></i>Lọc
                                </button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-3" title="Xóa bộ lọc">
                                    <i class="fas fa-undo"></i>
                                </a>
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
                                    <th class="ps-4 py-3" style="width: 100px;">Mã Đơn</th>
                                    <th>Khách Hàng</th>
                                    <th>Kênh Bán Hàng</th>
                                    <th class="text-start">Chi Tiết Sản Phẩm Mua</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th style="width: 200px;">Cập Nhật Nhanh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donHangs as $don)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#{{ $don->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $don->nguoiDung->ho_ten ?? 'Khách vãng lai' }}</div>
                                        <small class="text-muted"><i class="fas fa-phone me-1"></i>{{ $don->nguoiDung->so_dien_thoai ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if(empty($don->dia_chi_giao) || $don->dia_chi_giao == '')
                                            <span class="badge bg-info text-dark rounded-pill px-3 py-1 fw-bold">
                                                <i class="fas fa-store me-1"></i>Tại cửa hàng
                                            </span>
                                        @else
                                            <span class="badge bg-purple text-white rounded-pill px-3 py-1 fw-bold" style="background-color: #6f42c1;">
                                                <i class="fas fa-shipping-fast me-1"></i>Đặt giao hàng
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        <ul class="list-unstyled mb-0 small text-secondary">
                                            @foreach($don->chiTiet as $item)
                                                <li>• {{ $item->sanPham->ten_san_pham ?? 'Sản phẩm không tồn tại' }} 
                                                    <span class="text-danger fw-bold">x{{ $item->so_luong }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="fw-bold text-danger">{{ number_format($don->tong_tien, 0, ',', '.') }}đ</td>
                                    <td>
                                        @if($don->trang_thai == 'Cho xac nhan')
                                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Chờ xác nhận</span>
                                        @elseif($don->trang_thai == 'Dang giao')
                                            <span class="badge bg-primary px-3 py-1 rounded-pill">Đang giao</span>
                                        @elseif($don->trang_thai == 'Da thanh toan')
                                            <span class="badge bg-success px-3 py-1 rounded-pill">Đã thanh toán</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-1 rounded-pill">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.orders.update_status', $don->id) }}" method="POST" class="d-flex gap-1 justify-content-center">
                                            @csrf
                                            @method('PUT')
                                            <select name="trang_thai_moi" class="form-select form-select-sm rounded-2" style="width: 130px;">
                                                <option value="Cho xac nhan" {{ $don->trang_thai == 'Cho xac nhan' ? 'selected' : '' }}>Chờ xác nhận</option>
                                                <option value="Dang giao" {{ $don->trang_thai == 'Dang giao' ? 'selected' : '' }}>Đang giao</option>
                                                <option value="Da thanh toan" {{ $don->trang_thai == 'Da thanh toan' ? 'selected' : '' }}>Đã hoàn thành</option>
                                                <option value="Da huy" {{ $don->trang_thai == 'Da huy' ? 'selected' : '' }}>Hủy đơn</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-dark rounded-2" title="Lưu trạng thái"><i class="fas fa-save"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                                        <p class="mb-0 fw-bold">Hệ thống chưa có đơn hàng nào khớp với bộ lọc yêu cầu.</p>
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