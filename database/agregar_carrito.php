<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php';

if (!empty($_POST['carrito'])) {
    $idProducto = intval($_POST['carrito']);
    $cantidad = 1;
    $idCliente = isset($_SESSION['id']) ? intval($_SESSION['id']) : null;

    if ($idCliente) {
        // Usuario registrado
        $query = "INSERT INTO CarritoCompra (id_cliente, id_producto, cantidad) 
                  VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iii', $idCliente, $idProducto, $cantidad);
    } else {
        // Usuario no registrado
        // Obtener o crear una nueva sesión para el cliente no registrado
        if (!isset($_SESSION['id_sesion'])) {
            $query = "INSERT INTO Sesiones (id_cliente_no_registrado) VALUES (NULL)";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $_SESSION['id_sesion'] = $conn->insert_id;
        }

        $idSesion = $_SESSION['id_sesion'];

        // Agregar productos al carrito con el id de sesión correspondiente
        $query = "INSERT INTO CarritoCompra (id_producto, cantidad, id_sesion) 
                  VALUES (?, ?, ?)
                  ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iii', $idProducto, $cantidad, $idSesion);
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
