<?php
session_start();
require_once 'database/connection.php';

// Procesar el pago y vaciar el carrito
$id_cliente = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;
$id_cliente_no_registrado = !isset($_SESSION['id']) ? 1 : null;

$query = "DELETE FROM CarritoCompra WHERE id_cliente = ? OR id_cliente_no_registrado = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_cliente, $id_cliente_no_registrado);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: confirmacion.php");
exit;
?>
