<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - Đồng hồ Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <form action="{{ route('cart.process') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-7">
                    <div class="card shadow-sm border-0 p-4">
                        <h4 class="fw-bold mb-4 text-danger">THÔNG TIN GIAO HÀNG</h4>
                        <div class="mb-3">
                            <label class="form-label">Họ tên người nhận</label>
                            <input type="text" name="ho_ten" class="form-control" value="{{ Auth::user()->ho_ten }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="sdt" class="form-control" required placeholder="Nhập số điện thoại...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ nhận hàng</label>
                            <textarea name="dia_chi" class="form-control" rows="3" required placeholder="Địa chỉ chi tiết..."></textarea>
                        </div>
                        
                        <h4 class="fw-bold mt-4 mb-3">PHƯƠNG THỨC THANH TOÁN</h4>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment" id="cod" value="COD" checked>
                            <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" id="bank" value="BANK">
                            <label class="form-check-label" for="bank">Chuyển khoản ngân hàng</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow-sm border-0 p-4">
                        <h4 class="fw-bold mb-4">ĐƠN HÀNG CỦA BẠN</h4>
                        @php $total = 0; @endphp
                        @foreach($gioHang as $item)
                            @php $total += $item['gia'] * $item['so_luong']; @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item['ten'] }} <strong>x{{ $item['so_luong'] }}</strong></span>
                                <span class="fw-bold">{{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }} đ</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5">Tổng cộng:</span>
                            <span class="fs-4 fw-bold text-danger">{{ number_format($total, 0, ',', '.') }} đ</span>
                        </div>
                        
                        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold py-3 rounded-pill shadow">
                            XÁC NHẬN ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>