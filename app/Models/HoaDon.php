<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;

    // Khai báo tên bảng trong database của bạn
    protected $table = 'hoa_don'; 

    // Tắt timestamps nếu bảng của bạn không có cột created_at và updated_at
    public $timestamps = false; 

    // Khai báo các cột có thể ghi dữ liệu vào
    protected $fillable = [
        'id_nguoi_dung',
        'ngay_lap',
        'tong_tien',
        'dia_chi_giao',
        'sdt_nhan',
        'phuong_thuc_tt',
        'trang_thai'
    ];
// Thêm vào trong file HoaDon.php
public function nguoiDung()
{
    // Liên kết với bảng người dùng qua cột nguoi_dung_id
    return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id', 'id');
}

public function chiTiet()
{
    // Hàm này bạn đã có, dùng để lấy các món hàng trong đơn
    return $this->hasMany(ChiTietHoaDon::class, 'hoa_don_id', 'id');
}


}
