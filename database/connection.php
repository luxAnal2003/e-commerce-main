<?php
$servername = "localhost";
$username = "root"; // Cambia esto si tienes un usuario diferente
$password = ""; // Cambia esto si tienes una contraseña diferente
$dbname = "ecommerce";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, "3307");

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
