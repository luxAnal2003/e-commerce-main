<?php
    require_once 'connection.php';

    $id = $_POST['id'];

    $sql = "delete from products WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Producto eliminado con éxito";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
?>
