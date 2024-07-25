<?php
session_start();
require_once '../database/connection.php';

// Inicializar variables
$productos = [];
$searchQuery = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $searchQuery = htmlentities($_POST['search']);
    
    $sql = "SELECT * FROM productos WHERE nombre LIKE '%$searchQuery%' OR descripcion LIKE '%$searchQuery%'";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado) {
        while ($producto = mysqli_fetch_assoc($resultado)) {
            $productos[] = $producto;
        }
    } else {
        echo "Error en la consulta: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Productos</title>
    <link rel="stylesheet" href="css/navbar y footer.css">
    <style>
        .search-container {
            display: flex;
            justify-content: center;
            margin: 20px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
        }

        .search-container button {
            background-color: #3d5a80;
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .search-container button:hover {
            background-color: #98c1d9;
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

        .contenedorNombreProducto {
            height: 30px;
        }

        .itemProducto img {
            width: 150px;
            height: auto;
        }

        .verProducto {
            cursor: pointer;
        }

        .producto {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .producto img {
            width: 150px;
            height: auto;
            margin-right: 20px;
        }

        .producto h2 {
            margin: 0;
        }

        .producto p {
            margin: 5px 0;
        }

        button {
            background-color: #3d5a80;
            color: #ffffff;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 20px;
        }

        button:hover {
            background-color: #98c1d9;
        }
    </style>
</head>
<body>
    <main>
        <div class="search-container">
            <form action="" method="POST">
                <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Buscar productos...">
                <button type="submit">Buscar</button>
            </form>
        </div>
        <?php if (!empty($productos)) { ?>
            <div class="listaProductos">
                <?php foreach ($productos as $producto) {
                    $nombre = htmlspecialchars($producto['nombre']);
                    $nombreCorto = strlen($nombre) > 25 ? substr($nombre, 0, 25) . '...' : $nombre;?>
                    <div class="itemProducto" onclick="redirigirProducto(event)">
                        <form method="POST" action="index.php" class="productoForm">
                            <input type="hidden" name="verProducto" value="<?= $producto['id'] ?>">
                            <div class="verProducto">
                                <img src="../assets/uploads/<?= $producto['imagen_url'] ?>" alt="<?= $producto['nombre'] ?>">
                                <div class="contenedorNombreProducto">
                                    <p><?=$nombreCorto?></p>
                                </div>
                                <p>$<?= $producto['precio'] ?></p>
                            </div>
                        </form>
                        <button onclick="event.stopPropagation();">Añadir al carrito</button>
                    </div>
                <?php } ?>
            </div>
        <?php } elseif (!empty($searchQuery)) { ?>
            <p>No se encontraron resultados para "<?= htmlspecialchars($searchQuery) ?>".</p>
        <?php } ?>
    </main>
    <script>
        function redirigirProducto(event) {
            if (!event.target.closest('button')) {
                event.currentTarget.querySelector('.productoForm').submit();
            }
        }
    </script>
</body>
</html>
