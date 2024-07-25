<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Encargado de Inventarios</title>
        <link rel="stylesheet" href="../css/navbar-footer.css">
        <style>
            .contenedor{
                padding: 20px;
                text-align: left;
            }
            
            .contenido {
                display: none;
            }

            .activo {
                display: block;
            }

            #titulos {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }

            .titulo {
                margin-right: 10px;
                cursor: pointer;
            }

            .titulo.activo {
                font-weight: bold;
                color: green;
            }

            .nuevoProd{
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .nuevoProd h2{
                flex:1;
                text-align: center;
            }

            .listaProductos{
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .itemProducto {
                border: 1px solid #ccc;
                padding: 20px;
                margin: 5px;
                width: 150px;
                text-align: center;
                border-radius: 10px;
            }

            .itemProducto img{
                width: 150px;
                height: auto;
            }

            .resena a{
                text-decoration: none;
                color: #ee6c4d;
            }

            .resena, .replica {
                margin: 20px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f8f8f8;
            }

            .resena p, .replica p {
                margin: 5px 0;
            }

            hr {
                border: 0;
                height: 1px;
                background: #ddd;
            }
            .verProducto {
                cursor: pointer;
            }

            button {
                background-color: #3d5a80;
                color: #ffffff;
                font-family: 'Lato', sans-serif;
                font-size: 14px;
                border: none;
                padding: 10px;
                cursor: pointer;
                border-radius: 50px;
            }

            button:hover {
                background-color: #98c1d9;
            }

        </style>
    </head>
    <body>
        <?php
            if(isset($_SESSION['user_id'])) {
                $userLoggedIn = true;
                $userRole = $_SESSION['role'];
            } else {
                $userLoggedIn = false;
            }
        ?>
        <?php include '../navbars/EncargadoInventarios.html'; ?>
        <main>
            <?php include '../views/productosAlfab.php'; ?>
        </main>
        <?php include '../templates/footer.html'; ?>
    </body>
</html>
