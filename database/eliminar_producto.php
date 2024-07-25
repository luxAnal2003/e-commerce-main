<?php
require_once 'connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Preparar la consulta para eliminar el producto
    $sql = "DELETE FROM Productos WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Redirigir a la lista de productos después de eliminar
            header("Location: ../dashboards/EncargadoInventarios.php");
            exit();
        } else {
            echo "Error al eliminar el producto: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }

    $conn->close();
} else {
    echo "ID del producto no proporcionado.";
}
?>
