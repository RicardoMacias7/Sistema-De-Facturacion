<?php
// Conexión a la base de datos

// Configuración de la conexión a la base de datos
$servername = "sql208.infinityfree.com";
$username = "if0_37068684";
$password = "QDDMXbjIIptT3u";
$dbname = "if0_37068684_facturacion";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}




// Verificar si se recibió el ID de la factura
if (!isset($_GET['idFactura'])) {
    echo json_encode(array('status' => 'error', 'message' => 'ID de factura no proporcionado.'));
    exit; // Terminar la ejecución del script si no hay ID de factura
}

// Obtener el ID de la factura enviado por el cliente
$idFactura = $_GET['idFactura'];

// Preparar la consulta SQL para obtener los datos de la factura
$sql = "SELECT * FROM Facturas F
        INNER JOIN Clientes C ON F.ID_Cliente = C.ID_Cliente
        LEFT JOIN Detalles_Factura DF ON F.ID_Factura = DF.ID_Factura
        WHERE F.ID_Factura = :idFactura";

// Preparar la declaración
$stmt = $pdo->prepare($sql);

// Bind de parámetros
$stmt->bindParam(':idFactura', $idFactura, PDO::PARAM_INT);

// Ejecutar la consulta
if ($stmt->execute()) {
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($factura) {
        // Obtener todos los productos de la factura
        $sqlProductos = "SELECT P.ID_Producto, P.Nombre, DF.Cantidad 
                         FROM Productos P
                         INNER JOIN Detalles_Factura DF ON P.ID_Producto = DF.ID_Producto
                         WHERE DF.ID_Factura = :idFactura";

        $stmtProductos = $pdo->prepare($sqlProductos);
        $stmtProductos->bindParam(':idFactura', $idFactura, PDO::PARAM_INT);
        $stmtProductos->execute();
        $productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

        // Agregar los productos a los datos de la factura
        $factura['Productos'] = $productos;

        // Devolver los datos de la factura en formato JSON
        echo json_encode($factura);
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No se encontró la factura con el ID especificado.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Error al obtener la factura.'));
}
?>
