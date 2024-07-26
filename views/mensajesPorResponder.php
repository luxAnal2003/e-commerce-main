<?php
session_start();
require '../database/connection.php';

$isLoggedIn = isset($_SESSION['id']);
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Verificar si el usuario tiene permiso para ver esta página
if (!$isLoggedIn || $userRole !== 'encargado') {
    // Redirigir a una página de error o al login
    header("Location: ../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes No Respondidos</title>
    <link rel="stylesheet" href="css/navbar y footer.css">
    <style>
        .contenedorResena, .resena, .replica {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f8f8;
        }

        .contenedorResena h2 {
            text-align: center;
        }

        .contenedorResena p{
            margin: 20px;
        }

        .resena, .replica {
            background-color: #fff;
        }

        .resena p, .replica p {
            margin: 5px 0;
        }

        .replica {
            border-color: #ccc;
            margin-left: 40px; /* Espaciado para réplicas */
        }
        
        .contenedorReplica {
            display: flex;
            flex-direction: column;
        }

        .contenedorReplica textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        hr {
            border: 0;
            height: 1px;
            background: #ddd;
        }

        .respuestaForm {
            margin: 20px 0;
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
    <main>
        <div class="contenedor">
            <section class="contenedorResena">
                <?php
                include '../navbars/EncargadoInventarios.html';
                    // Consulta para obtener mensajes principales no respondidos
                    $query_hilos = "SELECT m.id AS id_hilo, m.mensaje, p.nombre AS producto, p.id AS id_producto, u.nombre AS usuario_nombre, m.fecha
                                    FROM MensajesForo m 
                                    JOIN Productos p ON m.id_producto = p.id 
                                    JOIN ClienteRegistrado u ON m.id_usuario = u.id
                                    WHERE m.id_respuesta_a IS NULL AND m.estado = FALSE";

                    $result_hilos = $conn->query($query_hilos);

                    if ($result_hilos->num_rows > 0) {
                        while ($row_hilo = $result_hilos->fetch_assoc()) {
                            // Mostrar mensaje principal
                            ?>
                            <div class='resena'>
                                <p><strong>Producto:</strong> <?php echo htmlspecialchars($row_hilo["producto"]); ?></p>
                                <p>Por: <?php echo htmlspecialchars($row_hilo["usuario_nombre"]); ?> el <?php echo htmlspecialchars($row_hilo["fecha"]); ?></p>
                                <hr>
                                <p><strong>Mensaje:</strong> <?php echo htmlspecialchars($row_hilo["mensaje"]); ?></p>
                            </div>
                            <?php if ($isLoggedIn) { ?>
                                <div class="respuestaForm">
                                    <form action="../database/insertar_comentario_gestorIn.php" method="POST">
                                        <input type="hidden" name="verProducto" value="<?php echo htmlspecialchars($row_hilo['id_producto']); ?>">
                                        <input type="hidden" name="id_respuesta_a" value="<?php echo htmlspecialchars($row_hilo['id_hilo']); ?>">
                                        <div class="contenedorReplica">
                                            <textarea name="mensaje" placeholder="Escribe tu respuesta aquí..." required></textarea>
                                            <button type="submit">Responder</button>
                                        </div>
                                    </form>
                                </div>
                            <?php } ?>
                            <?php
                        }
                    } else {
                        echo "<p>No hay mensajes no respondidos.</p>";
                    }

                    $conn->close();
                ?>
            </section>
        </div>
    </main>
</body>
</html>
