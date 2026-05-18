<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    protected $table = 'chi_tiet_hoa_don';
    public $timestamps = false;

    // SỬA TẠI ĐÂY: Đảm bảo dùng 'hoa_don_id' thay vì 'id_hoa_don'
    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'hoa_don_id', 'id');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id', 'id');
    }
}