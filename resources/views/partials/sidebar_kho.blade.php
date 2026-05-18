<div class="col-md-2 sidebar shadow">
    <div class="py-4">
        <div class="px-4 mb-4">
            <h5 class="fw-bold text-white mb-0"><i class="fas fa-warehouse me-2 text-danger"></i>ĐỒNG HỒ PLUS</h5>
            <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Hệ thống nhân viên kho</small>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('kho.index') }}" class="nav-link {{ Route::is('kho.index') ? 'active' : '' }}">
                    <i class="fas fa-boxes me-2"></i>Quản lý tồn kho
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kho.nhap') }}" class="nav-link {{ Route::is('kho.nhap') ? 'active' : '' }}">
                    <i class="fas fa-truck-loading me-2"></i>Nhập kho mới
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kho.lichsu') }}" class="nav-link {{ Route::is('kho.lichsu') ? 'active' : '' }}">
                    <i class="fas fa-file-import me-2"></i>Lịch sử nhập hàng
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