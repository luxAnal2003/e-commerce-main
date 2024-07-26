<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//verificacion de login
$isLoggedIn = isset($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="css/navbar-footer.css">
        <link rel="stylesheet" href="../css/navbar-footer.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="css/style.css">
        <style>
            button.cuenta{
                border-radius: 0;
            }
        </style>
    </head>
    <body>

        <?php include 'templates/header.html'; ?>

        <?php
        if (isset($_POST['cuenta'])) {
            include 'redirects/sign-in-up.html';
        } elseif (isset($_POST['carrito'])) {
            include 'views/carrito.php';
        } elseif (isset($_POST['verProducto']) || isset($_GET['verProducto'])) {
            $id_producto = isset($_POST['verProducto']) ? $_POST['verProducto'] : $_GET['verProducto'];
            $_POST['verProducto'] = $id_producto; 
            include 'views/producto.php';
        } elseif (isset($_POST['verProductoCatg'])) {
            include 'views/productosCategoria.php';
        } else {
            include 'dashboards/Cliente-Registrado-NoRegistrado.php';
        }
        ?>

        <?php require 'templates/footer.html'; ?>
        <?php require 'database/connection.php'; ?>

    </body>
</html>
