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
            .slider-container {
                position: relative;
                overflow: hidden;
                width: 80%;
                margin: 0 auto;
                
            }
            .slider-wrapper {
                display: flex;
                transition: transform 0.3s ease-in-out;
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

            .productos{
                padding: 20px;
                text-align: center;
            }

            .listaProductos{
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
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
            <section class="productos">
                <?php
                    require 'database/connection.php';

                    $sql_categorias = "SELECT * FROM Categorias";
                    $resultado = $conn->query($sql_categorias);

                    if ($resultado->num_rows > 0) {
                        while ($categoria = $resultado->fetch_assoc()) {
                            $categoria_id = $categoria['id'];
                            $categoria_nombre = htmlspecialchars($categoria['nombre']);

                            echo "<h2>{$categoria_nombre}</h2>";

                            $sql_productos = "
                                SELECT p.id, p.nombre, p.imagen_url, p.precio 
                                FROM productos p
                                JOIN productocategoria pc ON p.id = pc.id_producto
                                WHERE pc.id_categoria = ?
                                ORDER BY p.nombre ASC";
                            $stmt_productos = $conn->prepare($sql_productos);
                            $stmt_productos->bind_param("i", $categoria_id);
                            $stmt_productos->execute();
                            $result_productos = $stmt_productos->get_result();

                            if ($result_productos->num_rows > 0) {
                                $productos = $result_productos->fetch_all(MYSQLI_ASSOC);
                                $count = count($productos);

                                if ($count <= 5) {
                                    // Si hay 5 o menos productos, los mostramos centrados sin slider
                                    echo "<div class='listaProductos'>";
                                    foreach ($productos as $producto) {
                                        mostrarProducto($producto);
                                    }
                                    echo "</div>";
                                } else {
                                    // Si hay más de 5 productos, usamos el slider
                                    echo "<div class='slider-container' id='slider-{$categoria_id}'>";
                                    echo "<div class='slider-wrapper'>";
                                    foreach ($productos as $producto) {
                                        mostrarProducto($producto);
                                    }
                                    echo "</div>";
                                    echo "<button class='prev' onclick='cambiar(-1, {$categoria_id})'>&#10094;</button>";
                                    echo "<button class='sgn' onclick='cambiar(1, {$categoria_id})'>&#10095;</button>";
                                    echo "</div>";
                                }
                            } else {
                                echo '<p>No hay productos en esta categoría.</p>';
                            }
                        }
                    } else {
                        echo '<p>No hay categorías disponibles.</p>';
                    }


                ?>
            </section>

            <?php function mostrarProducto($producto) {
                $nombre = $producto['nombre'];
                $nombreCorto = strlen($nombre) > 25 ? substr($nombre, 0, 25) . '...' : $nombre;;?>
                <div class="listaProductos">
                    <div class="itemProducto" onclick="redirigirProducto(event)">
                    <form method="POST" action="index.php" class="productoForm">
                        <input type="hidden" name="verProducto" value="<?= $producto['id'] ?>">
                        <div class="verProducto">
                            <img src="assets/uploads/<?= $producto['imagen_url'] ?>" alt="<?= $producto['nombre'] ?>">
                            <div class="contenedorNombreProducto">
                                <p><?=$nombreCorto?></p>
                            </div>
                            <p>$<?= $producto['precio'] ?></p>
                        </div>
                    </form>
                    <button onclick="event.stopPropagation();">Añadir al carrito</button>
                    </div>
                </div>
            <?php } ?>
        </main>
        <script>
            function redirigirProducto(event) {
                if (!event.target.closest('button')) {
                    event.currentTarget.querySelector('.productoForm').submit();
                }
            }
            function cambiar(direccion, categoriaId) {
                const sliderContainer = document.querySelector(`#slider-${categoriaId}`);
                const slider = sliderContainer.querySelector('.slider-wrapper');
                const items = slider.querySelectorAll('.itemProducto');
                const itemWidth = items[0].offsetWidth + 10; // Añadir margen
                
                // Calcular cuántos items caben en el contenedor
                const containerWidth = sliderContainer.offsetWidth;
                const visibleItems = Math.floor(containerWidth / itemWidth);

                let currentPosition = parseInt(slider.style.transform.replace('translateX(', '').replace('px)', '') || 0);
                let nuevaPosicion = currentPosition + direccion * itemWidth * visibleItems;

                // Ajustar la posición si llegamos al final o al principio
                if (nuevaPosicion > 0) {
                    nuevaPosicion = -(Math.max(0, items.length - visibleItems) * itemWidth);
                } else if (nuevaPosicion < -(Math.max(0, items.length - visibleItems) * itemWidth)) {
                    nuevaPosicion = 0;
                }

                slider.style.transform = `translateX(${nuevaPosicion}px)`;
            }
        </script>
    </body>
</html>