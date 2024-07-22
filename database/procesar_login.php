<?php
session_start();
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo_electronico = trim($_POST['correo_electronico']);
    $contrasena = trim($_POST['contrasena']);

    // Validación
    if (empty($correo_electronico) || empty($contrasena)) {
        die("Todos los campos son obligatorios.");
    }

    $sql = "SELECT id, nombre, contrasena FROM ClienteRegistrado WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $correo_electronico);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        // Comparar la contraseña directamente
        if ($contrasena === $usuario['contrasena']) {
            // Iniciar sesión y guardar variables de sesión
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['correo_electronico'] = $correo_electronico;
            header("Location: ../index.php");
            exit();
        } else {
            die("Contraseña incorrecta. Verifica que estás ingresando la contraseña correcta.");
        }
    } else {
        die("Usuario no encontrado. Asegúrate de que el correo electrónico sea correcto.");
    }

    $stmt->close();
} else {
    die("Acceso no autorizado.");
}
?>
