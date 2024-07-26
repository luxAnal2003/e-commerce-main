<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['correo_electronico'];
    $password = $_POST['contrasena'];

    // Buscar en ambas tablas
    $stmt = $conn->prepare("
        SELECT 'encargado' AS role, id, contrasena
        FROM EncargadoInventarios
        WHERE correo_personal = ?
        UNION
        SELECT 'cliente' AS role, id, contrasena
        FROM ClienteRegistrado
        WHERE correo_electronico = ?
    ");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($password == $user['contrasena']) { // Comparación directa
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'] == 'encargado' ? 'encargado' : 'cliente';
            
            // Redirigir al dashboard que le pertence
            if ($user['role'] == 'encargado') {
                header("Location: ../dashboards/EncargadoInventarios.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        }
    }

    
    echo "Email o contraseña incorrectos";
}
?>
