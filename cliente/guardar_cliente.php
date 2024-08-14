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
$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

// Preparar y ejecutar consulta SQL para insertar nuevo cliente
$sql = "INSERT INTO Clientes (Cedula, Nombre, Direccion, Telefono, Email) VALUES ('$cedula', '$nombre', '$direccion', '$telefono', '$email')";

if ($conn->query($sql) === TRUE) {
    $respuesta = array('mensaje' => 'Cliente creado exitosamente.');
    echo json_encode($respuesta);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
