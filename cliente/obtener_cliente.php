<?php
$servername = "sql208.infinityfree.com";
$username = "if0_37068684";
$password = "QDDMXbjIIptT3u";
$dbname = "if0_37068684_facturacion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$idCliente = intval($_GET['id']);

$sql = "SELECT Cedula, Nombre, Direccion, Telefono, Email FROM Clientes WHERE ID_Cliente = $idCliente";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(array('error' => 'Cliente no encontrado'));
}

$conn->close();
?>
