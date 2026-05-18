<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use Notifiable;

    protected $table = 'nguoi_dung';
    
    // THÊM DÒNG NÀY ĐỂ FIX LỖI:
    public $timestamps = false; 

    protected $fillable = [
    'ho_ten', 'email', 'mat_khau', 'so_dien_thoai', 'vai_tro'
];
    // Ẩn mật khẩu khi xuất dữ liệu
    protected $hidden = [
        'mat_khau', 
        'remember_token',
    ];

    /**
     * Quan trọng: Laravel mặc định tìm cột 'password'. 
     * Vì bạn đặt tên cột là 'mat_khau' nên phải có hàm này để Laravel hiểu.
     */
    public function getAuthPassword()
{
    return $this->mat_khau;
}

public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'nguoi_dung_id', 'id');
    }

    // Hàm tĩnh dùng chung trên toàn hệ thống để ghi lại hoạt động
    public static function ghiLog($hanhDong, $chiTiet, $bangLienQuan = null)
    {
        \DB::table('he_thong_log')->insert([
            'nguoi_dung_id' => \Auth::id(), // Tự động lấy ID của người đang đăng nhập
            'hanh_dong' => $hanhDong,
            'chi_tiet' => $chiTiet,
            'bang_lien_quan' => $bangLienQuan,
            'thoi_gian' => now()
        ]);
    }
}