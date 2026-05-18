@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 bg-danger text-white p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-3 text-danger me-3">
                        <i class="fas fa-wallet fs-2"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 opacity-75">Tổng chi tiêu</h6>
                        <h3 class="fw-bold mb-0">{{ number_format($tongChiTieu, 0, ',', '.') }} đ</h3>
                    </div>
                </div>
                <hr class="my-3">
                <p class="small mb-0">* Chỉ tính các đơn hàng đã giao thành công.</p>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow border-0 rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-dark m-0" style="font-size: 1.5rem;">LỊCH SỬ GIAO DỊCH</h3>
    
    <a href="/" class="btn btn-outline-danger rounded-pill px-4 fw-bold btn-sm shadow-sm transition">
        <i class="fas fa-arrow-left me-2"></i> Quay lại mua hàng
    </a>
</div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày mua</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donHangs as $don)
                            <tr>
                                <td class="fw-bold">#{{ $don->id }}</td>
                                <td>{{ date('d/m/Y', strtotime($don->ngay_lap)) }}</td>
                                <td class="text-danger fw-bold">{{ number_format($don->tong_tien, 0, ',', '.') }} đ</td>
                                <td>
                                    @if($don->trang_thai == 'Da giao')
                                        <span class="badge bg-success px-3 rounded-pill">Thành công</span>
                                    @elseif($don->trang_thai == 'Da huy')
                                        <span class="badge bg-secondary px-3 rounded-pill">Đã hủy</span>
                                    @else
                                        <span class="badge bg-info px-3 rounded-pill">Đang xử lý</span>
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
@endsection