<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    use HasFactory;

    // Khai báo tên bảng dưới DB của bạn (nếu tên bảng là danh_muc)
    protected $table = 'danh_muc'; 

    protected $fillable = ['ten_danh_muc'];

    // Mối quan hệ đảo ngược: Một danh mục có nhiều sản phẩm
    public function sanPhams()
    {
        return $this->hasMany(SanPham::class, 'danh_muc_id');
    }
}