@extends('layouts.app')

@section('content')
<style>
    /* Style đồng bộ tuyệt đối với trang kho của Tâm */
    .sidebar { min-height: 100vh; background: #1a1d20; color: white; position: sticky; top: 0; z-index: 1000; }
    .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; transition: 0.2s; display: block; text-decoration: none; }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #dc3545; color: white; box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3); }
    .user-pill { background: #f8f9fa; border: 1px solid #eee; border-radius: 50px; padding: 5px 15px; }
    .logout-btn { background: none; border: none; color: #ffc107; padding: 12px 20px; margin: 5px 15px; width: calc(100% - 30px); text-align: left; }
    
    /* Cấu trúc CSS riêng cho vùng hộp kéo thả ảnh */
    .drop-zone { cursor: pointer; min-height: 180px; transition: 0.2s; border-color: #dc3545 !important; background: #f8f9fa; }
    .drop-zone:hover { background: #fff3f4; border-color: #bd2130 !important; }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
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
                    <h5 class="fw-bold mb-0">LẬP PHIẾU NHẬP KHO HÀNG</h5>
                    <div class="ms-auto d-flex align-items-center user-pill shadow-sm">
                        <span class="fw-bold text-dark small me-2">{{ Auth::user()->ho_ten }}</span>
                        <i class="fas fa-user-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-5">
                            <h5 class="fw-bold text-dark mb-4"><i class="fas fa-file-invoice text-danger me-2"></i>Thông tin phiếu nhập kho</h5>
                            
                            <form action="{{ route('kho.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-secondary">Hình thức nhập kho</label>
                                    <select id="hinhThucNhap" name="hinh_thuc_nhap" class="form-select rounded-3 py-2 fw-bold text-primary">
                                        <option value="cu_san_pham">Nhập thêm vào sản phẩm đã có sẵn trong hệ thống</option>
                                        <option value="moi_san_pham">Nhập mới hoàn toàn (Sản phẩm mới chưa từng có)</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="groupSanPhamCoSan">
                                    <label class="form-label fw-bold small text-secondary">Chọn sản phẩm có sẵn</label>
                                    <select id="selectSanPham" name="san_pham_id" class="form-select rounded-3 py-2">
                                        <option value="">-- Bấm vào đây để chọn đồng hồ có sẵn --</option>
                                        @foreach($sanPhams as $sp)
                                            <option value="{{ $sp->id }}" 
                                                    data-ten="{{ $sp->ten_san_pham }}"
                                                    data-danhmuc="{{ $sp->danh_muc_id }}"
                                                    data-giaban="{{ $sp->gia_ban }}">
                                                ID: #{{ $sp->id }} - {{ $sp->ten_san_pham }} (Tồn cũ: {{ $sp->so_luong_kho }} chiếc)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-secondary">Tên mẫu đồng hồ</label>
                                    <input type="text" id="tenSanPham" name="ten_san_pham" class="form-control rounded-3 py-2" placeholder="Nhập tên sản phẩm..." required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-secondary">Danh mục phân loại</label>
                                        <select id="danhMucId" name="danh_muc_id" class="form-select rounded-3 py-2" required>
                                            <option value="">-- Chọn danh mục mặt hàng --</option>
                                            @foreach($danhMucs as $dm)
                                                <option value="{{ $dm->id }}">{{ $dm->ten_danh_muc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-secondary">Số lượng nhập kho (chiếc)</label>
                                        <input type="number" name="so_luong" class="form-control rounded-3 py-2 text-center fw-bold text-danger" value="10" min="1" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-primary">Giá nhập / Giá vốn đợt này (VNĐ)</label>
                                        <input type="number" name="gia_von" class="form-control rounded-3 py-2 fw-bold text-primary" placeholder="Ví dụ: 5000000" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold small text-danger">Giá niêm yết bán ra (VNĐ)</label>
                                        <input type="number" id="giaBan" name="gia_ban" class="form-control rounded-3 py-2 fw-bold text-danger" placeholder="Ví dụ: 7500000" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-secondary">Hình ảnh mẫu mặt hàng (Không bắt buộc khi nhập thêm)</label>
                                    
                                    <div id="dropZone" class="drop-zone d-flex flex-column align-items-center justify-content-center border border-2 border-dashed rounded-4 p-4 text-center position-relative">
                                        <input type="file" id="fileInput" name="hinh_anh" class="d-none" accept="image/*">
                                        
                                        <div id="dropZonePrompt" class="text-muted">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-danger mb-2"></i>
                                            <p class="mb-1 fw-bold text-dark">Kéo và thả ảnh vào đây</p>
                                            <p class="small mb-0 text-secondary">Hoặc <span class="text-danger fw-bold">bấm để chọn ảnh</span> từ máy tính</p>
                                        </div>

                                        <div id="imagePreviewContainer" class="d-none w-100 h-100 text-center">
                                            <img id="imagePreview" src="#" alt="Xem trước" class="img-fluid rounded-3 shadow-sm mb-2" style="max-height: 160px; object-fit: contain;">
                                            <div>
                                                <span class="badge bg-dark rounded-pill px-3 py-1 shadow-sm" id="fileNameBadge">filename.jpg</span>
                                                <button type="button" id="btnRemoveImage" class="btn btn-sm btn-outline-danger rounded-circle ms-2" title="Xóa ảnh chọn lại">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end border-top pt-3">
                                    <a href="{{ route('kho.index') }}" class="btn btn-light rounded-pill px-4 me-2">Quay lại kho</a>
                                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">Xác nhận nhập kho</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const hinhThucNhap = document.getElementById("hinhThucNhap");
    const groupSanPhamCoSan = document.getElementById("groupSanPhamCoSan");
    const selectSanPham = document.getElementById("selectSanPham");
    const tenSanPham = document.getElementById("tenSanPham");
    const danhMucId = document.getElementById("danhMucId");
    const giaBan = document.getElementById("giaBan");

    // XỬ LÝ KÉO THẢ ẢNH VÀ XEM TRƯỚC INTERACTIVE
    const dropZone = document.getElementById("dropZone");
    const fileInput = document.getElementById("fileInput");
    const dropZonePrompt = document.getElementById("dropZonePrompt");
    const previewContainer = document.getElementById("imagePreviewContainer");
    const imagePreview = document.getElementById("imagePreview");
    const fileNameBadge = document.getElementById("fileNameBadge");
    const btnRemoveImage = document.getElementById("btnRemoveImage");

    // Click mở hộp thoại chọn tệp
    dropZone.addEventListener("click", function (e) {
        if (e.target.closest('#btnRemoveImage')) return;
        fileInput.click();
    });

    // Hiệu ứng rê chuột kéo thả tệp qua biên
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.style.background = "#fff3f4";
            dropZone.style.borderStyle = "solid";
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.style.background = "#f8f9fa";
            dropZone.style.borderStyle = "dashed";
        }, false);
    });

    // Thả tệp vào vùng drop
    dropZone.addEventListener("drop", function (e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            fileInput.files = files;
            handleImagePreview(files[0]);
        }
    });

    // Chọn file kiểu thủ công duyệt tệp
    fileInput.addEventListener("change", function () {
        if (this.files.length > 0) {
            handleImagePreview(this.files[0]);
        }
    });

    function handleImagePreview(file) {
        const reader = new FileReader();
        fileNameBadge.textContent = file.name;
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            dropZonePrompt.classList.add("d-none");
            previewContainer.classList.remove("d-none");
        }
        reader.readAsDataURL(file);
    }

    // Xóa ảnh chọn lại từ đầu
    btnRemoveImage.addEventListener("click", function (e) {
        e.preventDefault();
        fileInput.value = "";
        imagePreview.src = "#";
        previewContainer.classList.add("d-none");
        dropZonePrompt.classList.remove("d-none");
    });

    // MẶC ĐỊNH KHI LOAD TRANG: Khóa ô Tên sản phẩm vì đang ở chế độ Sản phẩm có sẵn
    if (hinhThucNhap.value === "cu_san_pham") {
        tenSanPham.setAttribute("readonly", "readonly");
    }

    // Lắng nghe sự kiện chuyển đổi hình thức nhập hàng
    hinhThucNhap.addEventListener("change", function () {
        if (this.value === "moi_san_pham") {
            groupSanPhamCoSan.classList.add("d-none");
            selectSanPham.removeAttribute("required");
            selectSanPham.value = "";
            
            tenSanPham.value = "";
            tenSanPham.removeAttribute("readonly");
            danhMucId.value = "";
            danhMucId.removeAttribute("disabled");
            giaBan.value = "";
        } else {
            groupSanPhamCoSan.classList.remove("d-none");
            selectSanPham.setAttribute("required", "required");
            tenSanPham.setAttribute("readonly", "readonly");
        }
    });

    // Tự động fill thông tin khi nhân viên chọn sản phẩm cũ trong danh sách
    selectSanPham.addEventListener("change", function () {
        const optionSelected = this.options[this.selectedIndex];
        if (this.value !== "") {
            tenSanPham.value = optionSelected.getAttribute("data-ten");
            danhMucId.value = optionSelected.getAttribute("data-danhmuc");
            giaBan.value = optionSelected.getAttribute("data-giaban");
            tenSanPham.setAttribute("readonly", "readonly");
        } else {
            tenSanPham.value = "";
            danhMucId.value = "";
            giaBan.value = "";
        }
    });
});
</script>
@endsection