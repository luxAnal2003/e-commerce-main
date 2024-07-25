<?php
require_once 'connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM Productos WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();

        if ($producto) {
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Editar Producto</title>
                <link rel="stylesheet" href="css/navbar y footer.css">
                <link rel="stylesheet" href="../css/style.css">
            </head>
            <style>
                .contenedor {
                    max-width: 800px;
                    margin: 50px auto;
                    padding: 20px;
                    background-color: #fff;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                h2 {
                    text-align: center;
                    color: #333;
                }
                form {
                    display: flex;
                    flex-direction: column;
                }
                label {
                    margin-top: 10px;
                    font-weight: bold;
                }
                input[type="text"], input[type="number"], textarea, input[type="file"] {
                    padding: 10px;
                    margin-top: 5px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                }
                img {
                    margin-top: 10px;
                    max-width: 100%;
                    height: auto;
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
                <div class="contenedor">
                    <h2>Editar Producto</h2>
                    <form action="actualizar_producto.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
                        
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                        
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                        
                        <label for="precio">Precio:</label>
                        <input type="number" id="precio" name="precio" value="<?= htmlspecialchars($producto['precio']) ?>" step="0.01" required>
                        
                        <label for="stock">Stock:</label>
                        <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($producto['stock']) ?>" required>
                        
                        <label for="imagen_url">Imagen actual:</label>
                        <img src="../assets/uploads/<?= htmlspecialchars($producto['imagen_url']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">

                        <label for="nueva_imagen">Nueva Imagen:</label>
                        <input type="file" id="nueva_imagen" name="nueva_imagen">

                        <button type="submit">Actualizar</button>
                    </form>
                </div>
                <?php include '../templates/footer.html'; ?>
            </body>
            </html>
            <?php
        } else {
            echo "Producto no encontrado.";
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }

    $conn->close();
} else {
    echo "ID del producto no proporcionado.";
}
?>
