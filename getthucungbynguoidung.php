<?php
header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ nguồn nào
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header cụ thể
header('Content-Type: application/json'); // Định dạng dữ liệu trả về là JSON
include 'db.php'; 


// Kiểm tra nếu có `idnguoidung` trong yêu cầu
if (isset($_GET['idnguoidung'])) {
    $idnguoidung = $_GET['idnguoidung'];

    // Truy vấn lấy thú cưng của người dùng theo `idnguoidung`
    $sql = "SELECT idthucung, tensukien, idnguoidung, loaisukien, trungtam, starttime, endtime, trangthai FROM thucung WHERE idnguoidung = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $idnguoidung); // Liên kết tham số
        $stmt->execute();
        $result = $stmt->get_result();

        $pets = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pets[] = $row; // Đưa từng thú cưng vào mảng
            }
        }

        // Trả về kết quả dưới dạng JSON
        echo json_encode($pets);
    } else {
        echo json_encode(array("error" => "Truy vấn không thành công"));
    }

    $stmt->close();
} else {
    echo json_encode(array("error" => "Thiếu idnguoidung"));
}

$conn->close();
?>
