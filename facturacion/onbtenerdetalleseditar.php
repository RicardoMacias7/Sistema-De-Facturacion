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
// Obtener el ID de factura enviado por el método GET
$ID_Factura = isset($_GET['id_factura']) ? intval($_GET['id_factura']) : 0;

if ($ID_Factura === 0) {
    die(json_encode(array('error' => 'ID de factura inválido')));
}

// // Consulta para obtener detalles de la factura, cliente, y productos
// $sql = "SELECT c.Nombre AS Cliente, p.Nombre, df.Cantidad, p.Precio, c.Cedula, c.Email, c.Telefono,c.Direccion, f.MetodoPago,f.NumeroFactura
//         FROM Detalles_Factura df
//         JOIN Productos p ON df.ID_Producto = p.ID_Producto
//         JOIN Facturas f ON df.ID_Factura = f.ID_Factura
//         JOIN Clientes c ON f.ID_Cliente = c.ID_Cliente
//         WHERE df.ID_Factura = $ID_Factura";
// Consulta para obtener detalles de la factura, cliente, y productos
$sql = "SELECT c.Nombre AS Cliente, p.Nombre, df.Cantidad, p.Precio, c.Cedula, c.Email, c.Telefono, c.Direccion, f.MetodoPago
        FROM Detalles_Factura df
        JOIN Productos p ON df.ID_Producto = p.ID_Producto
        JOIN Facturas f ON df.ID_Factura = f.ID_Factura
        JOIN Clientes c ON f.ID_Cliente = c.ID_Cliente
        WHERE df.ID_Factura = $ID_Factura";
$result = $conn->query($sql);

if (!$result) {
    die(json_encode(array('error' => 'Error en la consulta SQL: ' . $conn->error)));
}

if ($result->num_rows > 0) {
    // Procesar resultados y devolver JSON
    $productos = array();
    $cliente = '';
    $cedula = '';
    $email = '';
    $telefono = '';
    $direccion = '';
    $forma = '';
    // $facturaN = '';


    while ($row = $result->fetch_assoc()) {
        // Procesar cada fila y asignar valores...

        if ($cliente === '') {
            $cliente = $row['Cliente'];
            $cedula = $row['Cedula'];
            $email = $row['Email'];
            $telefono = $row['Telefono'];
            $direccion = $row['Direccion'];
            $forma = $row['MetodoPago'];
            // $facturaN =$row['NumeroFactura'];

        }


        $producto = array(
            'Nombre' => $row['Nombre'],
            'Cantidad' => $row['Cantidad'],
            'Precio' => $row['Precio']
        );
        $productos[] = $producto;
    }

    // Devolver los detalles de la factura en formato JSON...
    echo json_encode(array('forma' => $forma, 'cliente' => $cliente, 'cedula' => $cedula, 'email' => $email, 'direccion' => $direccion, 'telefono' => $telefono, 'productos' => $productos));
} else {
    // Devolver un array vacío si no se encuentran productos...
    echo json_encode(array('error' => 'No se encontraron productos para la factura'));
}

// Cerrar conexión
$conn->close();

?>