<?php
header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ nguồn nào
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header cụ thể
header('Content-Type: application/json'); // Định dạng dữ liệu trả về là JSON
include 'db.php'; 

if (isset($_GET['idthucung'])) {
    $idthucung = $_GET['idthucung']; 

    // Truy vấn lấy thông tin thú cưng theo idthucung
    $sql = "SELECT idthucung, tensukien, idnguoidung, loaisukien, trungtam, starttime, endtime, trangthai FROM thucung WHERE idthucung = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $idthucung); // Liên kết tham số
        $stmt->execute();
        $result = $stmt->get_result();

        $pets = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pets[] = $row; // Đưa từng thú cưng vào mảng
            }
            // Trả về kết quả dưới dạng JSON
            echo json_encode($pets);
        } else {
            echo json_encode(array("error" => "Không tìm thấy thú cưng"));
        }
    } else {
        echo json_encode(array("error" => "Truy vấn không thành công"));
    }

    $stmt->close();
} else {
    echo json_encode(array("error" => "Thiếu idthucung"));
}

$conn->close();
?>
