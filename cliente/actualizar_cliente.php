<?php
// Conexión a la base de datos (reemplaza con tus datos de conexión)
$servername = "sql208.infinityfree.com";
$username = "if0_37068684";
$password = "QDDMXbjIIptT3u";
$dbname = "if0_37068684_facturacion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$idCliente = $_POST['idCliente'];
$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

// Preparar y ejecutar consulta SQL para actualizar cliente
$sql = "UPDATE Clientes SET Cedula='$cedula', Nombre='$nombre', Direccion='$direccion', Telefono='$telefono', Email='$email' WHERE ID_Cliente='$idCliente'";

if ($conn->query($sql) === TRUE) {
    $respuesta = array('mensaje' => 'Cliente actualizado exitosamente.');
    echo json_encode($respuesta);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
