<?php
session_start();
require_once 'connection.php';

if (isset($_SESSION['id']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_SESSION['id'];
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $fecha_expiracion = $_POST['fecha_expiracion'];
    $cvv = $_POST['cvv'];

    // Insertar datos bancarios del cliente registrado
    $sql = "INSERT INTO DatosBancarios (id_cliente, numero_tarjeta, fecha_expiracion, cvv) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_cliente, $numero_tarjeta, $fecha_expiracion, $cvv);

    if ($stmt->execute()) {
        echo "Datos bancarios guardados correctamente.";
    } else {
        echo "Error al guardar los datos bancarios: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Acceso no autorizado.";
}
?>


