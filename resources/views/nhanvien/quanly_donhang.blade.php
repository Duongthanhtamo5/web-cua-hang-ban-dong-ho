@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm">
        <h4 class="fw-bold mb-0 text-danger"><i class="fas fa-tasks me-2"></i>QUẢN LÝ TIẾN ĐỘ ĐƠN HÀNG</h4>
        <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalThemDonHang">
            <i class="fas fa-plus-circle me-2"></i>Thêm đơn hàng giao
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Mã Đơn</th>
                            <th>Khách Hàng</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                            <th class="text-center">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donHangs as $don)
                        <tr>
                            <td class="ps-3 fw-bold">#{{ $don->id }}</td>
                            <td>
                                <a href="javascript:void(0)" 
                                   class="text-decoration-none fw-bold text-primary" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#modalChiTiet{{ $don->id }}">
                                   {{ $don->nguoiDung->ho_ten ?? 'Khách lẻ' }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $don->nguoiDung->so_dien_thoai ?? '' }}</small>
                            </td>
                            <td class="text-danger fw-bold">{{ number_format($don->tong_tien, 0, ',', '.') }}đ</td>
                            <td>
                                <span class="badge rounded-pill 
                                    {{ $don->trang_thai == 'Cho xac nhan' ? 'bg-warning text-dark' : '' }}
                                    {{ $don->trang_thai == 'Dang giao' ? 'bg-primary' : '' }}
                                    {{ $don->trang_thai == 'Da thanh toan' ? 'bg-success' : '' }}
                                    {{ $don->trang_thai == 'Da huy' ? 'bg-danger' : '' }}">
                                    {{ $don->trang_thai }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    <form action="{{ route('admin.orders.update', $don->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="Dang giao">
                                        <button type="submit" class="btn btn-sm btn-outline-primary border-0" title="Giao hàng"><i class="fas fa-shipping-fast"></i></button>
                                    </form>
                                    <form action="{{ route('admin.orders.update', $don->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="Da thanh toan">
                                        <button type="submit" class="btn btn-sm btn-outline-success border-0" title="Đã thu tiền"><i class="fas fa-check"></i></button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger border-0" data-bs-toggle="modal" data-bs-target="#modalHuy{{ $don->id }}" title="Hủy đơn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalChiTiet{{ $don->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4 text-dark">
                                    <div class="modal-header bg-light border-0">
                                        <h5 class="fw-bold mb-0">CHI TIẾT ĐƠN HÀNG #{{ $don->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4 text-start">
                                        <div class="mb-3">
                                            <p class="mb-1"><strong>Khách hàng:</strong> {{ $don->nguoiDung->ho_ten ?? 'Khách lẻ' }}</p>
                                            <p class="mb-1"><strong>Ngày đặt:</strong> {{ $don->ngay_lap }}</p>
                                        </div>
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>SL</th>
                                                    <th>Giá</th>
                                                    <th>Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($don->chiTiet as $item)
                                                <tr>
                                                    <td>{{ $item->sanPham->ten_san_pham ?? 'Sản phẩm không tồn tại' }}</td>
                                                    <td class="text-center">{{ $item->so_luong }}</td>
                                                    <td class="text-end">{{ number_format($item->gia_ban_luc_do, 0, ',', '.') }}đ</td>
                                                    <td class="text-end fw-bold">{{ number_format($item->so_luong * $item->gia_ban_luc_do, 0, ',', '.') }}đ</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="text-end mt-3">
                                            <h5 class="fw-bold text-danger">TỔNG CỘNG: {{ number_format($don->tong_tien, 0, ',', '.') }}đ</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalHuy{{ $don->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4 text-dark text-start">
                                    <form action="{{ route('admin.orders.update', $don->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="trang_thai" value="Da huy">
                                        <div class="modal-header border-0">
                                            <h5 class="fw-bold text-dark"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Lý do hủy đơn #{{ $don->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-3">
                                            <textarea name="ly_do_huy" class="form-control rounded-3" rows="3" placeholder="Lý do..." required></textarea>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-danger rounded-pill px-4">Xác nhận hủy đơn</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalThemDonHang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow rounded-4 text-dark text-start">
            <form action="{{ route('admin.orders.store_manual') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="fw-bold mb-0">THÊM ĐƠN HÀNG NGOÀI HỆ THỐNG</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">Số điện thoại khách</label>
                            <input type="text" name="so_dien_thoai" class="form-control rounded-3" placeholder="0123..." required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">Tên khách hàng</label>
                            <input type="text" name="ho_ten" class="form-control rounded-3" placeholder="Tên khách" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold small">Địa chỉ giao hàng</label>
                            <textarea name="dia_chi_giao" class="form-control rounded-3" rows="2" placeholder="Địa chỉ..." required></textarea>
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3">Chọn sản phẩm:</h6>
                    <div id="item-container">
                        <div class="row mb-2">
                            <div class="col-md-8">
                                <select name="san_pham[]" class="form-select rounded-3" required>
                                    <option value="">-- Chọn sản phẩm --</option>
                                    @foreach($allSanPham as $sp)
                                        <option value="{{ $sp->id }}">{{ $sp->ten_san_pham }} - {{ number_format($sp->gia_ban) }}đ</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="so_luong[]" class="form-control rounded-3" value="1" min="1">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success mt-2 rounded-pill" onclick="addItem()">+ Thêm món</button>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5">Lưu đơn hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addItem() {
        var container = document.getElementById('item-container');
        var items = container.getElementsByClassName('row');
        var newItem = items[0].cloneNode(true);
        newItem.querySelector('select').value = "";
        newItem.querySelector('input').value = "1";
        container.appendChild(newItem);
    }
</script>
@endsection