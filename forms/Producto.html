<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main>
        <section id="registro-producto" class="form-section">
            <h1>Registro de datos Producto</h1>
            <form action="../database/upload_product.php" method="post" enctype="multipart/form-data">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" maxlength="100" required>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
                <label for="precio">Precio:</label>
                <input type="text" id="precio" name="precio" required>
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" required>
                <label for="imagen_url">Imagen URL:</label>
                <input type="file" id="imagen_url" name="imagen_url" required>
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria">
                    <!-- Las opciones se llenarán dinámicamente desde la base de datos -->
                </select>
                <label for="nueva_categoria">Agregar nueva categoría (opcional):</label>
                <input type="text" id="nueva_categoria" name="nueva_categoria">
                <button type="submit">Guardar</button>
            </form>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../database/get_categories.php')
                .then(response => response.json())
                .then(data => {
                    const categoriaSelect = document.getElementById('categoria');
                    data.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.id;
                        option.textContent = categoria.nombre;
                        categoriaSelect.appendChild(option);
                    });
                });
        });
    </script>
</body>
</html>
