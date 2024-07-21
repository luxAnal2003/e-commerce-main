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
        } elseif (isset($_POST['verProducto'])) {
            include 'views/producto.php';
        } else {
            include 'dashboards/Cliente-Registrado-NoRegistrado.php';
        }
        ?>

        <?php require 'templates/footer.html'; ?>
        <?php require 'database/connection.php'; ?>

    </body>
</html>
