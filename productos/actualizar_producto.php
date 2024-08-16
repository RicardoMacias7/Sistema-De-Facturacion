<?php

$servername = "sql208.infinityfree.com";
$username = "if0_37068684";
$password = "QDDMXbjIIptT3u";
$dbname = "if0_37068684_facturacion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$idProducto = $_POST['idProducto'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];

$sql = "UPDATE productos SET  Nombre='$nombre', Precio='$precio' WHERE ID_Producto='$idProducto'";

if ($conn->query($sql) === TRUE) {
    $respuesta = array('mensaje' => 'Cliente actualizado exitosamente.');
    echo json_encode($respuesta);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

