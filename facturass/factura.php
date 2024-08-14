<?php
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

$sql = "SELECT ID_Producto, Nombre, Precio FROM Productos";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pantalla de Facturación</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlazar a un archivo CSS externo -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 20px;
            padding: 20px;
        }

        h1,
        h2 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select,
        input[type="text"],
        input[type="number"],
        input[type="email"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 0.5rem 1rem;
            width: 200px;
            margin: 0 auto;
            display: block;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        #totalFactura {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 5px;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
</head>

<body>


    <h1>Crear Nueva Factura</h1>
    <form id="facturaForm">
        <label for="cedulaCliente">Cédula:</label>
        <input type="text" id="cedulaCliente" name="cedulaCliente" required>

        <label for="nombreCliente">Nombre:</label>
        <input type="text" id="nombreCliente" name="nombreCliente" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="fecha">Fecha de Factura:</label>
        <input type="date" id="fecha" name="fecha" required>

        <label for="formaPago">Forma de Pago:</label>
        <select id="formaPago" name="formaPago" required>
            <option value="efectivo">Efectivo</option>
            <option value="tarjeta">Tarjeta de Crédito</option>
        </select>

        <label for="producto">Producto:</label>
        <select id="producto" name="producto" required>
            <?php
            $sql = "SELECT ID_Producto, Nombre, Precio FROM Productos";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["ID_Producto"] . "' data-precio='" . $row["Precio"] . "'>" . $row["Nombre"] . " - $" . $row["Precio"] . "</option>";
                }
            } else {
                echo "<option value=''>No hay productos disponibles</option>";
            }
            ?>
        </select>
        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" min="1" value="1" onchange="actualizarPrecio()">


        <!-- <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" min="1" value="1" required> -->

        <button type="button" onclick="agregarProducto()">Agregar Producto</button>
    </form>

    <h2>Detalles de Factura</h2>
    <table id="detalleFactura">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- Detalles de productos agregados -->
        </tbody>
    </table>

    <h3>Total Factura: $<span id="totalFactura">0.00</span></h3>

    <button onclick="mostrarModalFactura()">Crear Factura</button>

    <div id="facturaGenerada"></div>

    <!-- Modal de la Factura -->
    <div id="modalFactura" class="modal">
        <div class="modal-content">
            <span class="close" onclick="ocultarModalFactura()">&times;</span>
            <div id="modalContent">
                <!-- Contenido de la factura generada se insertará aquí -->
            </div>
        </div>
    </div>

    <!-- Script para manejar el modal -->
    <script>
        let totalFactura = 0;

        function actualizarPrecio() {
            const productoSelect = document.getElementById("producto");
            const precio = parseFloat(productoSelect.options[productoSelect.selectedIndex].dataset.precio);
            const cantidad = parseInt(document.getElementById("cantidad").value);
            const total = precio * cantidad;

            // Actualizar el campo de precio y total en la tabla de detalles de factura
            document.getElementById("precioProducto").innerText = precio.toFixed(2);
            document.getElementById("totalProducto").innerText = total.toFixed(2);
        }

        function agregarProducto() {
            const productoSelect = document.getElementById("producto");
            const cantidadInput = document.getElementById("cantidad");

            const producto = productoSelect.options[productoSelect.selectedIndex].text;
            const cantidad = parseInt(cantidadInput.value);
            const precio = parseFloat(productoSelect.options[productoSelect.selectedIndex].dataset.precio);
            const total = cantidad * precio;

            const tabla = document.getElementById("detalleFactura").getElementsByTagName('tbody')[0];
            const nuevaFila = tabla.insertRow();

            nuevaFila.insertCell(0).innerText = producto;
            nuevaFila.insertCell(1).innerText = cantidad;
            nuevaFila.insertCell(2).innerText = precio.toFixed(2);
            nuevaFila.insertCell(3).innerText = total.toFixed(2);

            totalFactura += total;
            document.getElementById("totalFactura").innerText = totalFactura.toFixed(2);
        }


        function mostrarModalFactura() {
            const cedulaCliente = document.getElementById('cedulaCliente').value;
            const nombreCliente = document.getElementById('nombreCliente').value;
            const telefono = document.getElementById('telefono').value;
            const direccion = document.getElementById('direccion').value;
            const email = document.getElementById('email').value;
            const fecha = document.getElementById('fecha').value;
            const formaPago = document.getElementById('formaPago').value;
            const detalleFactura = document.getElementById('detalleFactura').getElementsByTagName('tbody')[0];
            const productos = detalleFactura.getElementsByTagName('tr');

            let facturaHTML = `
                <h2>Factura Generada</h2>
                <table>
                    <tr><td><strong>Cédula:</strong></td><td>${cedulaCliente}</td></tr>
                    <tr><td><strong>Nombre:</strong></td><td>${nombreCliente}</td></tr>
                    <tr><td><strong>Teléfono:</strong></td><td>${telefono}</td></tr>
                    <tr><td><strong>Dirección:</strong></td><td>${direccion}</td></tr>
                    <tr><td><strong>Email:</strong></td><td>${email}</td></tr>
                    <tr><td><strong>Fecha de Factura:</strong></td><td>${fecha}</td></tr>
                    <tr><td><strong>Forma de Pago:</strong></td><td>${formaPago}</td></tr>
                </table>
                <h3>Detalles de Productos</h3>
                <table border="1">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>`;

            for (let i = 0; i < productos.length; i++) {
                const celdas = productos[i].getElementsByTagName('td');
                facturaHTML += `
                    <tr>
                        <td>${celdas[0].innerText}</td>
                        <td>${celdas[1].innerText}</td>
                        <td>${celdas[2].innerText}</td>
                        <td>${celdas[3].innerText}</td>
                    </tr>`;
            }

            facturaHTML += `
                    <tr>
                        <td colspan="3" style="text-align:right"><strong>Total Factura:</strong></td>
                        <td>$${totalFactura.toFixed(2)}</td>
                    </tr>
                </table>
                <button onclick="descargarFactura()">Descargar Factura</button>
                 <button onclick="generarFacturaPDF(facturaEjemplo)">Descargar</button>`;

                
            document.getElementById("modalContent").innerHTML = facturaHTML;
            document.getElementById("modalFactura").style.display = "block";
        }

        function ocultarModalFactura() {
            document.getElementById("modalFactura").style.display = "none";
        }

        function descargarFactura() {
            const cedulaCliente = document.getElementById('cedulaCliente').value;
            const nombreCliente = document.getElementById('nombreCliente').value;
            const facturaHTML = document.getElementById("modalContent").innerHTML;
            const blob = new Blob([facturaHTML], { type: "text/html;charset=utf-8" });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `Factura_${nombreCliente}_${cedulaCliente}.html`;
            link.click();
        }

  // Ejemplo de uso
var facturaEjemplo = {
    cedula: "123456789",
    cliente: "Cliente de prueba",
    correo: "cliente@example.com",
    telefono: "123456789",
    direccion: "Dirección de prueba",
    fecha: "2024-07-14",
    formaPago: "Tarjeta de crédito",
    numeroFactura: "001",
    productos: [
        { nombre: "Producto 1", cantidad: 2, precioUnitario: 10 },
        { nombre: "Producto 2", cantidad: 1, precioUnitario: 15 }
    ]
};

// Generar el PDF de la factura de ejemplo
generarFacturaPDF(facturaEjemplo);
    </script>
</body>

</html>