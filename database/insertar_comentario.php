<?php
session_start();
require_once 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si el usuario está logueado
    if (isset($_SESSION['id'])) {
        $id_usuario = $_SESSION['id'];
        $id_producto = isset($_POST['verProducto']) ? intval($_POST['verProducto']) : null;
        $id_respuesta_a = isset($_POST['id_respuesta_a']) ? intval($_POST['id_respuesta_a']) : null;
        $mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

        if (empty($id_usuario) || empty($mensaje) ) {
            $error = "Todos los campos son obligatorios.";
            echo "<script>alert('$error'); window.history.back();</script>";
        } else if ($id_producto && $mensaje) {
            $mensaje = mysqli_real_escape_string($conn, $mensaje);
            $fecha = date('Y-m-d H:i:s');
            
            // Insertar el comentario en la base de datos
            $sql = "INSERT INTO MensajesForo (id_usuario, id_producto, id_respuesta_a, mensaje, fecha) 
                    VALUES ($id_usuario, $id_producto, " . ($id_respuesta_a ? $id_respuesta_a : 'NULL') . ", '$mensaje', '$fecha')";

            if (mysqli_query($conn, $sql)) {
                if (isset($_POST['verProducto'])) {
                    header("Location: ../index.php?verProducto=" . $id_producto);
                }
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Datos inválidos.";
        }
    } else {
        echo "Debe iniciar sesión para comentar.";
    }
} else {
    echo "Método de solicitud no permitido.";
}
?>
