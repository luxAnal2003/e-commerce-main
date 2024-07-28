<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idProducto = intval($_POST['carrito']);
    $cantidad = 1;
    $idCliente = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;
    $idClienteNoRegistrado = 1;

    if ($idCliente) {
        // Usuario registrado
        $query = "INSERT INTO CarritoCompra (id_cliente, id_producto, cantidad) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iii', $idCliente, $idProducto, $cantidad);
    } else {
        // Usuario no registrado
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
?>