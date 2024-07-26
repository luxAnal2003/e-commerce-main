<?php
session_start();
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $edad = trim($_POST['edad']);
    $sexo = trim($_POST['sexo']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $documento_identidad = trim($_POST['documento_identidad']);
    $contrasena = trim($_POST['contrasena']);
    $correo_electronico = trim($_POST['correo_electronico']);
    $ubicacion = trim($_POST['ubicacion']);

    // Validación básica
    if (empty($nombre) || empty($apellido) || empty($documento_identidad) || empty($contrasena) || empty($correo_electronico) || empty($ubicacion)) {
        $error = "Todos los campos son obligatorios.";
        echo "<script>alert('$error'); window.history.back();</script>";;
    } else {
        // Verificar si el correo electrónico ya existe
        $sql = "SELECT id FROM ClienteRegistrado WHERE correo_electronico = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo_electronico);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "El correo electrónico ya está en uso.";
            echo "<script>alert('$error'); window.history.back();</script>";
        } else {
            // Verificar si el documento de identidad ya existe
            $sql = "SELECT id FROM ClienteRegistrado WHERE documento_identidad = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $documento_identidad);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "El documento de identidad ya está en uso.";
                echo "<script>alert('$error'); window.history.back();</script>";
            } else {
                //insertar el nuevo usuario en la base de datos
                $sql = "INSERT INTO ClienteRegistrado (nombre, apellido, edad, sexo, fecha_nacimiento, documento_identidad, contrasena, correo_electronico, ubicacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssissssss", $nombre, $apellido, $edad, $sexo, $fecha_nacimiento, $documento_identidad, $contrasena, $correo_electronico, $ubicacion);

                try {
                    $stmt->execute();
                    $_SESSION['id'] = $stmt->insert_id;
                    $_SESSION['correo_electronico'] = $correo_electronico;
                    echo "<script>alert('Registro exitoso'); window.location.href = '../index.php';</script>";
                } catch (mysqli_sql_exception $e) {
                    $error = "Ocurrió un error al registrar el usuario. Por favor, intenta nuevamente.";
                    echo "<script>alert('$error'); window.history.back();</script>";
                }
            }
            $stmt->close();
        }
    }
}
?>
