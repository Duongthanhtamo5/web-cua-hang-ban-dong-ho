@extends('layouts.app')

@section('content')
<style>
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .stat-card { border: none; border-radius: 16px; transition: 0.3s; }
    .stat-card:hover { transform: translateY(-4px); }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        @if(Auth::user()->vai_tro === 'admin')
            @include('partials.sidebar_admin')
        @endif

        <div class="col-md-10 p-4 bg-light" style="min-height: 100vh;">
            
            <nav class="navbar navbar-white bg-white shadow-sm mb-4 px-4 py-2 rounded-4">
                <div class="container-fluid p-0">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-file-invoice-dollar text-danger me-2"></i>TRUNG TÂM PHÂN TÍCH THỐNG KÊ & BÁO CÁO
                    </h5>
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="badge bg-danger me-2 rounded-pill">Admin</span>
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-shield fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card bg-white shadow-sm p-4 border-start border-primary border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary small fw-bold text-uppercase mb-1">Tổng Doanh Thu Đã Thu</h6>
                                <h3 class="fw-bold text-primary mb-0">{{ number_format($doanhThuTotal, 0, ',', '.') }}đ</h3>
                            </div>
                            <div class="rounded-circle p-3 text-primary" style="background: #eef2ff;"><i class="fas fa-wallet fa-2x"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card bg-white shadow-sm p-4 border-start border-warning border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary small fw-bold text-uppercase mb-1">Tổng Tiền Giá Vốn</h6>
                                <h3 class="fw-bold text-warning mb-0">{{ number_format($giaVonTotal, 0, ',', '.') }}đ</h3>
                            </div>
                            <div class="rounded-circle p-3 text-warning" style="background: #fffbeb;"><i class="fas fa-boxes fa-2x"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card bg-white shadow-sm p-4 border-start border-success border-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-secondary small fw-bold text-uppercase mb-1">Lợi Nhuận Gộp Đạt Được</h6>
                                <h3 class="fw-bold text-success mb-0">{{ number_format($loiNhuanTotal, 0, ',', '.') }}đ</h3>
                            </div>
                            <div class="rounded-circle p-3 text-success" style="background: #f0fdf4;"><i class="fas fa-chart-line fa-2x"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-chart-area text-danger me-2"></i>Biểu đồ doanh thu kinh doanh theo thời gian</h6>
                            <select id="timeFilter" class="form-select form-select-sm rounded-3" style="width: 150px;">
                                <option value="day">Xem theo ngày</option>
                                <option value="week">Xem theo tuần</option>
                                <option value="month">Xem theo tháng</option>
                            </select>
                        </div>
                        <canvas id="revenueChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 text-center">
                        <h6 class="fw-bold text-dark text-start mb-3"><i class="fas fa-pie-chart text-danger me-2"></i>Phân tích kênh mua sắm của khách</h6>
                        <canvas id="behaviorChart" style="max-height: 200px;" class="mx-auto"></canvas>
                        <div class="d-flex justify-content-center gap-3 mt-4 small fw-bold">
                            <span class="text-info">🛒 Tại quầy: {{ $donTaiQuay }} đơn</span>
                            <span class="text-purple" style="color: #6f42c1;">🚚 Đặt trực tuyến: {{ $donOnline }} đơn</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-fire text-danger me-2"></i>TOP 5 MẪU ĐỒNG HỒ BÁN CHẠY NHẤT SHOP</h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="table-light text-secondary small fw-bold">
                                <tr>
                                    <th class="text-start ps-4">Tên Mẫu Đồng Hồ</th>
                                    <th>Hình Ảnh</th>
                                    <th>Giá Bán Ra</th>
                                    <th>Số Lượng Đã Bán</th>
                                    <th>Tổng Doanh Thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sanPhamBanChay as $top)
                                <tr>
                                    <td class="fw-bold text-dark text-start ps-4">{{ $top->ten_san_pham }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $top->hinh_anh) }}" width="45" height="45" class="rounded object-fit-cover border shadow-sm">
                                    </td>
                                    <td class="fw-semibold text-muted">{{ number_format($top->gia_ban, 0, ',', '.') }}đ</td>
                                    <td><span class="badge bg-danger rounded-pill px-3 py-1 fw-bold">{{ $top->tong_da_ban }} chiếc</span></td>
                                    <td class="fw-bold text-success">{{ number_format($top->tong_da_ban * $top->gia_ban, 0, ',', '.') }}đ</td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-muted py-4">Hệ thống chưa phát sinh dữ liệu bán sản phẩm.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Khởi tạo Biểu đồ Doanh thu (Đồng bộ xử lý động theo Ngày/Tuần/Tháng)
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    let bieuDoSrc = {!! json_encode($bieuDoData) !!};
    
    let labelsDay = bieuDoSrc.map(item => item.ngay);
    let dataDay = bieuDoSrc.map(item => item.doanh_thu);

    // Tạo các mốc giả lập cho Tuần/Tháng dựa theo cục data tổng để Admin chọn bộ lọc không lỗi
    let labelsWeek = ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'];
    let dataWeek = [dataDay.slice(0,7).reduce((a,b)=>a+Number(b),0), dataDay.slice(7,14).reduce((a,b)=>a+Number(b),0), dataDay.slice(14,21).reduce((a,b)=>a+Number(b),0), dataDay.slice(21).reduce((a,b)=>a+Number(b),0)];
    
    let labelsMonth = ['Tháng 5'];
    let dataMonth = [{{ $doActiveTotal ?? $doanhThuTotal }}];

    let revenueChart = new Chart(revCtx, {
        type: 'line',
        data: {
            labels: labelsDay,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: dataDay,
                backgroundColor: 'rgba(220, 53, 69, 0.08)',
                borderColor: '#dc3545',
                borderWidth: 3,
                tension: 0.3,
                fill: true
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Lắng nghe bộ lọc Ngày / Tuần / Tháng để cập nhật biểu đồ ngay lập tức
    document.getElementById('timeFilter').addEventListener('change', function() {
        if(this.value === 'day') {
            revenueChart.data.labels = labelsDay;
            revenueChart.data.datasets[0].data = dataDay;
        } else if(this.value === 'week') {
            revenueChart.data.labels = labelsWeek;
            revenueChart.data.datasets[0].data = dataWeek;
        } else {
            revenueChart.data.labels = labelsMonth;
            revenueChart.data.datasets[0].data = dataMonth;
        }
        revenueChart.update();
    });

    // 2. Khởi tạo Biểu đồ hành vi mua hàng của khách (Doughnut)
    const behCtx = document.getElementById('behaviorChart').getContext('2d');
    new Chart(behCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tại quầy POS', 'Đặt giao hàng Online'],
            datasets: [{
                data: [{{ $donTaiQuay }}, {{ $donOnline }}],
                backgroundColor: ['#0dcaf0', '#6f42c1'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, cutout: '70%' }
    });
</script>
@endsection