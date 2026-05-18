<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Đồng hồ Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cart-img { width: 80px; height: 80px; object-fit: contain; }
        .table-v-align td { vertical-align: middle; }
    </style>
</head>
<body class="bg-light">

    <header class="bg-white py-3 border-bottom shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="/" class="text-decoration-none">
                <span class="text-danger fw-bold fs-3">Đồng hồ <span class="text-dark">Plus</span></span>
            </a>
            <h4 class="fw-bold mb-0 text-uppercase">Giỏ hàng của bạn</h4>
            <a href="/" class="btn btn-outline-danger btn-sm rounded-pill px-3">Tiếp tục mua sắm</a>
        </div>
    </header>

    <div class="container mt-5">
    @if(session('gioHang') && count(session('gioHang')) > 0)
        <form action="{{ route('cart.checkout') }}" method="GET">
            <div class="card border-0 shadow-sm overflow-hidden">
                <table class="table table-v-align mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Giá</th>
                            <th style="width: 150px;">Số lượng</th>
                            <th>Thành tiền</th>
                            <th class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $tongTien = 0; @endphp
                        @foreach(session('gioHang') as $id => $details)
                            @php 
                                $thanhTien = $details['gia'] * $details['so_luong'];
                                $tongTien += $thanhTien; 
                            @endphp
                            <tr class="cart-item" data-price="{{ $details['gia'] }}" data-id="{{ $id }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $details['hinh']) }}" class="cart-img me-3">
                                        <span class="fw-bold">{{ $details['ten'] }}</span>
                                    </div>
                                </td>
                                <td>{{ number_format($details['gia'], 0, ',', '.') }} đ</td>
                                <td>
                                    <input type="number" name="qty[{{ $id }}]" value="{{ $details['so_luong'] }}" 
                                           class="form-control text-center rounded-pill quantity-input" min="1">
                                </td>
                                <td class="text-danger fw-bold item-total">
                                    {{ number_format($thanhTien, 0, ',', '.') }} đ
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="card-footer bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Tổng cộng:</h4>
                        <h3 class="text-danger fw-bold mb-0 main-total-display">
                            {{ number_format($tongTien, 0, ',', '.') }} đ
                        </h3>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="submit" class="btn btn-danger btn-lg px-5 fw-bold rounded-pill shadow-sm">
                            TIẾN HÀNH ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="text-center py-5 shadow-sm bg-white rounded-4">
            <i class="fas fa-shopping-basket text-muted mb-3 fs-1"></i>
            <h4 class="text-muted">Giỏ hàng đang trống!</h4>
        </div>
    @endif
</div>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(".quantity-input").on('change', function (e) {
        var ele = $(this);
        var row = ele.closest(".cart-item");
        var id = row.attr("data-id"); // Lấy ID từ data-id của thẻ tr
        var quantity = ele.val();
        var price = parseInt(row.attr('data-price'));

        // Cập nhật tiền tạm thời trên màn hình
        var subtotal = price * quantity;
        row.find('.item-total').text(subtotal.toLocaleString('vi-VN') + ' đ');
        
        updateGrandTotal();

        // Gửi AJAX để lưu vào Session ngay lập tức
        $.ajax({
            url: '{{ route("cart.update") }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: id, 
                so_luong: quantity
            },
            success: function (response) {
                console.log("Đã cập nhật Session");
            }
        });
    });

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(row => {
            const price = parseInt(row.getAttribute('data-price'));
            const quantity = parseInt(row.querySelector('.quantity-input').value);
            total += price * quantity;
        });
        document.querySelector('.main-total-display').innerText = total.toLocaleString('vi-VN') + ' đ';
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>