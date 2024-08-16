<?php
$servername = "sql208.infinityfree.com";
$username = "if0_37068684";
$password = "QDDMXbjIIptT3u";
$dbname = "if0_37068684_facturacion";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a utf8
$conn->set_charset("utf8");

$cedula = isset($_POST['cedula']) ? $_POST['cedula'] : '';

if (empty($cedula)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Cédula no proporcionada'));
    $conn->close();
    exit;
}

$sql = "SELECT * FROM Clientes WHERE Cedula = '$cedula'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si se encontró el cliente, devolver los datos
    $cliente = $result->fetch_assoc();
    echo json_encode($cliente);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Cliente no encontrado'));
}

$conn->close();
?>