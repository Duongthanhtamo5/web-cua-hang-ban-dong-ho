<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đồng hồ Plus - Đẳng cấp & Phong cách</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .product-card { transition: 0.3s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    </style>
</head>
<body class="bg-light">

    <div class="sticky-top shadow">
        <header class="bg-white py-3 border-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <a href="/" class="text-decoration-none">
                            <span class="text-danger fw-bold fs-3">Đồng hồ <span class="text-dark">Plus</span></span>
                        </a>
                    </div>
                    <div class="col-md-5">
                        <form action="{{ route('san-pham.tim-kiem') }}" method="GET" class="input-group search-box shadow-sm">
                            <input type="text" name="query" class="form-control border-0 bg-light" 
                                   placeholder="Tìm kiếm đồng hồ..." value="{{ $tuKhoa ?? '' }}">
                            <button type="submit" class="btn btn-light border-0">
                                <i class="fas fa-search text-muted"></i>
                            </button>
                        </form>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                        <a href="{{ route('gio-hang.index') }}" class="text-dark me-4 position-relative">
                            <i class="fas fa-shopping-cart fs-4"></i>
                            @if(session('gioHang'))
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    {{ count(session('gioHang')) }}
                                </span>
                            @endif
                        </a>
                        
                        @auth
                            <div class="dropdown d-inline-block">
                                <a href="javascript:void(0)" class="text-dark me-4 d-flex align-items-center text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle fs-3 text-primary"></i>
                                    <span class="ms-2 fw-bold d-none d-md-inline">{{ Auth::user()->ho_ten }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userMenu">
                                    <li class="px-3 py-2 border-bottom mb-2">
                                        <span class="small text-muted">Tài khoản của tôi</span>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('profile.show') }}">
                                            <i class="fas fa-user-edit me-2 text-muted"></i> Xem thông tin cá nhân
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('orders.tracking') }}">
                                            <i class="fas fa-truck me-2 text-muted"></i> Theo dõi đơn hàng
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('orders.history') }}">
                                            <i class="fas fa-history me-2 text-muted"></i> Lịch sử mua hàng
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="px-2">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger py-2 rounded">
                                                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-dark me-4">
                                <i class="fas fa-user fs-4"></i>
                            </a>
                        @endauth

                        <div class="d-inline-block text-start small border-start ps-3 text-dark">
                            <strong>Hotline:</strong><br>
                            <span class="text-danger fw-bold">0352 077 311</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <nav class="navbar navbar-expand-lg navbar-dark bg-danger p-0 shadow-sm">
            <div class="container">
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link px-4 py-3 active" href="/"><i class="fas fa-home"></i> TRANG CHỦ</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-4 py-3" href="#" data-bs-toggle="dropdown">ĐỒNG HỒ</a>
                            <ul class="dropdown-menu border-0 shadow">
                                <li><a class="dropdown-item" href="#">Philippe Auguste</a></li>
                                <li><a class="dropdown-item" href="#">Jacques Lemans</a></li>
                                <li><a class="dropdown-item" href="#">Diamond D</a></li>
                                <li><a class="dropdown-item" href="#">Aries Gold</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Xem tất cả đồng hồ</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link px-4 py-3" href="#">KÍNH MẮT</a></li>
                        <li class="nav-item"><a class="nav-link px-4 py-3" href="#">PHỤ KIỆN</a></li>
                        <li class="nav-item"><a class="nav-link px-4 py-3" href="#">TIN TỨC</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <main class="py-2">
        @yield('content')
    </main>

    <footer class="bg-white py-5 border-top mt-5 text-dark">
        <div class="container text-center text-md-start">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="fw-bold">Đồng hồ <span class="text-danger">Plus</span></h4>
                    <p class="text-muted italic">"Đẳng cấp - Phong cách - Sang trọng"</p>
                </div>
                <div class="col-md-6 text-md-end text-muted small">
                    <p class="mb-1"><strong>NHÓM 07 - LỚP DCCNT 14.9</strong></p>
                    <p class="mb-1">Leader: <strong>Dương Thanh Tâm - 20233344</strong></p>
                    <p class="mb-0">Thành viên: Lê Xuân Đạt, Nguyễn Đăng Huân</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>