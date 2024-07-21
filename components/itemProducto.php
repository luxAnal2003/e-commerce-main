<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <div class="itemProducto" onclick="redirigirProducto(event)">
            <form method="POST" action="index.php" class="productoForm">
                <input type="hidden" name="verProducto" value="1">
                <div class="verProducto">
                    <img src="assets/img/promocionMueble.png" alt="Nuevo Producto">
                    <p>Nuevo Producto</p>
                    <p>$10</p>
                </div>
            </form>
            <button onclick="event.stopPropagation();">Añadir al carrito</button>
        </div>
    </body>
</html>