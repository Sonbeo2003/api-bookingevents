<?php
include "D:/Xampp/htdocs/API_Events/db.php";

header('Content-Type: application/json');

if (isset($_POST['email']) && isset($_POST['matkhau'])) {
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];

    $query = 'SELECT * FROM `nguoidung` WHERE `email` = "' . $email . '" AND `matkhau` = "' . $matkhau . '"';
    $data = mysqli_query($conn, $query);
    $numrow = mysqli_num_rows($data);

    if ($numrow > 0) {
        $nguoidung = mysqli_fetch_assoc($data);
        $arr = [
            'success' => true,
            'message' => "Đăng nhập thành công",
            'user' => [
                'idnguoidung' => $nguoidung['idnguoidung'],
                'tennguoidung' => $nguoidung['tennguoidung'],
                'email' => $nguoidung['email'],
                'diachi' => $nguoidung['diachi'],  // Thêm địa chỉ
                'sodienthoai' => $nguoidung['sodienthoai'],  // Thêm số điện thoại
            ],
        ];
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
