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

$nombre = $_POST['nombre'];
$precio = $_POST['precio'];


$sql = "INSERT INTO productos (Nombre, Precio) VALUES ('$nombre','$precio')";

if ($conn->query($sql) === TRUE) {
    $respuesta = array('mensaje' => 'producto creado exitosamente.');
    echo json_encode($respuesta);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
