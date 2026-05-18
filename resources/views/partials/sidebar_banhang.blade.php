<div class="col-md-2 sidebar shadow">
    <div class="py-4">
        <div class="px-4 mb-4">
            <h5 class="fw-bold text-white mb-0"><i class="fas fa-clock me-2 text-danger"></i>ĐỒNG HỒ PLUS</h5>
            <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Hệ thống nhân viên</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('banhang.index', ['view' => 'pos']) }}" class="nav-link {{ request('view') != 'orders' && !Route::is('admin.customers.index') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart me-2"></i>Bán hàng tại quầy
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('banhang.index', ['view' => 'orders']) }}" class="nav-link {{ request('view') == 'orders' ? 'active' : '' }}">
                    <i class="fas fa-tasks me-2"></i>Quản lý đơn hàng
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.customers.index') }}" class="nav-link {{ Route::is('admin.customers.index') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>Quản lý khách hàng
                </a>
            </li>
            <hr class="mx-3 text-secondary">
            <li class="nav-item">
                <a href="{{ route('profile.show') }}" class="nav-link {{ Route::is('profile.show') ? 'active' : '' }}">
                    <i class="fas fa-key me-2"></i>Đổi mật khẩu
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