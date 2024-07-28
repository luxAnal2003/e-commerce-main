<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'database/connection.php';

if (!isset($_SESSION['id_sesion_no_registrado'])) {
    $_SESSION['id_sesion_no_registrado'] = uniqid();
}
// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['id']);
$id_cliente = $isLoggedIn ? intval($_SESSION['id']) : null;
$id_cliente_no_registrado = 1; // ID para usuarios no registrados

// Consulta para obtener los productos en el carrito del cliente específico o no registrado
if ($isLoggedIn) {
    $query = "SELECT cc.*, p.nombre, p.imagen_url, p.precio 
              FROM CarritoCompra cc 
              JOIN Productos p ON cc.id_producto = p.id 
              WHERE cc.id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_cliente);

    // Verificar registros en cuenta bancaria
    $query_datos_bancarios = "SELECT * FROM DatosBancarios WHERE id_cliente = ?";
    $stmt_datos_bancarios = $conn->prepare($query_datos_bancarios);
    $stmt_datos_bancarios->bind_param("i", $id_cliente);
} else {
    $idSesion = $_SESSION['id_sesion'];

    // Consulta para obtener los productos en el carrito del cliente no registrado
    $query = "SELECT cc.*, p.nombre, p.imagen_url, p.precio 
              FROM CarritoCompra cc 
              JOIN Productos p ON cc.id_producto = p.id 
              WHERE cc.id_sesion =?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idSesion);

    // Verificar registros en cuenta bancaria
    $query_datos_bancarios = "SELECT * FROM DatosBancarios WHERE id_cliente_no_registrado = ?";
    $stmt_datos_bancarios = $conn->prepare($query_datos_bancarios);
    $stmt_datos_bancarios->bind_param("i", $id_cliente_no_registrado);
}

$stmt->execute();
$result = $stmt->get_result();

$stmt_datos_bancarios->execute();
$result_datos_bancarios = $stmt_datos_bancarios->get_result();

$carritoItems = [];
while ($row = $result->fetch_assoc()) {
    $row['total'] = $row['cantidad'] * $row['precio']; // Calcular el total del producto
    $carritoItems[] = $row;
}

$stmt->close();
$stmt_datos_bancarios->close();
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
                                <a href="database/actualizar_carrito.php?id=<?= htmlspecialchars($item['id_producto']) ?>&accion=agregar" class="agregar">Agregar</a>
                                <a href="database/actualizar_carrito.php?id=<?= htmlspecialchars($item['id_producto']) ?>&accion=eliminar" class="eliminar">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="subtotalCarrito">
                <form method="POST" action="database/procesar_compra.php" class="compra">
                    <?php foreach ($carritoItems as $item) { ?>
                        <input type="hidden" name="productos[<?= htmlspecialchars($item['id_producto']) ?>][id]" value="<?= htmlspecialchars($item['id_producto']) ?>">
                        <input type="hidden" name="productos[<?= htmlspecialchars($item['id_producto']) ?>][nombre]" value="<?= htmlspecialchars($item['nombre']) ?>">
                        <input type="hidden" name="productos[<?= htmlspecialchars($item['id_producto']) ?>][precio]" value="<?= htmlspecialchars($item['precio']) ?>">
                        <input type="hidden" name="productos[<?= htmlspecialchars($item['id_producto']) ?>][cantidad]" value="<?= htmlspecialchars($item['cantidad']) ?>">
                    <?php } ?>
                    <p>Subtotal (<?= count($carritoItems) ?> producto/s): 
                    <strong>US$<?= array_sum(array_column($carritoItems, 'total')) ?></strong></p>
                    <?php if ($result_datos_bancarios->num_rows > 0) { ?>
                    <button type="submit">Proceder al pago</button>
                    <?php } else { ?>
                        <button type="button" disabled style="background-color: #ccc;">Proceder al pago</button>
                        <p>Debes agregar un método de pago antes de proceder, <a href="forms/PagoClienteNoRegistrado.html">Añadir datos bancarios</a>.</p>
                    <?php } ?>
                </form>
            </div>
        <?php } else { ?>
            <p>El carrito está vacío.</p>
        <?php } ?>
    </section>
</body>
</html>