<?php
include "db.php";
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả nguồn
header('Access-Control-Allow-Methods: POST'); // Cho phép phương thức POST
header('Access-Control-Allow-Headers: Content-Type'); // Cho phép headers cần thiết

header('Content-Type: application/json');

if (isset($_POST['email']) && isset($_POST['matkhau'])) {
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];

    $query = 'SELECT * FROM `nguoidung` WHERE `email` = "' . $email . '" AND `matkhau` = "' . $matkhau . '"';
    $data = mysqli_query($conn, $query);
    $numrow = mysqli_num_rows($data);

    if ($numrow > 0) {
        $nguoidung = mysqli_fetch_assoc($data);
        
        // Kiểm tra vai trò
        if ($nguoidung['vaitro'] == 1) {
            $arr = [
                'success' => true,
                'message' => "Đăng nhập thành công",
                'user' => [
                    'idnguoidung' => $nguoidung['idnguoidung'],
                    'tennguoidung' => $nguoidung['tennguoidung'],
                    'email' => $nguoidung['email'],
                    'sodienthoai' => $nguoidung['sodienthoai'], // Thêm số điện thoại vào phản hồi
                    'diachi' => $nguoidung['diachi'], // Thêm địa chỉ vào phản hồi
                    'vaitro' => $nguoidung['vaitro'] // Vai trò người dùng
                ],
            ];
        } else {
            $arr = [
                'success' => false,
                'message' => "Bạn không có quyền truy cập.",
            ];
        }
    } else {
        $arr = [
            'success' => false,
            'message' => "Email hoặc mật khẩu không đúng",
        ];
    }
} else {
    $arr = [
        'success' => false,
        'message' => "Thiếu email hoặc mật khẩu",
    ];
}

echo json_encode($arr);
?>
