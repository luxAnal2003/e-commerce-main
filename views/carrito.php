<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'database/connection.php';

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['id']);
$id_cliente = $isLoggedIn ? intval($_SESSION['id']) : null;
$id_cliente_no_registrado = 1; // ID para usuarios no registrados

// Consulta para obtener los productos en el carrito del cliente específico o no registrado
$query = "SELECT cc.*, p.nombre, p.imagen_url, p.precio 
          FROM CarritoCompra cc 
          JOIN Productos p ON cc.id_producto = p.id 
          WHERE cc.id_cliente = ? OR (cc.id_cliente_no_registrado = ? AND cc.id_cliente IS NULL)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_cliente, $id_cliente_no_registrado);
$stmt->execute();
$result = $stmt->get_result();

$carritoItems = [];
while ($row = $result->fetch_assoc()) {
    $row['total'] = $row['cantidad'] * $row['precio']; // Calcular el total del producto
    $carritoItems[] = $row;
}

$stmt->close();
$conn->close();
?>

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

        table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse; 
        }

        td, th {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #3d5a80;
            color: white;
        }

        td img {
            width: 50px;
            height: auto;
        }

        td a {
            text-decoration: none;
            margin: 10px;
        }

        .subtotalCarrito {
            text-align: right;
            padding: 10px 0;
        }

        .subtotalCarrito p {
            margin: 10px 0;
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
        <?php if (!empty($carritoItems)) { ?>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carritoItems as $item) { ?>
                        <tr>
                            <td><img src="assets/uploads/<?= htmlspecialchars($item['imagen_url']) ?>" alt="imgProducto"></td>
                            <td style="text-align: left;"><?= htmlspecialchars($item['nombre']) ?></td>
                            <td>US$<?= htmlspecialchars($item['precio']) ?></td>
                            <td><?= htmlspecialchars($item['cantidad']) ?></td>
                            <td>US$<?= htmlspecialchars($item['total']) ?></td>
                            <td>
                                <a href="database/actualizar_carrito.php?id=<?= htmlspecialchars($item['id']) ?>&accion=disminuir" class="disminuir">Disminuir</a>
                                <a href="database/actualizar_carrito.php?id=<?= htmlspecialchars($item['id']) ?>&accion=agregar" class="agregar">Agregar</a>
                                <a href="database/actualizar_carrito.php?id=<?= htmlspecialchars($item['id']) ?>&accion=eliminar" class="eliminar">Eliminar</a>
                            </td>
                    <?php } ?>
                </tbody>
            </table>
            <div class="subtotalCarrito">
                <p>Subtotal (<?= count($carritoItems) ?> producto/s): 
                <strong>US$<?= array_sum(array_column($carritoItems, 'total')) ?></strong></p>
                <button onclick="window.location.href='database/procesar_compra.php'">Proceder al pago</button>
            </div>
        <?php } else { ?>
            <p>El carrito está vacío.</p>
        <?php } ?>
    </section>
</body>
</html>