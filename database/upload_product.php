<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria'];
    $nueva_categoria = $_POST['nueva_categoria'];

    // Directorio donde se guardarán las imágenes
    $target_dir = "../assets/uploads/";
    
    // Obtener el próximo ID de la tabla Productos
    $result = $conn->query("SHOW TABLE STATUS LIKE 'Productos'");
    $row = $result->fetch_assoc();
    $next_id = $row['Auto_increment'];
    
    // Generar un nombre único para la imagen
    $imageFileType = strtolower(pathinfo($_FILES['imagen_url']['name'], PATHINFO_EXTENSION));
    $target_file = $target_dir . "imagen_" . $next_id . '.' . $imageFileType;

    // Mover el archivo subido a la carpeta de destino
    if (move_uploaded_file($_FILES['imagen_url']['tmp_name'], $target_file)) {
        // Guardar la ruta relativa en la base de datos
        $imagen_url = "imagen_" . $next_id . '.' . $imageFileType;

        // Si se proporciona una nueva categoría, usarla
        if (!empty($nueva_categoria)) {
            $stmt = $conn->prepare("INSERT INTO Categorias (nombre) VALUES (?)");
            $stmt->bind_param("s", $nueva_categoria);
            if ($stmt->execute()) {
                $categoria_id = $stmt->insert_id;
            } else {
                echo "Error al insertar nueva categoría: " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit;
            }
            $stmt->close();
        }

        $sql = "INSERT INTO Productos (nombre, descripcion, precio, stock, imagen_url) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $stock, $imagen_url);
            if ($stmt->execute()) {
                // Obtener el ID del producto insertado
                $producto_id = $stmt->insert_id;

                // Insertar la relación Producto-Categoría
                $sql_categoria = "INSERT INTO ProductoCategoria (id_producto, id_categoria) VALUES (?, ?)";
                if ($stmt_categoria = $conn->prepare($sql_categoria)) {
                    $stmt_categoria->bind_param("ii", $producto_id, $categoria_id);
                    if ($stmt_categoria->execute()) {
                        echo "Producto y categoría guardados exitosamente.";
                    } else {
                        echo "Error al insertar relación Producto-Categoría: " . $stmt_categoria->error;
                    }
                    $stmt_categoria->close();
                } else {
                    echo "Error al preparar la consulta de relación Producto-Categoría: " . $conn->error;
                }
            } else {
                echo "Error al insertar producto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error al preparar la consulta de producto: " . $conn->error;
        }
    } else {
        echo "Hubo un error subiendo el archivo.";
    }
}
$conn->close();
?>
