<?php
require_once 'connection.php';

// Seleccionar mensajes que necesitan actualización
$sql = "SELECT * FROM EstadoMensajes";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $id_mensaje = $row['id_mensaje'];

    // Actualizar el estado del mensaje en MensajesForo
    $updateSql = "UPDATE MensajesForo SET estado = TRUE WHERE id = $id_mensaje";
    mysqli_query($conn, $updateSql);
}

// Vaciar la tabla temporal después de procesar
mysqli_query($conn, "TRUNCATE TABLE EstadoMensajes");

?>
