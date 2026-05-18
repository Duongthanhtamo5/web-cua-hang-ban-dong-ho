@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">
            <i class="fas fa-box-open me-2 text-danger"></i>THEO DÕI ĐƠN HÀNG
        </h3>
        <a href="/" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
    
    @if($donHangs->isEmpty())
        <div class="text-center py-5 bg-white shadow-sm rounded-4">
            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="100" class="mb-3 opacity-25">
            <p class="text-muted">Bạn không có đơn hàng nào đang xử lý.</p>
            <a href="/" class="btn btn-danger rounded-pill px-4">Mua sắm ngay</a>
        </div>
    @else
        @foreach($donHangs as $don)
        <div class="card shadow-sm border-0 mb-5 rounded-4 overflow-hidden">
            <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
                <div>
                    <span class="opacity-75">Mã đơn hàng:</span> 
                    <span class="fw-bold text-warning">#{{ $don->id }}</span>
                </div>
                <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-sync-alt fa-spin me-1"></i> {{ $don->trang_thai }}
                </span>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-4 border-end">
                        <div class="p-2">
                            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>Thông tin nhận hàng
                            </h6>
                            <p class="mb-2 small"><strong>Ngày đặt:</strong> {{ date('d/m/Y H:i', strtotime($don->ngay_lap)) }}</p>
                            <p class="mb-2 small"><strong>Địa chỉ:</strong> {{ $don->dia_chi_giao }}</p>
                            <p class="mb-2 small"><strong>Thanh toán:</strong> <span class="badge bg-light text-dark">{{ $don->loai_hoa_don }}</span></p>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="p-2">
                            <h6 class="fw-bold mb-3 text-primary border-bottom pb-2">
                                <i class="fas fa-shopping-cart me-2"></i>Sản phẩm đã đặt
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light small">
                                        <tr>
                                            <th>Tên sản phẩm</th>
                                            <th class="text-center">SL</th>
                                            <th class="text-end">Đơn giá</th>
                                            <th class="text-end">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($don->chiTiet as $ct)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $ct->sanPham->ten_san_pham }}</td>
                                            <td class="text-center text-muted">x{{ $ct->so_luong }}</td>
                                            <td class="text-end small">{{ number_format($ct->gia_ban_luc_do, 0, ',', '.') }} đ</td>
                                            <td class="text-end fw-bold text-danger">{{ number_format($ct->gia_ban_luc_do * $ct->so_luong, 0, ',', '.') }} đ</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end pt-3 fw-bold fs-5">TỔNG CỘNG:</td>
                                            <td class="text-end pt-3 fw-bold fs-5 text-danger border-top">
                                                {{ number_format($don->tong_tien, 0, ',', '.') }} đ
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="mt-4 p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between mb-2 small fw-bold">
                                    <span>Đang xử lý</span>
                                    <span>Đang giao hàng</span>
                                    <span>Đã nhận hàng</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                         style="width: {{ $don->trang_thai == 'Cho xac nhan' ? '33%' : ($don->trang_thai == 'Dang giao' ? '66%' : '100%') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection