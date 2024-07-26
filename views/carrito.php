<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Carrito de Compras</title>
        <style>
            .carrito {
                max-width: 70%;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .carrito h1 {
                text-align: center;
                margin-bottom: 20px;
            }

            .itemCarrito {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border-bottom: 1px solid #ddd;
                background-color: #f8f8f8;
            }

            .productoInfo {
                display: flex;
                align-items: center;
                flex-grow: 2;
                margin-right: 20px;
            }

            .productoInfo img {
                width: 100px;
                height: auto;
                margin-right: 20px;
            }

            .productoInfo p {
                margin: 0;
            }

            .cantidadProducto {
                display: flex;
                align-items: center;
                margin-top: 5px;
            }

            .cantidadProducto a {
                text-decoration: none;
                color: #4CAF50;
                font-size: 20px;
                padding: 0 10px;
            }

            .cantidadProducto a:hover {
                text-decoration: underline;
            }

            .cantidadProducto .cantidad {
                margin: 0 10px;
                font-size: 18px;
            }

            .subtotalCarrito {
                text-align: right;
                padding: 10px 0;
            }

            .subtotalCarrito p {
                margin: 10px 0;
            }

            button {
                background-color: #ee6c4d;
                color: white;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                border-radius: 5px;
            }



            button:hover {
                background-color: #c9302c;
            }

            hr {
                border: 0;
                height: 1px;
                background: #ddd;
            }

        </style>
        
        <link rel="stylesheet" href="css/navbar-footer.css">

    </head>
    <body>
        <section class="carrito">
            <h1>Carrito</h1>
            <div class="itemCarrito">
                <div class="productoInfo">
                    <img src="assets/img/promocionMueble.png" alt="imgProducto">
                    <p>HyperX Cloud II Core Wireless - Auriculares de diadema para videojuegos, con tecnología de voz azul, color negro</p>
                    <p>Precio: US$99.99</p>
                    <div clas="cantidadProducto">
                        <p class="cantidad">Cant: 1 </p>
                        <a href="#" class="agregar"> Agregar</a>
                        <a href="#" class="eliminar"> Eliminar</a>    
                    </div>
                </div>
            </div>
            <div class="itemCarrito">
                <div class="productoInfo">
                    <img src="assets/img/promocionMueble.png" alt="imgProducto">
                    <p>HyperX Cloud II Core Wireless - Auriculares de diadema para videojuegos, con tecnología de voz azul, color negro</p>
                    <p>Precio: US$99.99</p>
                    <div clas="cantidadProducto">
                        <p class="cantidad">Cant: 1 </p>
                        <a href="#" class="agregar"> Agregar</a>
                        <a href="#" class="eliminar"> Eliminar</a>    
                    </div>
                </div>
            </div>
            <div class="itemCarrito">
                <div class="productoInfo">
                    <img src="assets/img/promocionMueble.png" alt="imgProducto">
                    <p>HyperX Cloud II Core Wireless - Auriculares de diadema para videojuegos, con tecnología de voz azul, color negro</p>
                    <p>Precio: US$99.99</p>
                    <div clas="cantidadProducto">
                        <p class="cantidad">Cant: 3 </p>
                        <a href="#" class="agregar"> Agregar</a>
                        <a href="#" class="eliminar"> Eliminar</a>    
                    </div>
                </div>
            </div>

            <div class="subtotalCarrito">
                <p>Subtotal (3 producto/s): <strong>US$300.00</strong></p>
                <button onclick="redirigir('compras.php')">Proceder al pago</button>
            </div>
        </section>
        <script>
            function redirigir(url) {
                window.location.href = url;
            }
        </script>

    </body>
</html>