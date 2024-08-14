<?php
// Configuración de conexión a la base de datos
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

// Consulta SQL para obtener todos los productos
$sql = "SELECT ID_Producto, Nombre, Precio FROM Productos";
$result = $conn->query($sql);

$productos = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $productos[] = array(
            'ID_Producto' => $row['ID_Producto'],
            'Nombre' => $row['Nombre'],
            'Precio' => $row['Precio']
        );
    }
} else {
    // Si no hay productos, devolver un array vacío
    $productos = array();
}

// Cerrar conexión
$conn->close();

// Devolver productos como JSON
header('Content-Type: application/json');
echo json_encode($productos);
?>
