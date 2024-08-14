<?php
// Configuración de conexión a la base de datos
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "factura";
$servername = "sql208.infinityfree.com";
$username = "if0_37068684";
$password = "QDDMXbjIIptT3u";
$dbname = "if0_37068684_facturacion";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la cédula del cliente desde la solicitud POST
$cedula = isset($_POST['cedula']) ? $_POST['cedula'] : '';

// Consulta SQL para buscar el cliente por cédula
$sql = "SELECT * FROM clientes WHERE Cedula = '$cedula'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si se encontró el cliente, devolver los datos
    $cliente = $result->fetch_assoc();
    echo json_encode($cliente);
} else {
    // Si no se encontró el cliente, devolver un mensaje de error
    http_response_code(404);
    echo json_encode(array('error' => 'Cliente no encontrado'));
}

// Cerrar conexión
$conn->close();
?>
