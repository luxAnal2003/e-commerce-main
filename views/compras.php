<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Compras Pasadas</title>
    <link rel="stylesheet" href="css/navbar y footer.css">
    <style>
        .factura {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .factura h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .factura-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .factura-header div {
            width: 33%;
            text-align: center;
        }

        .factura-header div p {
            margin: 5px 0;
        }

        .itemCarrito {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            background: #f8f8f8;
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

    </style>
</head>
<body>
    <div class="factura">
        <h1>COMPRAS PASADAS</h1>
        <div class="factura-header">
            <div>
                <p><strong>Momento de Compra</strong></p>
                <p>Fecha: Miércoles, 10 de julio del 2024</p>
                <p>Hora: 22:56</p>
            </div>
            <div>
                <p><strong>Momento de Entrega</strong></p>
                <p>Fecha: Miércoles, 10 de julio del 2024</p>
                <p>Hora: 8:10</p>
            </div>
            <div>
                <p><strong>Total a pagar: US$100</strong></p>
            </div>
        </div>
        <div class="itemCarrito">
            <div class="productoInfo">
                <img src="assets/img/promocionMueble.png" alt="imgProducto">
                <p>HyperX Cloud II Core Wireless - Auriculares de diadema para videojuegos, con tecnología de voz azul, color negro</p>
                <p>Precio: US$99.99</p>
                <p class="cantidad">Cant: 1 </p>
            </div>
        </div>
        <div class="itemCarrito">
            <div class="productoInfo">
                <img src="assets/img/promocionMueble.png" alt="imgProducto">
                <p>HyperX Cloud II Core Wireless - Auriculares de diadema para videojuegos, con tecnología de voz azul, color negro</p>
                <p>Precio: US$99.99</p>
                <p class="cantidad">Cant: 1 </p>
            </div>
        </div>
    </div>
</body>
</html>