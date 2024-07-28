<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php';

if (!empty($_POST['carrito'])) {
    $idProducto = htmlentities(intval($_POST['carrito']));
    $cantidad = 1;
    $idCliente = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;

    if ($idCliente) {
        // Usuario registrado
        $idClienteNoRegistrado = NULL;
        $query = "INSERT INTO CarritoCompra (id_cliente, id_cliente_no_registrado, id_producto, cantidad) 
                  VALUES (?, ?, ?, ?) 
                  ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iiii', $idCliente, $idClienteNoRegistrado, $idProducto, $cantidad);
    } else {
        // Usuario no registrado
        $idClienteNoRegistrado = 1; // Usuario no registrado tiene un ID fijo de 1
        $query = "INSERT INTO CarritoCompra (id_cliente_no_registrado, id_producto, cantidad) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iii', $idClienteNoRegistrado, $idProducto, $cantidad);
    }

    if ($stmt->execute()) {
        echo "Producto agregado al carrito exitosamente.";
    } else {
        echo "Error al agregar el producto al carrito: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método de solicitud no válido.";
}
header ('Location: ../index.php');
?>
