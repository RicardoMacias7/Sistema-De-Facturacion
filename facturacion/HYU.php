<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "factura";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID de factura enviado por el método GET
$ID_Factura = $_GET['id_factura'];

// Consulta para obtener detalles de la factura, cliente, y productos
$sql = "SELECT f.Fecha, c.Nombre AS Cliente, c.Cedula, p.Nombre, df.Cantidad, p.Precio
        FROM Detalles_Factura df
        JOIN Productos p ON df.ID_Producto = p.ID_Producto
        JOIN Facturas f ON df.ID_Factura = f.ID_Factura
        JOIN Clientes c ON f.ID_Cliente = c.ID_Cliente
        WHERE df.ID_Factura = $ID_Factura";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $productos = array();
    $fecha = '';
    $cliente = '';
    $cedula = '';
    while ($row = $result->fetch_assoc()) {
        if ($fecha === '') {
            $fecha = $row['Fecha'];
        }
        if ($cliente === '') {
            $cliente = $row['Cliente'];
            $cedula = $row['Cedula'];
        }
        $producto = array(
            'Nombre' => $row['Nombre'],
            'Cantidad' => $row['Cantidad'],
            'Precio' => $row['Precio']
        );
        $productos[] = $producto;
    }

    // Devolver los detalles de la factura en formato JSON
    echo json_encode(array('fecha' => $fecha, 'cliente' => $cliente, 'cedula' => $cedula, 'productos' => $productos));
} else {
    echo json_encode(array('productos' => array())); // Devolver un array vacío si no se encuentran productos
}

// Cerrar conexión
$conn->close();
?>
