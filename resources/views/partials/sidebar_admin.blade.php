<div class="col-md-2 sidebar shadow">
    <div class="py-4">
        <div class="px-4 mb-4">
            <h5 class="fw-bold text-white mb-0"><i class="fas fa-user-shield me-2 text-danger"></i>ĐỒNG HỒ PLUS</h5>
            <small class="text-danger text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Hệ thống Quản trị viên</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line me-2"></i>Màn hình tổng quan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kho.index') }}" class="nav-link {{ Route::is('kho.index') || Route::is('kho.nhap') || Route::is('kho.lichsu') ? 'active' : '' }}">
                    <i class="fas fa-boxes me-2"></i>Quản lý sản phẩm
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ Route::is('admin.orders.index') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag me-2"></i>Quản lý đơn hàng
                </a>
            </li>
            <li class="nav-item">
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.index') ? 'active' : '' }}">
        <i class="fas fa-users-cog me-2"></i>Quản lý nhân viên
    </a>
</li>
   <li class="nav-item">
    <a href="{{ route('admin.customers.index') }}" class="nav-link {{ Route::is('admin.customers.index') ? 'active' : '' }}">
        <i class="fas fa-users me-2"></i>Quản lý khách hàng
    </a>
</li>
            <li class="nav-item">
    <a href="{{ route('admin.logs.index') }}" class="nav-link {{ Route::is('admin.logs.index') ? 'active' : '' }}">
        <i class="fas fa-history me-2"></i>Log hoạt động hệ thống
    </a>
</li>
            <li class="nav-item">
    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ Route::is('admin.reports.index') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar me-2"></i>Thống kê báo cáo
    </a>
</li>
            <hr class="mx-3 text-secondary">
            <li class="nav-item">
                <a href="{{ route('profile.show') }}" class="nav-link {{ Route::is('profile.show') ? 'active' : '' }}">
                    <i class="fas fa-key me-2"></i>Cài đặt tài khoản
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn nav-link">
                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>