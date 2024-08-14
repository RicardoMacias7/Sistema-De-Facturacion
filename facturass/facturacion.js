let totalFactura = 0;

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
    // Recolectar datos del formulario y detalles de la factura
    const cedulaCliente = document.getElementById('cedulaCliente').value;
    const nombreCliente = document.getElementById('nombreCliente').value;
    const telefono = document.getElementById('telefono').value;
    const direccion = document.getElementById('direccion').value;
    const email = document.getElementById('email').value;
    const fecha = document.getElementById('fecha').value;

    const formaPago = document.getElementById('formaPago').value;

    const detalleFactura = document.getElementById('detalleFactura').getElementsByTagName('tbody')[0];
    const productos = detalleFactura.getElementsByTagName('tr');

    // Crear la estructura de la factura en HTML
    let facturaHTML = `
        <h2>Factura Generada</h2>
        <table>
        <tr>
            <td><strong>Cedula:</strong></td>
            <td>${cedulaCliente}</td>
        </tr>
        <tr>
            <td><strong>Nombre:</strong></td>
            <td>${nombreCliente}</td>
        </tr>
        <tr>
            <td><strong>Teléfono:</strong></td>
            <td>${telefono}</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong></td>
            <td>${direccion}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td>${email}</td>
        </tr>
        <tr>
            <td><strong>Fecha de Factura:</strong></td>
            <td>${fecha}</td>
        </tr>

                     <tr>
                    <td><strong>Forma de Pago:</strong></td>
                    <td>${formaPago}</td>
                </tr>
    </table>
    <h3>Detalles de la Factura</h3>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
    `;

    // Iterar sobre los productos y agregarlos a la factura
    for (let i = 0; i < productos.length; i++) {
        const cells = productos[i].getElementsByTagName('td');
        const producto = cells[0].innerText;
        const cantidad = cells[1].innerText;
        const precio = cells[2].innerText;
        const total = cells[3].innerText;

        facturaHTML += `
            <tr>
                <td>${producto}</td>
                <td>${cantidad}</td>
                <td>${precio}</td>
                <td>${total}</td>
            </tr>
        `;
    }

    // Cerrar la tabla y mostrar el total de la factura
    facturaHTML += `
            </tbody>
        </table>
        <h3>Total Factura: $<span id="totalFactura">${totalFactura.toFixed(2)}</span></h3>
        <button onclick="descargarFactura()">Descargar Factura</button>
    `;

    // Mostrar la factura generada en un modal
    const modalFactura = document.getElementById('modalFactura');
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = facturaHTML;
    modalFactura.style.display = 'block';
}

function ocultarModalFactura() {
    // Ocultar el modal de la factura
    const modalFactura = document.getElementById('modalFactura');
    modalFactura.style.display = 'none';
}

function descargarFactura() {
    // Recolectar datos del modal para generar el archivo HTML
    const facturaHTML = document.getElementById('modalContent').innerHTML;

    // Crear un archivo HTML descargable
    const blob = new Blob([facturaHTML], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'factura.html';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
