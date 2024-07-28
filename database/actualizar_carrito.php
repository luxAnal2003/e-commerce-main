<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'connection.php';

$id_cliente = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;
$id_cliente_no_registrado = 1; // ID para usuarios no registrados
$id_producto = isset($_GET['id']) ? intval($_GET['id']) : null;
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;

if ($id_producto && $accion) {
    switch ($accion) {
        case 'agregar':
            if ($id_cliente) {
                $query = "UPDATE CarritoCompra SET cantidad = cantidad + 1 WHERE id_producto = ? AND id_cliente = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $id_producto, $id_cliente);
            } else {
                $query = "UPDATE CarritoCompra SET cantidad = cantidad + 1 WHERE id_producto = ? AND id_cliente_no_registrado = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $id_producto, $id_cliente_no_registrado);
            }
            $stmt->execute();
            break;
        case 'eliminar':
            if ($id_cliente) {
                $query = "DELETE FROM CarritoCompra WHERE id_producto = ? AND id_cliente = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $id_producto, $id_cliente);
            } else {
                $query = "DELETE FROM CarritoCompra WHERE id_producto = ? AND id_cliente_no_registrado = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $id_producto, $id_cliente_no_registrado);
            }
            $stmt->execute();
            break;
    }
}

$stmt->close();
$conn->close();

header("Location: ../index.php");
exit;
?>