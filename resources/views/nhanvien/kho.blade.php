@extends('layouts.app')

@section('content')
<style>
    /* Dùng lại Style chuẩn từ file banhang của bạn để đồng bộ phụ lục */
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .logout-btn { background: none; border: none; color: #ffc107; padding: 12px 20px; margin: 5px 15px; width: calc(100% - 30px); text-align: left; }
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
                <div class="container-fluid p-0">
                    <h5 class="fw-bold mb-0">QUẢN LÝ KHO HÀNG</h5>
                    <button type="button" class="btn btn-danger btn-sm rounded-pill px-4 ms-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalThemSanPham">
                        <i class="fas fa-plus-circle me-1"></i> Thêm sản phẩm mới
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid px-4 mb-3">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3">
                        <form action="{{ route('kho.index') }}" method="GET" class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control bg-light border-start-0 rounded-end-3" 
                                           placeholder="Tìm theo tên đồng hồ hoặc mã ID..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <select name="danh_muc_id" class="form-select bg-light rounded-3">
                                    <option value="">-- Tất cả danh mục --</option>
                                    @foreach($danhMucs as $dm)
                                        <option value="{{ $dm->id }}" {{ request('danh_muc_id') == $dm->id ? 'selected' : '' }}>
                                            {{ $dm->ten_danh_muc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-danger rounded-3 w-100 fw-bold">
                                    <i class="fas fa-filter me-1"></i> Lọc dữ liệu
                                </button>
                                @if(request('search') || request('danh_muc_id'))
                                    <a href="{{ route('kho.index') }}" class="btn btn-outline-secondary rounded-3" title="Xóa bộ lọc">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="container-fluid px-4">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0 text-center">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-3" style="width: 60px;">STT</th>
                                    <th style="width: 80px;">Hình</th>
                                    <th class="text-start">Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Số lượng tồn</th> 
                                    <th>Giá nhập TB</th>
                                    <th>Giá bán hiện tại</th>
                                    <th style="width: 120px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sanPhams as $index => $sp)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        @if($sp->hinh_anh)
                                            <img src="{{ Str::contains($sp->hinh_anh, 'products/') ? asset('storage/' . $sp->hinh_anh) : asset('products/' . $sp->hinh_anh) }}" 
                                                 width="50" height="50" class="rounded object-fit-cover border shadow-sm" alt="Đồng hồ">
                                        @else
                                            <img src="https://placehold.co/60" width="50" height="50" class="rounded border">
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        <div class="fw-bold text-dark">{{ $sp->ten_san_pham }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">ID: #{{ $sp->id }}</small>
                                    </td>
                                    <td class="text-secondary">
                                        {{ $sp->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $sp->so_luong_kho < 5 ? 'bg-danger' : 'bg-success' }} rounded-pill px-3 py-2" style="font-size: 0.85rem;">
                                            {{ $sp->so_luong_kho ?? 0 }} chiếc
                                        </span>
                                    </td>
                                    <td class="text-muted fw-semibold">
                                        {{ isset($sp->gia_von) ? number_format($sp->gia_von, 0, ',', '.') . 'đ' : '0đ' }}
                                    </td>
                                    <td class="fw-bold text-danger">
                                        {{ number_format($sp->gia_ban, 0, ',', '.') }}đ
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-outline-primary border-0 rounded-circle" title="Nhập thêm" data-bs-toggle="modal" data-bs-target="#modalNhapThem{{ $sp->id }}">
                                                <i class="fas fa-plus-square fs-5"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info border-0 rounded-circle" title="Sửa" data-bs-toggle="modal" data-bs-target="#modalSuaSanPham{{ $sp->id }}">
                                                <i class="fas fa-edit fs-5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalThemSanPham" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-start">
            <form action="{{ route('kho.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 bg-danger text-white">
                    <h5 class="fw-bold mb-0">THÊM SẢN PHẨM MỚI</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-dark">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Tên đồng hồ</label>
                                <input type="text" name="ten_san_pham" class="form-control rounded-3" placeholder="Nhập tên sản phẩm..." required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Số lượng tồn ban đầu</label>
                                    <input type="number" name="so_luong" class="form-control rounded-3" value="1" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Danh mục sản phẩm</label>
                                    <select name="danh_muc_id" class="form-select rounded-3" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($danhMucs as $dm)
                                            <option value="{{ $dm->id }}">{{ $dm->ten_danh_muc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Giá nhập / Giá vốn (VNĐ)</label>
                                    <input type="number" name="gia_von" class="form-control rounded-3" placeholder="Ví dụ: 2000000" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Giá bán (VNĐ)</label>
                                    <input type="number" name="gia_ban" class="form-control rounded-3" placeholder="Ví dụ: 3000000" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-bold small">Hình ảnh sản phẩm</label>
                            <div id="dragDropArea" class="border border-2 border-dashed rounded-4 p-3 text-center d-flex flex-column align-items-center justify-content-center bg-light" style="min-height: 215px; border-style: dashed !important; cursor: pointer; transition: 0.3s;">
                                <div id="dragDropPrompt">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                    <p class="small text-muted mb-1 fw-bold">Kéo thả ảnh vào đây</p>
                                    <p class="text-muted" style="font-size: 0.75rem;">hoặc click để chọn tệp</p>
                                </div>
                                <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded-3 d-none" style="max-height: 180px; object-fit: contain;">
                                <input type="file" name="hinh_anh" id="fileInput" class="d-none" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">Lưu sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($sanPhams as $sp)
<div class="modal fade" id="modalNhapThem{{ $sp->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('kho.nhap_them', $sp->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="fw-bold text-primary">NHẬP THÊM SỐ LƯỢNG</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-start text-dark">
                    <p class="mb-2">Sản phẩm: <strong>{{ $sp->ten_san_pham }}</strong></p>
                    <p class="small text-muted">Số lượng hiện tại: {{ $sp->so_luong_kho ?? 0 }} chiếc</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Số lượng nhập thêm</label>
                        <input type="number" name="so_luong_them" class="form-control rounded-3 text-center fs-4 fw-bold" value="10" min="1">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Xác nhận nhập</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach($sanPhams as $sp)
<div class="modal fade" id="modalSuaSanPham{{ $sp->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 text-start">
            <form action="{{ Auth::user()->vai_tro === 'admin' ? route('admin.products.update', $sp->id) : route('kho.update', $sp->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 bg-info text-white">
                    <h5 class="fw-bold mb-0"><i class="fas fa-edit me-2"></i>CHỈNH SỬA THÔNG TIN & NHẬP HÀNG</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-dark">
                    <div class="row">
                        <div class="col-md-7 border-end">
                            <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-info-circle me-1"></i> Sửa thông tin sản phẩm</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Tên đồng hồ</label>
                                <input type="text" name="ten_san_pham" class="form-control rounded-3" value="{{ $sp->ten_san_pham }}" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Danh mục</label>
                                    <select name="danh_muc_id" class="form-select rounded-3" required>
                                        @foreach($danhMucs as $dm)
                                            <option value="{{ $dm->id }}" {{ $sp->danh_muc_id == $dm->id ? 'selected' : '' }}>{{ $dm->ten_danh_muc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-danger">Giá bán hiện tại (VNĐ)</label>
                                    <input type="number" name="gia_ban" class="form-control rounded-3 fw-bold text-danger" value="{{ $sp->gia_ban }}" required>
                                </div>
                            </div>
                            
                            <div class="p-3 bg-light rounded-3 mt-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">Số lượng tồn kho hiện tại:</span>
                                    <span class="fw-bold text-dark">{{ $sp->so_luong_kho ?? 0 }} chiếc</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">Giá vốn trung bình cũ:</span>
                                    <span class="fw-bold text-dark">{{ number_format($sp->gia_von ?? 0, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-truck-loading me-1"></i> Nhập lô hàng mới (Nếu có)</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-primary">Số lượng nhập thêm</label>
                                <input type="number" name="so_luong_them" class="form-control rounded-3 border-primary text-center fw-bold text-primary" value="0" min="0">
                                <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">* Giữ nguyên 0 nếu chỉ cập nhật thông tin, không thêm kho.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-primary">Giá nhập của đợt này (VNĐ)</label>
                                <input type="number" name="gia_nhap_moi" class="form-control rounded-3 border-primary" value="0" min="0">
                                <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">* Hệ thống tự tính lại vốn trung bình khi Số lượng nhập > 0.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Thay đổi hình ảnh mới</label>
                                <input type="file" name="hinh_anh" class="form-control rounded-3" accept="image/*">
                                @if($sp->hinh_anh)
                                    <div class="mt-2 text-center">
                                        <img src="{{ Str::contains($sp->hinh_anh, 'products/') ? asset('storage/' . $sp->hinh_anh) : asset('products/' . $sp->hinh_anh) }}" 
                                             class="rounded border shadow-sm" width="55" height="55" style="object-fit: cover;">
                                        <div class="text-muted" style="font-size: 0.65rem;">Ảnh hiện hành</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-info text-white rounded-pill px-5 fw-bold shadow-sm">Lưu cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dragDropArea = document.getElementById("dragDropArea");
        const fileInput = document.getElementById("fileInput");
        const imagePreview = document.getElementById("imagePreview");
        const dragDropPrompt = document.getElementById("dragDropPrompt");

        if (dragDropArea) {
            dragDropArea.addEventListener("click", () => fileInput.click());

            dragDropArea.addEventListener("dragover", (e) => {
                e.preventDefault();
                dragDropArea.classList.add("border-danger", "bg-white");
            });

            dragDropArea.addEventListener("dragleave", () => {
                dragDropArea.classList.remove("border-danger", "bg-white");
            });

            dragDropArea.addEventListener("drop", (e) => {
                e.preventDefault();
                dragDropArea.classList.remove("border-danger", "bg-white");
                
                const files = e.dataTransfer.files;
                if (files.length > 0 && files[0].type.startsWith("image/")) {
                    fileInput.files = files;
                    displayPreview(files[0]);
                }
            });
        }

        if (fileInput) {
            fileInput.addEventListener("change", function () {
                if (this.files && this.files[0]) {
                    displayPreview(this.files[0]);
                }
            });
        }

        function displayPreview(file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove("d-none");
                dragDropPrompt.classList.add("d-none");
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection