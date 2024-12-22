<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

include '../db.php';

// Lấy tháng và năm từ request
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Truy vấn tổng số người dùng trong tháng
$sql_users = "
    SELECT COUNT(DISTINCT idnguoidung) AS total_users
    FROM lichhen
    WHERE MONTH(ngayhen) = ? AND YEAR(ngayhen) = ?
";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->bind_param("ii", $month, $year);
$stmt_users->execute();
$result_users = $stmt_users->get_result();
$total_users = $result_users->fetch_assoc()['total_users'];

// Truy vấn tổng số lịch hẹn trong tháng
$sql_bookings = "
    SELECT COUNT(idlichhen) AS total_bookings
    FROM lichhen
    WHERE MONTH(ngayhen) = ? AND YEAR(ngayhen) = ?
";
$stmt_bookings = $conn->prepare($sql_bookings);
$stmt_bookings->bind_param("ii", $month, $year);
$stmt_bookings->execute();
$result_bookings = $stmt_bookings->get_result();
$total_bookings = $result_bookings->fetch_assoc()['total_bookings'];

// Truy vấn dữ liệu chi tiết theo ngày
$sql_daily_stats = "
    SELECT DATE(ngayhen) AS date, 
           COUNT(DISTINCT idnguoidung) AS users, 
           COUNT(idlichhen) AS bookings
    FROM lichhen
    WHERE MONTH(ngayhen) = ? AND YEAR(ngayhen) = ?
    GROUP BY DATE(ngayhen)
    ORDER BY DATE(ngayhen)
";
$stmt_daily_stats = $conn->prepare($sql_daily_stats);
$stmt_daily_stats->bind_param("ii", $month, $year);
$stmt_daily_stats->execute();
$result_daily_stats = $stmt_daily_stats->get_result();
$daily_stats = [];
while ($row = $result_daily_stats->fetch_assoc()) {
    $daily_stats[] = $row;
}

// Đóng kết nối
$stmt_users->close();
$stmt_bookings->close();
$stmt_daily_stats->close();
$conn->close();

// Trả về kết quả
$response = [
    "status" => "success",
    "month" => $month,
    "year" => $year,
    "total_users" => $total_users,
    "total_bookings" => $total_bookings,
    "daily_stats" => $daily_stats
];

echo json_encode($response);