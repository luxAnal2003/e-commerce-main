<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="css/navbar y footer.css">
        <style>
            body {
                font-family: 'Roboto', sans-serif;
                margin: 0;
                padding: 0;
            }
            #servicios, .productos{
                padding: 20px;
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
            .contenedorNombreProducto {
                height: 30px;
            }

            .itemProducto img{
                width: 100%;
                height: auto;
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
                border-radius: 20px;
            }

            button:hover {
                background-color: #98c1d9;
            }
            
            .sliderContainer {
                position: relative;
                max-width: 80%;
                margin: auto;
                overflow: hidden;
            }

            .slider {
                display: flex;
                transition: transform 0.5s ease;
            }

            .slide {
                flex: 0 0 calc(20% - 10px); /* Mostrar 5 productos y agregar espacio */
                box-sizing: border-box;
                margin: 5px; /* Espacio entre productos */
            }

            .prev, .sgn {
                position: absolute;
                top: 50%;
                background-color: #3d5a80;
                color: white;
                border: none;
                padding: 10px;
                cursor: pointer;
            }
            .prev {
                left: 10px;
            }

            .sgn {
                right: 10px;
            }


        </style>
    </head>
    <body>
        <header>

        </header>
        <main>
        <section class="productos">
            <?php
            require '../database/connection.php';

            $sql_categorias = "SELECT * FROM Categorias";
            $resultado = $conn->query($sql_categorias);
            
            if ($resultado->num_rows > 0) {
                while ($categoria = $resultado->fetch_assoc()) {
                    $categoria_id = $categoria['id'];
                    $categoria_nombre = htmlspecialchars($categoria['nombre']);
            
                    echo "<h2>{$categoria_nombre}</h2>";
            
                    // Obtener el número total de productos en la categoría
                    $sql_count = "
                        SELECT COUNT(*) as total 
                        FROM productos p
                        JOIN productocategoria pc ON p.id = pc.id_producto
                        WHERE pc.id_categoria = ?";
                    $stmt_count = $conn->prepare($sql_count);
                    $stmt_count->bind_param("i", $categoria_id);
                    $stmt_count->execute();
                    $result_count = $stmt_count->get_result();
                    $total_productos = $result_count->fetch_assoc()['total'];
            
                    // Establecer la paginación
                    $productos_por_pagina = 5;
                    $total_paginas = ceil($total_productos / $productos_por_pagina);
                    $pagina_actual = isset($_GET['pagina_' . $categoria_id]) ? (int)$_GET['pagina_' . $categoria_id] : 1;
                    $inicio = ($pagina_actual - 1) * $productos_por_pagina;
            
                    // Obtener productos de la categoría actual con paginación
                    $sql_productos = "
                        SELECT p.id, p.nombre, p.imagen_url, p.precio 
                        FROM productos p
                        JOIN productocategoria pc ON p.id = pc.id_producto
                        WHERE pc.id_categoria = ?
                        ORDER BY p.nombre ASC
                        LIMIT ?, ?";
                    $stmt_productos = $conn->prepare($sql_productos);
                    $stmt_productos->bind_param("iii", $categoria_id, $inicio, $productos_por_pagina);
                    $stmt_productos->execute();
                    $result_productos = $stmt_productos->get_result();
            
                    if ($result_productos->num_rows > 0) {
                        echo '<div class="listaProductos">';
                        while ($producto = $result_productos->fetch_assoc()) {
                            $nombre = $producto['nombre'];
                            $nombreCorto = strlen($nombre) > 25 ? substr($nombre, 0, 25) . '...' : $nombre;?>
                            <div class="itemProducto" onclick="redirigirProducto(event)">
                                <form method="POST" action="../index.php" class="productoForm">
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
                        <?php }
                        echo '</div>';
            
                        // Mostrar enlaces de paginación
                        echo '<div class="paginacion">';
                        for ($i = 1; $i <= $total_paginas; $i++) {
                            echo '<a href="?pagina_' . $categoria_id . '=' . $i . '">' . $i . '</a> ';
                        }
                        echo '</div>';
                    } else {
                        echo '<p>No hay productos en esta categoría.</p>';
                    }
                }
            } else {
                echo '<p>No hay categorías disponibles.</p>';
            }
            ?>
            
        </section>

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

