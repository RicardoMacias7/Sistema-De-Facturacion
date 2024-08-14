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

// Recibir ID del cliente a obtener
$idCliente = $_GET['id'];

// Preparar y ejecutar consulta SQL para obtener datos del cliente por su ID
$sql = "SELECT Cedula, Nombre, Direccion, Telefono, Email FROM Clientes WHERE ID_Cliente='$idCliente'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo "Cliente no encontrado";
}

$conn->close();
?>
