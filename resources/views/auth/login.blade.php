<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Đồng hồ Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { background: #f4f4f4; }
        .auth-card { max-width: 400px; margin: 100px auto; border: none; border-radius: 15px; }
        .btn-danger { background-color: #be1e2d; border: none; }
        .btn-danger:hover { background-color: #a31a27; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card auth-card shadow-lg p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-danger">Đồng hồ <span class="text-dark">Plus</span></h3>
                <p class="text-muted">Đăng nhập để mua sắm ngay</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif 

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control rounded-pill px-3" placeholder="email@vi-du.com" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Mật khẩu</label>
                    <input type="password" name="password" class="form-control rounded-pill px-3" placeholder="********" required>
                </div>
                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-bold">ĐĂNG NHẬP</button>
            </form>

            <div class="text-center mt-4 small">
                Bạn chưa có tài khoản? 
                <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>