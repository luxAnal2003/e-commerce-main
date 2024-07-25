<?php
require '../database/connection.php';

// Consultar todos los productos en orden alfabético
$query = "SELECT * FROM Productos ORDER BY nombre ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .contenedor{
            padding: 20px;
            text-align: left;
            margin: 30px;
        }

        .listaProductos {
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
        .itemProducto img {
            width: 150px;
            height: auto;
        }

        .contenedorNombreProducto {
            height: 30px;
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
    <?php include '../navbars/EncargadoInventarios.html'; ?>
    <main style="margin-top: 110px;">
        <div class="contenedor">
            <h2>Todos los Productos</h2>
            <div class="listaProductos">
                <?php
                while ($row = $result->fetch_assoc()) {
                    $nombre = htmlspecialchars($row['nombre']);
                    $nombreCorto = strlen($nombre) > 25 ? substr($nombre, 0, 25) . '...' : $nombre;?>
                    <div class="itemProducto">
                        <form method="POST" action="../index.php" class="productoForm">
                            <input type="hidden" name="verProducto" value="<?= $row['id'] ?>">
                            <img src="../assets/uploads/<?= $row['imagen_url'] ?>" alt="<?= $row['nombre'] ?>">
                            <div class="contenedorNombreProducto">
                                <p><?=$nombreCorto?></p>
                            </div>
                            <p>$<?= htmlspecialchars($row['precio']) ?></p>
                        </form>
                        <button onclick="editarProducto(<?= $row['id'];?>)">Editar</button>
                        <button onclick="confirmarEliminar(<?= $row['id']; ?>)">Eliminar</button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php include '../templates/footer.html'; ?>
    <script>
        function confirmarEliminar(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                window.location.href = '../database/eliminar_producto.php?id=' + id;
            }
        }
        function editarProducto(id) {
            if (confirm('¿Estás seguro de que deseas editar este producto?')) {
                window.location.href = '../database/editar_producto.php?id=' + id;
            }
        }
    </script>
</body>
</html>
