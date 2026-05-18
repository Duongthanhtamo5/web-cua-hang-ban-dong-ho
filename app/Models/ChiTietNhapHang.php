<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietNhapHang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_nhap_hang'; // Khớp tên bảng

    // Do bảng của bạn không có cột created_at và updated_at mặc định của Laravel
    public $timestamps = false; 

    protected $fillable = [
    'nhap_hang_id',
    'san_pham_id',
    'so_luong_nhap',
    'gia_nhap',
    'nguoi_dung_id' // Thêm cột lưu ID người nhập vào đây
];

// Thiết lập mối quan hệ: Một dòng lịch sử thuộc về một người dùng (Nhân viên)
public function nguoiNhap()
{
    // Cột nguoi_dung_id liên kết với khóa chính id của bảng người dùng
    return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id'); 
}

    // Thiết lập mối quan hệ để lấy tên sản phẩm hiển thị ra bảng
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id');
    }
}