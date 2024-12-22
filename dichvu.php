<?php
header('Content-Type: application/json');
include 'db.php';

$sql = "SELECT * FROM dichvu";
$result = $conn->query($sql);

$services = array();
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

echo json_encode($services);

$conn->close();
?>
