<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
    // Inicializar la URL de la imagen con la actual
    $imagen_url = $_POST['imagen_url'] ?? ''; // Usa la imagen actual si no se carga una nueva

    // Verificar si se subió una nueva imagen
    if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../assets/uploads/";
        $imageFileType = strtolower(pathinfo($_FILES['nueva_imagen']['name'], PATHINFO_EXTENSION));
        $imagen_url = "imagen_" . $id . '.' . $imageFileType;
        $target_file = $target_dir . $imagen_url;

        if (move_uploaded_file($_FILES['nueva_imagen']['tmp_name'], $target_file)) {
            // Imagen cargada correctamente
            $sql = "UPDATE Productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen_url = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $stock, $imagen_url, $id);
        } else {
            echo "Hubo un error subiendo el archivo.";
            exit;
        }
    } else {
        // No se subió una nueva imagen, solo actualiza los demás campos
        $sql = "UPDATE Productos SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $stock, $id);
    }

    if ($stmt) {
        if ($stmt->execute()) {
            echo "Producto actualizado correctamente.";
        } else {
            echo "Error al actualizar producto: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta de actualización: " . $conn->error;
    }

    $conn->close();
}
?>
