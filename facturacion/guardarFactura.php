<?php
// Conexión a la base de datos
$conexion = new mysqli('sql208.infinityfree.com', 'if0_37068684', 'QDDMXbjIIptT3u', 'if0_37068684_facturacion');



// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener datos de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$idCliente = $data['idCliente'];
$fecha = $data['fecha'];
$total = $data['total'];
$pago = $data['pago']; // Obtener el método de pago
$productos = $data['productos']; // Array de productos

// Función para obtener el siguiente número de factura secuencial
function obtenerSiguienteNumeroFactura($conexion) {
    $query = "SELECT MAX(NumeroFactura) AS max_numero FROM Facturas";
    $result = $conexion->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $max_numero = $row['max_numero'];

        if ($max_numero !== null) {
            $siguiente_numero = (int)$max_numero + 1;
        } else {
            $siguiente_numero = 100;
        }
    } else {
        $siguiente_numero = 100;
    }

    return $siguiente_numero;
}

// Obtener el siguiente número de factura
$numeroFactura = obtenerSiguienteNumeroFactura($conexion);

// Insertar la nueva factura en la tabla Facturas
$sql = $conexion->prepare("INSERT INTO Facturas (ID_Cliente, Fecha, Total, NumeroFactura, MetodoPago) VALUES (?, ?, ?, ?, ?)");
$sql->bind_param("isdis", $idCliente, $fecha, $total, $numeroFactura, $pago);

if ($sql->execute()) {
    $idFactura = $conexion->insert_id; // Obtener el ID de la factura insertada

    // Insertar cada producto en la tabla Detalles_Factura
    foreach ($productos as $producto) {
        $idProducto = $producto['idProducto'];
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];

        $sqlDetalle = $conexion->prepare("INSERT INTO Detalles_Factura (ID_Factura, ID_Producto, Cantidad, Precio) VALUES (?, ?, ?, ?)");
        $sqlDetalle->bind_param("iiid", $idFactura, $idProducto, $cantidad, $precio);
        $sqlDetalle->execute();
    }

    $respuesta = array('mensaje' => 'Factura guardada exitosamente.');
    echo json_encode($respuesta);
} else {
    echo "Error: " . $sql->error;
}

$sql->close();
$conexion->close();
?>
