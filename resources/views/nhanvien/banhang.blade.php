@extends('layouts.app')

@section('content')
<style>
    /* Sidebar & Header Styles */
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    
    /* POS & Order Management Styles */
    .product-card { transition: 0.3s; border: 1px solid #eee !important; border-radius: 15px; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; border-color: #dc3545 !important; }
    .status-badge { font-size: 0.75rem; padding: 6px 12px; font-weight: 600; }
    .btn-action { width: 35px; height: 35px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; margin: 0 2px; transition: 0.2s; }
    #customerList { z-index: 1050; max-height: 200px; overflow-y: auto; }
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
            <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm mb-4 px-4 py-2">
                <div class="container-fluid">
                    <h5 class="fw-bold mb-0">
                        {{ request('view') == 'orders' ? 'QUẢN LÝ TIẾN ĐỘ ĐƠN HÀNG' : 'QUẦY BÁN HÀNG' }}
                    </h5>
                    
                    <div class="ms-auto d-flex align-items-center user-pill">
                        <div class="text-end me-2">
                            <small class="text-muted d-block" style="font-size: 0.7rem;">Nhân viên đang trực</small>
                            <span class="fw-bold text-dark small">{{ Auth::user()->ho_ten }}</span>
                        </div>
                        <i class="fas fa-user-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4">
                @if(request('view') == 'orders')
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-3 py-3">Mã đơn</th>
                                            <th>Khách hàng</th>
                                            <th>Ngày lập</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($donHangs as $don)
                                        <tr>
                                            <td class="ps-3 fw-bold">#{{ $don->id }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $don->nguoiDung->ho_ten ?? 'Khách lẻ' }}</div>
                                                <small class="text-muted">{{ $don->nguoiDung->so_dien_thoai ?? '' }}</small>
                                            </td>
                                            <td>{{ $don->ngay_lap }}</td>
                                            <td class="text-danger fw-bold">{{ number_format($don->tong_tien, 0, ',', '.') }}đ</td>
                                            <td>
                                                <span class="badge rounded-pill status-badge 
                                                    {{ $don->trang_thai == 'Cho xac nhan' ? 'bg-warning text-dark' : '' }}
                                                    {{ $don->trang_thai == 'Dang giao' ? 'bg-primary' : '' }}
                                                    {{ $don->trang_thai == 'Da thanh toan' ? 'bg-success' : '' }}
                                                    {{ $don->trang_thai == 'Da huy' ? 'bg-danger' : '' }}">
                                                    {{ $don->trang_thai }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    @if($don->trang_thai == 'Cho xac nhan')
                                                    <form action="{{ route('admin.orders.update', $don->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="trang_thai" value="Dang giao">
                                                        <button class="btn btn-sm btn-primary btn-action" title="Giao hàng"><i class="fas fa-truck"></i></button>
                                                    </form>
                                                    @endif

                                                    @if($don->trang_thai == 'Dang giao')
                                                    <form action="{{ route('admin.orders.update', $don->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="trang_thai" value="Da thanh toan">
                                                        <button class="btn btn-sm btn-success btn-action" title="Hoàn tất"><i class="fas fa-check"></i></button>
                                                    </form>
                                                    @endif

                                                    @if($don->trang_thai != 'Da thanh toan' && $don->trang_thai != 'Da huy')
                                                    <button class="btn btn-sm btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#modalHuyDon{{ $don->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="modalHuyDon{{ $don->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow rounded-4">
                                                    <form action="{{ route('admin.orders.update', $don->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="trang_thai" value="Da huy">
                                                        <div class="modal-header border-0 p-4 pb-0">
                                                            <h5 class="fw-bold mb-0">Lý do hủy đơn #{{ $don->id }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <textarea name="ly_do_huy" class="form-control rounded-3" rows="3" placeholder="Vui lòng nhập lý do (bắt buộc)..." required></textarea>
                                                        </div>
                                                        <div class="modal-footer border-0 p-4 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                                                            <button type="submit" class="btn btn-danger rounded-pill px-4">Xác nhận hủy</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <tr><td colspan="6" class="text-center py-5 text-muted">Không có đơn hàng nào.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white py-3 border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold mb-0">SẢN PHẨM</h5>
                                        <input type="text" class="form-control w-50 rounded-pill" placeholder="Tìm đồng hồ...">
                                    </div>
                                </div>
                                <div class="card-body overflow-auto" style="max-height: 600px;">
                                    <div class="row g-3">
                                        @foreach($sanPham as $sp)
                                        <div class="col-md-4">
                                            <div class="card h-100 product-card text-center p-3">
                                                @if($sp->hinh_anh)
                                                    <img src="{{ \Illuminate\Support\Str::contains($sp->hinh_anh, 'products/') ? asset('storage/' . $sp->hinh_anh) : asset('products/' . $sp->hinh_anh) }}" 
                                                         class="card-img-top object-fit-cover rounded-3 border" 
                                                         style="height: 140px; object-fit: cover;" 
                                                         alt="{{ $sp->ten_san_pham }}">
                                                @else
                                                    <img src="https://placehold.co/150" class="card-img-top object-fit-cover rounded-3" style="height: 140px;" alt="No image">
                                                @endif
                                                <div class="card-body p-2 d-flex flex-column justify-content-between">
                                                    <h6 class="small fw-bold text-dark text-truncate mt-2" title="{{ $sp->ten_san_pham }}">{{ $sp->ten_san_pham }}</h6>
                                                    <p class="text-danger fw-bold mb-2 small">{{ number_format($sp->gia_ban, 0, ',', '.') }}đ</p>
                                                    <a href="{{ route('banhang.giohang.them', $sp->id) }}" class="btn btn-danger btn-sm rounded-pill w-100 fw-bold">Thêm vào đơn</a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 80px;">
                                <div class="card-header bg-dark text-white py-3 rounded-top-4">
                                    <h5 class="fw-bold mb-0"><i class="fas fa-shopping-cart me-2"></i>ĐƠN HÀNG ĐANG LẬP</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 280px;">
                                        <table class="table align-middle table-hover mb-0">
                                            <tbody>
                                                @php $total = 0; @endphp
                                                @forelse($gioHang as $id => $item)
                                                    @php $subtotal = $item['gia'] * $item['so_luong']; $total += $subtotal; @endphp
                                                    <tr>
                                                        <td class="ps-3" style="width: 60px;">
                                                            @if(isset($item['hinh']))
                                                                <img src="{{ \Illuminate\Support\Str::contains($item['hinh'], 'products/') ? asset('storage/' . $item['hinh']) : asset('products/' . $item['hinh']) }}" 
                                                                     width="40" height="40" class="rounded object-fit-cover border shadow-sm">
                                                            @else
                                                                <img src="https://placehold.co/40" width="40" height="40" class="rounded border">
                                                            @endif
                                                        </td>
                                                        <td class="small fw-bold text-dark">{{ $item['ten'] }}</td>
                                                        <td style="width: 75px;">
                                                            <form action="{{ route('banhang.capnhat', $id) }}" method="POST">
                                                                @csrf
                                                                <input type="number" name="so_luong" value="{{ $item['so_luong'] }}" class="form-control form-control-sm text-center rounded-3 p-1 fw-bold" onchange="this.form.submit()" min="1">
                                                            </form>
                                                        </td>
                                                        <td class="text-end fw-bold text-danger small" style="width: 100px;">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                                        <td class="text-center" style="width: 35px;"><a href="{{ route('banhang.xoa', $id) }}" class="text-muted"><i class="fas fa-times-circle fs-5 hover-danger"></i></a></td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="5" class="text-center py-5 text-muted fw-bold"><i class="fas fa-folder-open me-2"></i>Giỏ hàng trống</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-white p-4 rounded-bottom-4 border-top">
                                    <form action="{{ route('banhang.xacnhan') }}" method="POST">
                                        @csrf
                                        <div class="mb-3 bg-p-3 rounded-3 p-2 bg-light">
                                            <label class="form-label small fw-bold text-secondary mb-1">Tra cứu khách hàng thành viên</label>
                                            <input type="text" name="so_dien_thoai" id="sdt_khach" class="form-control rounded-pill mb-2 border-0 shadow-sm" placeholder="Nhập số điện thoại tra cứu..." autocomplete="off">
                                            <div id="customerList" class="list-group shadow"></div>
                                            <input type="text" name="ten_khach" id="ten_khach" class="form-control rounded-pill border-0 shadow-sm" placeholder="Tên khách lẻ / Khách mới...">
                                        </div>
                                        <div class="d-flex justify-content-between mb-3 align-items-center">
                                            <span class="fw-bold text-dark">TỔNG TIỀN THANH TOÁN:</span>
                                            <span class="text-danger fs-3 fw-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill fw-bold shadow-sm py-2">
                                            <i class="fas fa-shopping-basket me-2"></i>XÁC NHẬN CHỐT ĐƠN HÀNG
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function focusKhachHang() {
        if($("#sdt_khach").length) $("#sdt_khach").focus();
        else window.location.href = "{{ route('banhang.index', ['view' => 'pos']) }}";
    }

    $(document).ready(function(){
        $('#sdt_khach').on('keyup', function(){
            let query = $(this).val();
            if(query.length >= 3) {
                $.ajax({
                    url: "{{ route('banhang.timkhach') }}",
                    type: "GET",
                    data: {'query': query},
                    success: function(data){
                        $('#customerList').html('').show();
                        data.forEach(function(kh){
                            $('#customerList').append(`<a href="#" class="list-group-item list-group-item-action select-kh" data-ten="${kh.ho_ten}" data-sdt="${kh.so_dien_thoai}"><i class="fas fa-user me-2 text-muted"></i>${kh.so_dien_thoai} - <strong>${kh.ho_ten}</strong></a>`);
                        });
                    }
                });
            } else { $('#customerList').hide(); }
        });

        $(document).on('click', '.select-kh', function(e){
            e.preventDefault();
            $('#sdt_khach').val($(this).data('sdt'));
            $('#ten_khach').val($(this).data('ten'));
            $('#customerList').hide();
        });
    });
</script>
@endsection