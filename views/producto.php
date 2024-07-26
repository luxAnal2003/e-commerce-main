<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'database/connection.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="css/navbar y footer.css">
    <style>
        .infoProducto {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            height: 300px;
        }

        .infoProducto img{
            width: 300px;
            height: auto;
            margin: 10px;
        }

        .detallesProducto {
            width: 500px;
            margin: 10px;
            text-align: left;
        }

        .infoCompra {
            background-color: #f8f8f8;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            margin: 30px;
            flex-direction: column;
            border-radius: 10px;
        }

        .infoCompra p {
            margin: 5px 0;
        }

        .infoCompra .disponibilidad {
            color: #3d5a80;
            font-weight: bold;
        }

        .contenedorResena, .resena, .replica {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f8f8;
        }

        .contenedorResena h2 {
            text-align: center;
        }
        .contenedorResena p{
            margin: 20px;
        }

        .resena, .replica {
            background-color: #fff;
        }

        .resena p, .replica p {
            margin: 5px 0;
        }

        .replica {
            border-color: #ccc;
        }
        
        .contenedorReplica {
            display: flex;
            flex-direction: column;
        }

        .contenedorReplica textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        hr {
            border: 0;
            height: 1px;
            background: #ddd;
        }

        .contenedor{
            padding: 20px;
            text-align: left;  
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
        <?php
            if (!empty($_POST['verProducto'])) {
                $id = htmlentities($_POST['verProducto']);
                $sql = "select * from productos where id = $id";
                $resultado = mysqli_query($conn, $sql);
            
                if ($producto= mysqli_fetch_assoc($resultado)) {
                    // Procesar la descripción para convertir comas en puntos de lista
                    $descripcion = $producto['descripcion'];
                    // Separar la descripción por comas y convertir cada palabra a mayúsculas
                    $descripcionItems = explode(',', $descripcion);
                    // Eliminar espacios en blanco adicionales
                    $descripcionItems = array_map('trim', $descripcionItems); 
                    //Colocar la primera letra en mayus
                    $descripcionItems = array_map(function($item) {
                        return ucwords(strtolower($item));
                    }, $descripcionItems);
                    ?>
                    <div class="infoProducto">
                        <div class="imgProducto">
                            <img src="assets/uploads/<?= $producto['imagen_url'] ?>" alt="<?= $producto['nombre'] ?>">
                        </div>
                        <div class="detallesProducto">
                            <h2><?= $producto['nombre']; ?></h2>
                            <ul>
                                <?php foreach ($descripcionItems as $item): ?>
                                    <li><?= $item; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="infoCompra">
                            <form method="POST" action="database/agregar_ carrito.php" class="verProducto">
                                <input type="hidden" name="carrito" value="<?= $producto['id'] ?>">
                                <input type="hidden" name="cantidad" value="1">
                                <p class="disponibilidad">Disponible</p>
                                <p>Precio: $<?= $producto['precio']; ?></p>
                                <p>Cant: <?= $producto['stock']; ?></p>
                                <button type="submit">Agregar al carrito</button>
                            </form>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "Producto no encontrado.";
                }
            } else {
                echo "ID del producto no proporcionado.";
            }
        ?>
        <div class="contenedor">
            <section class="contenedorResena">
                <h2>Foro del producto</h2>
                <?php if (isset($_SESSION['id'])){ ?>
                    <div class="resena">
                        <form action="database/insertar_comentario.php" method="POST">
                            <input type="hidden" name="verProducto" value="<?= htmlspecialchars($id) ?>">
                            <div class="contenedorReplica">
                                <textarea name="mensaje" required></textarea>
                                <button type="submit">Enviar</button>
                            </div>
                        </form>
                    </div>
                <?php } else {?>
                    <p>Para comentar, <a href="forms/login.html">inicia sesión</a>.</p>
                <?php }
                $sqlForoCount = "SELECT COUNT(*) as count FROM MensajesForo WHERE id_producto = $id";
                $resultForoCount = mysqli_query($conn, $sqlForoCount);
                $countRow = mysqli_fetch_assoc($resultForoCount);

                if ($countRow['count'] == 0) {
                    echo "<p>No hay comentarios aún. Sé el primero en comentar.</p>";
                } else {
                    function mostrarMensajes($id_producto, $id_respuesta_a = null, $nivel = 1) {
                        global $conn;
                        
                        // Consulta para obtener los mensajes y sus detalles
                        $sqlForo = "SELECT mf.id, mf.mensaje, mf.fecha, mf.id_usuario, mf.id_encargado, 
                                           cr.nombre AS nombre_usuario, cr2.nombre AS nombre_replicado, 
                                           e.nombre AS nombre_encargado
                                    FROM MensajesForo mf
                                    LEFT JOIN ClienteRegistrado cr ON mf.id_usuario = cr.id
                                    LEFT JOIN MensajesForo mf2 ON mf.id_respuesta_a = mf2.id
                                    LEFT JOIN ClienteRegistrado cr2 ON mf2.id_usuario = cr2.id
                                    LEFT JOIN EncargadoInventarios e ON mf.id_encargado = e.id
                                    WHERE mf.id_producto = $id_producto 
                                    AND mf.id_respuesta_a " . (is_null($id_respuesta_a) ? "IS NULL" : "= $id_respuesta_a") . "
                                    ORDER BY mf.fecha DESC";
                        
                        $resultForo = mysqli_query($conn, $sqlForo);
                        
                        if ($resultForo->num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($resultForo)) {
                                $nombreAutor = $row['nombre_usuario'] ? $row['nombre_usuario'] : $row['nombre_encargado'];
                                ?>
                                <div class="replica" style="margin-left: <?= $nivel * 20 ?>px;">
                                    <?php if (!is_null($row['nombre_replicado'])) { ?>
                                        <p><b>Re: <?= htmlspecialchars($row['nombre_replicado']) ?></b></p>
                                    <?php } ?>
                                    <p>Por: <?= htmlspecialchars($nombreAutor); ?> el <?= htmlspecialchars(date('d/m/Y H:i', strtotime($row['fecha']))); ?></p>
                                    <hr>
                                    <p>Mensaje: <?= htmlspecialchars($row['mensaje']) ?></p>
                                    <?php if (isset($_SESSION['id'])) { ?>
                                        <a href="#" onclick="formReplicar(<?= $row['id'] ?>); return false;">Replicar</a>
                                        <div id="replicar<?= $row['id'] ?>" style="display: none;">
                                            <form action="database/insertar_comentario.php" method="POST">
                                                <input type="hidden" name="verProducto" value="<?= htmlspecialchars($id_producto) ?>">
                                                <input type="hidden" name="id_respuesta_a" value="<?= htmlspecialchars($row['id']) ?>">
                                                <div class="contenedorReplica">
                                                    <textarea name="mensaje" required></textarea>
                                                    <button type="submit">Responder</button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                                // Mostrar respuestas recursivamente
                                mostrarMensajes($id_producto, $row['id'], $nivel + 1);
                            }
                        }
                    }
                    
                    // Llamada a la función con el ID del producto
                    mostrarMensajes($id);
                }                    
                ?>
            </section>

        </div> 
    </main>
    <script>
        function redirigir(url){
            window.location.href = url; 
        }
        function formReplicar(id) {
            const form = document.getElementById('replicar' + id);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</body>
</html>