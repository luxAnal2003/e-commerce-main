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
            text-align: justify;
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

        .resena h2 {
            text-align: center;
        }

        .replica {
            border-color: #ccc;
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
    <header>
        
    </header>

    <main>
    <?php
        require_once 'database/connection.php';
    ?>
    <main>
        <?php
            if (!empty($_POST['id'])) {
                $id = htmlentities($_POST['id']);
                $sql = "select * from productos where id = $id";
                $resultado = mysqli_query($conn, $sql);
            
                if ($fila = mysqli_fetch_assoc($resultado)) {
                    // Procesar la descripción para convertir comas en puntos de lista
                    $descripcion = $fila['descripcion'];
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
                            <img src="<?php echo $fila['imagen_url']; ?>" alt="<?php echo $fila['nombre']; ?>">
                        </div>
                        <div class="detallesProducto">
                            <h2><?php echo $fila['nombre']; ?></h2>
                            <ul>
                                <?php foreach ($descripcionItems as $item): ?>
                                    <li><?php echo $item; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="infoCompra">
                            <p class="disponibilidad">Disponible</p>
                            <p>Precio: $<?php echo $fila['precio']; ?></p>
                            <p>Cant: <?php echo $fila['stock']; ?></p>
                            <button onclick="redirigir('carrito.php')">Agregar al carrito</button>
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
            <section class="resena">
                <h2>Foro del producto</h2>
                <div class="resena">
                    <p>Usuario: Juan Pérez</p>
                    <p>Momento del envío: 2024-07-01 10:15:00</p>
                    <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                </div>
                <hr>
                <div class="resena">
                    <p>Usuario: Juan Pérez</p>
                    <p>Momento del envío: 2024-07-01 10:15:00</p>
                    <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                    <div class="replica">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                    </div>
                </div>
                <hr>
                <div class="resena">
                    <p>Usuario: Juan Pérez</p>
                    <p>Momento del envío: 2024-07-01 10:15:00</p>
                    <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                </div>
            </section>
        </div> 
    </main>
    <script>
        function redirigir(url){
            window.location.href = url; 
        }
    </script>
</body>
</html>
