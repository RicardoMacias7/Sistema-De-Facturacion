<?php
$servername = "sql208.infinityfree.com";
$username = "if0_37068684";
$password = "QDDMXbjIIptT3u";
$dbname = "if0_37068684_facturacion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];


$sql = "INSERT INTO Clientes (Cedula, Nombre, Direccion, Telefono, Email) VALUES ('$cedula', '$nombre', '$direccion', '$telefono', '$email')";

if ($conn->query($sql) === TRUE) {
    $respuesta = array('mensaje' => 'Cliente creado exitosamente.');
    echo json_encode($respuesta);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
