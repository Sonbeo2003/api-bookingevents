<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header('Content-Type: application/json'); 

include 'db.php';

// Kiểm tra nếu có tham số idtrungtam được gửi lên
if (isset($_GET['idtrungtam'])) {
    $idtrungtam = $_GET['idtrungtam'];

    // Chuẩn bị câu truy vấn SQL để lấy danh sách dịch vụ của trung tâm
    $sql = "
        SELECT dv.iddichvu, dv.tendichvu, dv.mota, dv.gia, dv.thoigianthuchien, dv.hinhanh
        FROM dichvu dv
        INNER JOIN dichvu_trungtam dvt ON dv.iddichvu = dvt.iddichvu
        WHERE dvt.idtrungtam = ?
    ";

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idtrungtam);  // 'i' biểu thị cho kiểu integer
    $stmt->execute();
    
    // Lấy kết quả
    $result = $stmt->get_result();
    $services = $result->fetch_all(MYSQLI_ASSOC);

    // Trả về kết quả dưới dạng JSON
    echo json_encode($services);
    
    // Đóng kết nối
    $stmt->close();
    $conn->close();
} else {
    // Nếu không có idtrungtam, trả về lỗi
    echo json_encode(["error" => "Thiếu tham số idtrungtam"]);
}
?>
