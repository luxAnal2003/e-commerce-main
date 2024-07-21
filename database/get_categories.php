<?php
require_once 'connection.php';

$sql = "SELECT id, nombre FROM Categorias";
$result = $conn->query($sql);

$categorias = [];
while ($row = $result->fetch_assoc()) {
    $categorias[] = $row;
}

echo json_encode($categorias);

$conn->close();
?>
