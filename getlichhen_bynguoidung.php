<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header('Content-Type: application/json'); 
include 'db.php'; 

// Lấy idnguoidung và trạng thái từ request
$idnguoidung = isset($_GET['idnguoidung']) ? intval($_GET['idnguoidung']) : 0;  
$status = isset($_GET['status']) ? intval($_GET['status']) : -1; // -1 mặc định là không lọc theo trạng thái

$sql = "SELECT 
            lichhen.idlichhen, 
            nguoidung.tennguoidung, 
            thucung.tenthucung, 
            lichhen.ngayhen, 
            lichhen.thoigianhen, 
            lichhen.trangthai, 
            lichhen.ngaytao,
            dichvu.tendichvu, 
            trungtam.tentrungtam
        FROM lichhen 
        JOIN nguoidung ON lichhen.idnguoidung = nguoidung.idnguoidung
        JOIN thucung ON lichhen.idthucung = thucung.idthucung
        JOIN lichhen_dichvu ON lichhen.idlichhen = lichhen_dichvu.idlichhen
        JOIN dichvu ON lichhen_dichvu.iddichvu = dichvu.iddichvu
        JOIN trungtam ON lichhen.idtrungtam = trungtam.idtrungtam
        WHERE lichhen.idnguoidung = ?";

// Nếu trạng thái được truyền, thêm điều kiện lọc
if ($status != -1) {
    $sql .= " AND lichhen.trangthai = ?";
}

if ($stmt = $conn->prepare($sql)) {
    if ($status != -1) {
        $stmt->bind_param("ii", $idnguoidung, $status); // Ràng buộc tham số với idnguoidung và trạng thái
    } else {
        $stmt->bind_param("i", $idnguoidung); // Ràng buộc chỉ với idnguoidung
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Kiểm tra nếu idlichhen đã tồn tại trong bookings
            if (!isset($bookings[$row['idlichhen']])) {
                // Nếu chưa, khởi tạo một mảng cho lịch hẹn
                $bookings[$row['idlichhen']] = [
                    'idlichhen' => $row['idlichhen'],
                    'tennguoidung' => $row['tennguoidung'],
                    'tenthucung' => $row['tenthucung'],
                    'ngayhen' => $row['ngayhen'],
                    'thoigianhen' => $row['thoigianhen'],
                    'trangthai' => $row['trangthai'],
                    'ngaytao' => $row['ngaytao'],
                    'tentrungtam' => $row['tentrungtam'],
                    'dichvu' => [] // Khởi tạo mảng cho dịch vụ
                ];
            }
            // Thêm tên dịch vụ vào mảng dịch vụ
            $bookings[$row['idlichhen']]['dichvu'][] = $row['tendichvu'];
        }
        // Chuyển đổi mảng từ dạng liên kết sang dạng chỉ mục
        echo json_encode(array_values($bookings)); // Trả về danh sách lịch hẹn cùng dịch vụ
    } else {
        echo json_encode(array("message" => "No bookings found")); // Không tìm thấy lịch hẹn
    }
    $stmt->close();
} else {
    echo json_encode(array("error" => "Failed to prepare statement.")); // Lỗi khi chuẩn bị truy vấn
}

$conn->close();
?>
