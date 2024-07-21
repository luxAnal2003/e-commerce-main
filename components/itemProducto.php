<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <?php
            require_once 'database/connection.php';
            
            $sql = "select id, nombre, descripcion, precio, imagen_url from productos";
            $resultado = mysqli_query($conn, $sql);
        
            if ($resultado->num_rows > 0) {//¿hay registros en la base de datos?
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    $descripcion = $fila['descripcion'];
                    $descripcionCorta = strlen($descripcion) > 30 ? substr($descripcion, 0, 40) . '...' : $descripcion;
                ?>
                    <div class="itemProducto" onclick="redirigirProducto(event)">
                        <form method="POST" action="index.php" class="productoForm">
                            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                            <input type="hidden" name="verProducto" value="1">

                            <div class="verProducto">
                                <div class="contenedorProducto">
                                    <img src="<?php echo $fila['imagen_url']; ?>" alt="<?php echo $fila['nombre']; ?>">
                                </div>
                                <h2><?php echo $fila['nombre']; ?></h2>
                                <p class="descripcion"><?php echo $descripcionCorta; ?></p>                            
                                <p>Precio: $<?php echo $fila['precio']; ?></p>
                            </div>
                        </form>
                        <button onclick="event.stopPropagation();">Añadir al carrito</button>
                    </div>
                <?php
                }
                
            } else {
                echo "No hay productos disponibles";
            }
            
        ?>   
    </body>
</html>