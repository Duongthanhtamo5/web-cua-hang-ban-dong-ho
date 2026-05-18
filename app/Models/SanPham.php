<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham'; // Tên bảng dưới DB của bạn (ví dụ: san_pham hoặc sanphams)

    protected $fillable = [
    'ten_san_pham',
    'thuong_hieu',
    'gia_ban',
    'gia_von',         // Đảm bảo là gia_von
    'so_luong_kho',    // Đảm bảo là so_luong_kho
    'hinh_anh',
    'mo_ta',
    'danh_muc_id'
    
];
public $timestamps = false;

    // BỔ SUNG ĐOẠN NÀY: Định nghĩa mối quan hệ danh mục
    public function danhMuc()
    {
        // belongsTo nghĩa là 1 Sản phẩm thuộc về 1 Danh mục
        // 'danh_muc_id' là tên cột khóa ngoại trong bảng san_pham của bạn
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }
}