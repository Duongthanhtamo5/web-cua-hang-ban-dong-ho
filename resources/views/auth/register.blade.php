<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản - Đồng hồ Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { background: #f4f4f4; }
        .auth-card { max-width: 500px; margin: 50px auto; border: none; border-radius: 15px; }
        .btn-danger { background-color: #be1e2d; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card auth-card shadow-lg p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-danger">Đăng ký thành viên</h3>
                <p class="text-muted">Trở thành khách hàng thân thiết của Đồng hồ Plus</p>
            </div>
            <form action="{{ route('register.post') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" name="ho_ten" class="form-control rounded-pill px-3" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control rounded-pill px-3" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Số điện thoại</label>
        <input type="text" name="so_dien_thoai" class="form-control rounded-pill px-3">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control rounded-pill px-3" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control rounded-pill px-3" required>
        </div>
    </div>
    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">ĐĂNG KÝ TÀI KHOẢN</button>
</form>