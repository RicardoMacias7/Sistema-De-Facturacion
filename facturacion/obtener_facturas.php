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

$sql = "SELECT f.ID_Factura, f.Fecha, c.Nombre, f.Total 
        FROM Facturas f 
        JOIN Clientes c ON f.ID_Cliente = c.ID_Cliente
        WHERE f.Activo = TRUE"; // Solo obtener facturas activas
$result = $conn->query($sql);

$facturas = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $facturas[] = $row;
    }
}

// Mostrar los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($facturas);

$conn->close();
?>