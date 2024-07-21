<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard Encargado de Inventarios</title>
        <link rel="stylesheet" href="../css/navbar-footer.css">
        <link rel="stylesheet" href="../css/style.css">
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
        <?php include '../navbars/EncargadoInventarios.html'; ?>
        <main style="margin-top: 110px;">
            <div class="contenedor">
               
                <section class="productos">
                    <h2>Todos los productos</h2>
                    <div class="listaProductos">
                        <?php
                        require '../database/connection.php';
                        $query = "SELECT * FROM Productos ORDER BY nombre ASC";
                        $result = $conn->query($query);

                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="itemProducto">';
                            echo '<div class="verProducto" onclick="redirigir(\'producto.php?id=' . $row['id'] . '\')">';
                            echo '<img src="../assets/uploads/' . $row['imagen_url'] . '" alt="' . $row['nombre'] . '">';
                            echo '<p>' . $row['nombre'] . '</p>';
                            echo '<p>$' . $row['precio'] . '</p>';
                            echo '</div>';
                            echo '<button style="border-radius: 50px; margin-right: 4px;" onclick="editarProducto(' . $row['id'] . ')">Editar</button>';
                            echo '<button style="border-radius: 50px" onclick="eliminarProducto(' . $row['id'] . ')">Eliminar</button>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </section>
                
                <div id="clientesPorResponder" class="contenido">
            <section class="resena">
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                        <a href="#" class="replicar">Replicar</a>
                    </div>
                    <hr>
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                        <a href="#" class="replicar">Replicar</a>
                    </div>
                    <hr>
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                        <a href="#" class="replicar">Replicar</a>
                    </div>
                    <hr>
                </section>
            </div>
            <div id="clientesRespondidos" class="contenido">
            <section class="resena">
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>

                        <div class="replica">
                            <p>Usuario: Carlos García</p>
                            <p>Momento del envío: 2024-07-02 12:05:00</p>
                            <p>Mensaje: "@María López, tuve el mismo problema. Lo solucioné actualizando el software Logitech G HUB. Después de la actualización, el micrófono funcionó mucho mejor."</p>

                        </div>
                    </div>
                    <hr>
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>
                        <div class="replica">
                            <p>Usuario: Carlos García</p>
                            <p>Momento del envío: 2024-07-02 12:05:00</p>
                            <p>Mensaje: "@María López, tuve el mismo problema. Lo solucioné actualizando el software Logitech G HUB. Después de la actualización, el micrófono funcionó mucho mejor."</p>

                        </div>
                    </div>
                    <hr>
                    <div class="resena">
                        <p>Usuario: Juan Pérez</p>
                        <p>Momento del envío: 2024-07-01 10:15:00</p>
                        <p>Mensaje: "Compré estos auriculares la semana pasada y la calidad del sonido es increíble. La cancelación de ruido es excelente y el micrófono funciona a la perfección."</p>

                        <div class="replica">
                            <p>Usuario: Carlos García</p>
                            <p>Momento del envío: 2024-07-02 12:05:00</p>
                            <p>Mensaje: "@María López, tuve el mismo problema. Lo solucioné actualizando el software Logitech G HUB. Después de la actualización, el micrófono funcionó mucho mejor."</p>

                        </div>
                    </div>
                    <hr>
                </section>
            </div>

        </div>

            </div>
        </main>
        <?php include '../templates/footer.html'; ?>
        <script>
            function redirigir(url) {
                window.location.href = url;
            }

            function editarProducto(id) {
                // Lógica para editar producto
                redirigir('editar_producto.php?id=' + id);
            }

            function eliminarProducto(id) {
                if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                    // Lógica para eliminar producto
                    redirigir('eliminar_producto.php?id=' + id);
                }
            }
                   
        </script>
    </body>
</html>
