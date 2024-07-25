<?php
require '../database/connection.php';
session_start();

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
    <title>Mensajes Respondidos</title>
    <link rel="stylesheet" href="css/navbar y footer.css">
    <style>
        .contenedorResena, .resena, .replica {
            margin: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f8f8;
        }

        .contenedorResena h2 {
            text-align: center;
        }

        .contenedorResena p {
            margin: 20px;
        }

        .resena, .replica {
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .resena p {
            margin: 5px 0;
        }

        .replica {
            margin-left: 40px; /* Espaciado para diferenciar */
            padding: 15px; /* Espaciado interno adicional */
        }

        .replica p {
            margin: 5px 0;
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
    </style>
</head>
<body>
    <main>
        <div class="contenedor">
            <section class="contenedorResena">
                <?php
                    include '../navbars/EncargadoInventarios.html';
                    // Consulta para obtener mensajes principales respondidos
                    $query_hilos = "SELECT m.id, m.mensaje, p.nombre AS producto, u.nombre AS usuario_nombre, m.fecha
                                    FROM MensajesForo m 
                                    JOIN Productos p ON m.id_producto = p.id 
                                    JOIN ClienteRegistrado u ON m.id_usuario = u.id
                                    WHERE m.id_respuesta_a IS NULL AND m.estado = TRUE";

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
                            <?php
                            // Consulta para obtener la réplica del gestor de inventario
                            $query_replicas = "SELECT r.mensaje AS replica, e.nombre AS gestor_nombre, r.fecha
                                            FROM MensajesForo r
                                            JOIN EncargadoInventarios e ON r.id_usuario = e.id
                                            WHERE r.id_respuesta_a = " . $row_hilo['id'];

                            $result_replicas = $conn->query($query_replicas);

                            if ($result_replicas->num_rows > 0) {
                                while ($row_replica = $result_replicas->fetch_assoc()) {
                                    ?>
                                    <div class="replica">
                                        <p><b>Re: <?php echo htmlspecialchars($row_hilo['usuario_nombre']); ?></b></p>
                                        <p>Por: <?php echo htmlspecialchars($row_replica['gestor_nombre']); ?> el <?php echo htmlspecialchars($row_replica['fecha']); ?></p>
                                        <hr>
                                        <p>Mensaje: <?php echo htmlspecialchars($row_replica['replica']); ?></p>
                                    </div>
                                    <?php
                                }
                            }
                        }
                    } else {
                        echo "<p>No hay mensajes respondidos.</p>";
                    }

                    $conn->close();
                ?>
            </section>
        </div>
    </main>
</body>
</html>
