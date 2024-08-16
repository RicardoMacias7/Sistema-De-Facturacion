<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado y Gestión de Facturas</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Gestión de Facturación</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" id="clientes">
                    <a class="nav-link" href="../cliente/cliente.php">Listado de Clientes</a>
                </li>
                <li class="nav-item" id="product">
                    <a class="nav-link" href="../productos/producto.php">Listado de Productos</a>
                </li>
                <li class="nav-item" id="facturas">
                    <a class="nav-link" href="../facturacion/facturas.php">Listado de Facturas</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Listado de Facturas</h2>
        <!-- Botón para abrir modal de crear nueva factura -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearFactura">Crear Factura</button>
        <!-- Tabla para listar facturas -->
        <table class="table table-striped" id="tablaFacturas">
            <thead>
                <tr>
                    <!-- <th>ID_Factura</th> -->
                    <th>Numero Factura</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Conexión base de datos
                $servername = "sql208.infinityfree.com";
                $username = "if0_37068684";
                $password = "QDDMXbjIIptT3u";
                $dbname = "if0_37068684_facturacion";


                $conn = new mysqli($servername, $username, $password, $dbname);


                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }
                $conn->set_charset("utf8");

                $sql = "SELECT f.ID_Factura, f.Fecha, c.Nombre, f.Total,f.NumeroFactura
                FROM Facturas f 
                JOIN Clientes c ON f.ID_Cliente = c.ID_Cliente
                WHERE f.Activo = TRUE"; // Solo obtener facturas activas
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        // echo "<td>" . $row["ID_Factura"] . "</td>";
                        echo "<td>" . $row["NumeroFactura"] . "</td>";
                        echo "<td>" . $row["Fecha"] . "</td>";
                        echo "<td>" . $row["Nombre"] . "</td>";
                        echo "<td>" . $row["Total"] . "</td>";
                        echo '<td>
                        <button class="btn btn-success" onclick="verDetallesFactura(' . $row["ID_Factura"] . ')">Ver Detalles</button>
                      </td>';
                        echo '<td>
                      <button class="btn btn-info" onclick="editarFactura(' . $row["ID_Factura"] . ')">Editar</button>
                    </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron facturas</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para crear factura -->
    <div class="modal fade" id="modalCrearFactura" tabindex="-1" role="dialog" aria-labelledby="modalCrearFacturaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearFacturaLabel">Crear Factura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para crear  -->
                    <form id="formCrearFactura">
                        <div class="form-row">
                            <input type="hidden" id="idCliente" name="idCliente">
                            <div class="form-group col-md-6">
                                <label for="cedulaCliente">Cédula</label>
                                <input type="text" class="form-control" id="cedulaCliente" required maxlength="10">
                                <button type="button" class="btn btn-primary mt-2" onclick="buscarCliente()">Buscar
                                    Cliente</button>
                                <div id="resultadoBusqueda"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nombreCliente">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="nombreCliente" required readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="telefonoCliente">Teléfono</label>
                                <input type="text" class="form-control" id="telefonoCliente" required readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="direccionCliente">Dirección</label>
                                <input type="text" class="form-control" id="direccionCliente" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="emailCliente">Email</label>
                            <input type="email" class="form-control" id="emailCliente" required readonly>
                        </div>
                        <div class="col">
                            <label for="formaPago">Forma de Pago:</label>
                            <select id="formaPago" class="form-control" name="formaPago" required>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                            </select>
                        </div>
                        <hr>
                        <h5>Detalle de Productos</h5>
                        <div id="productos">
                            <div class="form-row align-items-center mb-2" id="producto1Row">
                                <div class="col">
                                    <label for="producto1">Producto</label>
                                    <select class="form-control producto-select" id="producto1" required>

                                    </select>
                                </div>
                                <div class="col">
                                    <label for="cantidad1">Cantidad</label>
                                    <input type="number" class="form-control" min="1" value="1" id="cantidad1" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mb-3" onclick="agregarProducto()">Agregar
                            Factura</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarFactura()">Guardar Factura</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDITAR MODAL -->
    <div class="modal fade" id="modalEditarFactura" tabindex="-1" role="dialog"
        aria-labelledby="modalEditarFacturaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarFacturaLabel">Editar Factura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para Editar  -->
                    <form id="formEditarFactura">
                        <div class="form-row">
                            <input type="hidden" id="idClienteEditar" name="idClienteEditar">
                            <div class="form-group col-md-6">
                                <label for="cedulaEditar">Cédula</label>
                                <input type="text" class="form-control" id="cedulaEditar" required maxlength="10">
                                <button type="button" class="btn btn-primary mt-2" onclick="buscarEditar()">Buscar
                                    Cliente Editar</button>
                                <div id="resultadoBusquedaS"></div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nombreEditar">Nombre del Cliente</label>
                                <input type="text" class="form-control" id="nombreEditar" required readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="telefonoEditar">Teléfono</label>
                                <input type="text" class="form-control" id="telefonoEditar" required readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="direccionEditar">Dirección</label>
                                <input type="text" class="form-control" id="direccionEditar" required readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="emailEditar">Email</label>
                            <input type="email" class="form-control" id="emailEditar" required readonly>
                        </div>
                        <div class="col">
                            <label for="formaPagoEditar">Forma de Pago:</label>
                            <select id="formaPagoEditar" class="form-control" name="formaPagoEditar" required>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                            </select>
                        </div>
                        <hr>
                        <h5>Detalle de Productos</h5>
                        <div id="productos">
                            <div class="form-row align-items-center mb-2" id="producto1Row">
                                <div class="col">
                                    <label for="producto1">Producto</label>
                                    <select class="form-control producto-select" id="producto1" required>

                                    </select>
                                </div>
                                <div class="col">
                                    <label for="cantidad1">Cantidad</label>
                                    <input type="number" class="form-control" min="1" value="1" id="cantidad1" required>
                                </div>
                                <!-- <div class="col">
                                    <label for="formaPago">Forma de Pago:</label>
                                    <select id="formaPago" class="form-control" name="formaPago" required>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Tarjeta de Crédito</option>
                                    </select>
                                </div> -->

                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mb-3" onclick="agregarProducto()">Agregar
                            Factura</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarFacturaEditada()">Editar
                        Factura</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles de factura.... -->
    <div class="modal fade" id="modalDetallesFactura" tabindex="-1" role="dialog"
        aria-labelledby="modalDetallesFacturaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesFacturaLabel">Detalles de Factura </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="infoCedula"><strong>Cedula:</strong></label>
                                <input id="infoCedula" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="infoCliente"><strong>Cliente:</strong></label>
                                <input type="text" class="form-control" id="infoCliente" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="infoEmail"><strong>Correo:</strong></label>
                                <input type="email" class="form-control" id="infoEmail" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="infoTelefono"><strong>Teléfono:</strong></label>
                                <input type="tel" class="form-control" id="infoTelefono" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="infoDirecion"><strong>Direccion:</strong></label>
                                <input type="text" class="form-control" id="infoDirecion" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="infoFecha"><strong>Fecha:</strong></label>
                                <input type="text" class="form-control" id="infoFecha" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="infoPago"><strong>Forma Pago:</strong></label>
                                <input id="infoPago" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="infoFactura"><strong>Numero Factura:</strong></label>
                                <input id="infoFactura" class="form-control" readonly>
                            </div>
                        </div>
                    </form>
                    <!-- Tabla de detalles de productos.... -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="detalleProductos">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="descargarFactura()">Descargar
                        Factura</button>

                </div>
            </div>
        </div>
    </div>


    <script>

        let productoCount = [];
        // Función para buscar un cliente por cédula....
        function buscarCliente() {
            const cedula = document.getElementById('cedulaCliente').value;

            const formData = new URLSearchParams();
            formData.append('cedula', cedula);

            fetch('buscar_cliente.php', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        alert('invalido');
                        throw new Error('invalido');
                    }
                    return response.json();
                })
                .then(cliente => {
                    // Actualizar los campos del formulario con los datos del cliente encontrado
                    document.getElementById('idCliente').value = cliente.ID_Cliente;
                    document.getElementById('nombreCliente').value = cliente.Nombre;
                    document.getElementById('telefonoCliente').value = cliente.Telefono;
                    document.getElementById('direccionCliente').value = cliente.Direccion;
                    document.getElementById('emailCliente').value = cliente.Email;
                })
                .catch(error => {
                    console.error('Error al buscar cliente:', error);
                    // Mostrar mensaje de error al usuario (cliente no encontrado)....
                    alert('Cliente no encontrado, por favor ingrese la cedula correcta o verifique si el cliente existe');
                });
        }
        // Función para cargar los datos de la factura en el modal de edición...
        function editarFactura(ID_Factura) {
            fetch(`onbtenerdetalleseditar.php?id_factura=${ID_Factura}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener detalles de la factura');
                    }
                    return response.json();
                })
                .then(data => {
                    // Llenar los campos del formulario de edición con los datos obtenidos....
                    document.getElementById('cedulaEditar').value = data.cedula;
                    document.getElementById('formaPagoEditar').value = data.forma;
                    document.getElementById('nombreEditar').value = data.cliente;
                    document.getElementById('telefonoEditar').value = data.telefono;
                    document.getElementById('direccionEditar').value = data.direccion;
                    document.getElementById('emailEditar').value = data.email;

                    // Limpiar y llenar la tabla de productos en el modal de edición....
                    document.getElementById('productos').innerHTML = ''; // Limpiar productos existentes
                    data.productos.forEach(producto => {
                        const nuevoProductoRow = document.createElement('div');
                        nuevoProductoRow.classList.add('form-row', 'align-items-center', 'mb-2');
                        nuevoProductoRow.innerHTML = `
                    <div class="col">
                        <label for="producto${producto.ID_Producto}">Producto</label>
                        <select class="form-control producto-select" id="producto${producto.ID_Producto}" required>
                            <option value="${producto.ID_Producto}" selected>${producto.Nombre}</option>
                            <!-- Aquí deberías cargar las opciones de productos disponibles -->
                        </select>
                    </div>
                    <div class="col">
                        <label for="cantidad${producto.ID_Producto}">Cantidad</label>
                        <input type="number" class="form-control" min="1" value="${producto.Cantidad}" id="cantidad${producto.ID_Producto}" required>
                    </div>
                `;
                        document.getElementById('productos').appendChild(nuevoProductoRow);
                    });

                    // Mostrar el modal de editar factura....
                    $('#modalEditarFactura').modal('show');

                })
                .catch(error => {
                    console.error('Error al obtener detalles de la factura:', error);
                    alert('Error al obtener detalles de la factura');
                });
        }
        // Función para cargar productos....
        function cargarProductos() {
            fetch('cargar_productos.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar productos');
                    }
                    return response.json();
                })
                .then(data => {
                    document.querySelectorAll('.producto-select').forEach(select => {
                        select.innerHTML = '';
                        data.forEach(producto => {
                            const option = document.createElement('option');
                            option.value = producto.ID_Producto;
                            option.textContent = `${producto.Nombre} - $${producto.Precio}`;
                            select.appendChild(option);
                        });
                    });
                })
                .catch(error => {
                    console.error('Error al cargar productos:', error);
                });
        }

        // Función para agregar un nuevo campo de producto al formulario...
        function agregarProducto() {
            productoCount++; // Incrementar el contador de productos...

            const nuevoProductoRow = document.createElement('div');
            nuevoProductoRow.classList.add('form-row', 'align-items-center', 'mb-2');
            nuevoProductoRow.id = `producto${productoCount}Row`;

            nuevoProductoRow.innerHTML = `
            <div class="col">
                <label for="producto${productoCount}">Producto</label>
                <select class="form-control producto-select" id="producto${productoCount}" required>
                    
                </select>
            </div>
            <div class="col">
                <label for="cantidad${productoCount}">Cantidad</label>
                    <input type="number" class="form-control" min="1" value="1" id="cantidad${productoCount}" required>
            </div>
     
        `;
            document.getElementById('productos').appendChild(nuevoProductoRow);
            cargarProductos(); // Cargar opciones de productos en el nuevo select...
        }

        // Cargar productos al cargar la página...
        cargarProductos();



        //SIMULADPR ACTUALIZAR...
        function guardarFacturaEditada() {
            alert('simulador! La factura se actualizo');
            return;
        }

        function guardarFactura() {
            const idCliente = document.getElementById('idCliente').value;
            const totalFactura = calcularTotalFactura(); // Calcula el total de la factura...
            const fechaActual = obtenerFechaActual(); // Obtén la fecha actual...
            const formaPago = document.getElementById('formaPago').value;

            if (!idCliente) {
                alert('Por favor, busque un cliente válido.');
                return;
            }

            const productos = [];
            const productosRows = document.querySelectorAll('#productos .form-row');

            productosRows.forEach((row, index) => {
                const productoSelect = row.querySelector('.producto-select');
                const cantidadInput = row.querySelector('input[type="number"]');

                const idProducto = productoSelect.value;
                const cantidad = parseInt(cantidadInput.value);
                const precioUnitario = parseFloat(productoSelect.options[productoSelect.selectedIndex].text.split('$')[1]);

                // Verifica que el producto sea válido y no es el default (ID 1)...
                if (idProducto && idProducto != 1 && cantidad > 0) {
                    productos.push({
                        idProducto: idProducto,
                        cantidad: cantidad,
                        precio: precioUnitario
                    });
                }
            });

            console.log('Productos seleccionados:', productos);

            // Verifica que haya productos seleccionados válidos...
            if (productos.length === 0) {
                alert("Debes seleccionar al menos un producto.");
                return;
            }

            const factura = {
                idCliente: idCliente,
                fecha: fechaActual,
                total: totalFactura,
                pago: formaPago,
                productos: productos
            };

            console.log('Factura a enviar:', factura);

            fetch('guardar_factura.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(factura),
            })
                .then(response => {
                    console.log('Response:', response);
                    if (!response.ok) {
                        throw new Error('Error al guardar la factura');
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Factura guardada correctamente');
                    console.log('Factura guardada correctamente:', data);
                    document.getElementById('formCrearFactura').reset();
                    $('#modalCrearFactura').modal('hide');
                    location.reload();
                })
                .catch(error => {
                    console.error('Error al guardar la factura:', error.message);
                    alert('Error al guardar la factura. Por favor, revisa la consola para más detalles.');
                });
        }



        function calcularTotalFactura() {
            let totalFactura = 0;
            const productosRows = document.querySelectorAll('#productos .form-row');
            productosRows.forEach((row) => {
                const productoSelect = row.querySelector('.producto-select');
                const cantidadInput = row.querySelector('input[type="number"]');
                const precioUnitario = parseFloat(productoSelect.options[productoSelect.selectedIndex].text.split('$')[1]);
                const cantidad = parseInt(cantidadInput.value);
                const totalProducto = precioUnitario * cantidad;
                totalFactura += totalProducto;

            });
            return totalFactura;
        }

        // Función para obtener la fecha actual en formato YYYY-MM-DD...
        function obtenerFechaActual() {
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            const yyyy = today.getFullYear();

            return `${yyyy}-${mm}-${dd}`;
        }

        function verDetallesFactura(ID_Factura) {
            fetch(`obtenerDetallesFactura.php?id_factura=${ID_Factura}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener detalles de la factura');
                    }
                    return response.json();
                })
                .then(data => {
                    // Llenar los campos del formulario con los datos obtenidos
                    document.getElementById('infoCedula').value = data.cedula || 'No disponible';
                    document.getElementById('infoCliente').value = data.cliente || 'No disponible';
                    document.getElementById('infoEmail').value = data.email || 'No disponible';
                    document.getElementById('infoTelefono').value = data.telefono || 'No disponible';
                    document.getElementById('infoFecha').value = data.fecha || 'No disponible';
                    document.getElementById('infoDirecion').value = data.direccion || 'No disponible';
                    document.getElementById('infoPago').value = data.forma || 'No disponible';
                    document.getElementById('infoFactura').value = '# ' + (data.facturaN || 'No disponible');

                    // Limpiar y llenar la tabla de detalles de productos..
                    const detalleProductos = document.getElementById('detalleProductos');
                    detalleProductos.innerHTML = '';

                    let totalFactura = 0; // Variable para calcular el total de la factura...

                    // Construir filas de detalles de productos....
                    data.productos.forEach(producto => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td>${producto.Nombre}</td>
                    <td>${producto.Cantidad}</td>
                    <td>$${producto.Precio}</td>
                    <td>$${(producto.Cantidad * producto.Precio).toFixed(2)}</td>
                `;
                        detalleProductos.appendChild(row);

                        // Sumar al total de la factura.....
                        totalFactura += producto.Cantidad * producto.Precio;
                    });

                    // Crear una fila para mostrar el total....
                    const totalRow = document.createElement('tr');
                    totalRow.innerHTML = `
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>$${totalFactura.toFixed(2)}</strong></td>
            `;
                    detalleProductos.appendChild(totalRow);

                    // Mostrar el modal de detalles de factura....
                    $('#modalDetallesFactura').modal('show');
                })
                .catch(error => {
                    console.error('Error al obtener detalles de la factura:', error);
                    alert('Error al obtener detalles de la factura');
                });
        }

        function descargarFactura() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const cedula = document.getElementById('infoCedula').value;
            const cliente = document.getElementById('infoCliente').value;
            const email = document.getElementById('infoEmail').value;
            const telefono = document.getElementById('infoTelefono').value;
            const direccion = document.getElementById('infoDirecion').value;
            const fecha = document.getElementById('infoFecha').value;
            const formaPago = document.getElementById('infoPago').value;
            const numeroFactura = document.getElementById('infoFactura').value;

            const facturaData = [
                ['Factura', numeroFactura],
                ['Fecha', fecha],
                ['Cliente', cliente],
                ['Cédula', cedula],
                ['Email', email],
                ['Teléfono', telefono],
                ['Dirección', direccion],
                ['Forma de Pago', formaPago]
            ];

            doc.autoTable({
                startY: 10,
                head: [['Detalle', 'Información']],
                body: facturaData,
                styles: {
                    halign: 'left'
                },
                headStyles: {
                    fillColor: [22, 50, 133]
                },
                margin: { top: 10 },
                bodyStyles: {
                    cellPadding: 2 // // Reduce el relleno de la celda para acercar las filas...
                }
            });


            let productos = [];
            let totalFactura = 0;
            document.querySelectorAll('#detalleProductos tr').forEach(tr => {
                const tds = tr.querySelectorAll('td');
                if (tds.length === 4) {
                    const producto = tds[0].textContent || '';
                    const cantidad = tds[1].textContent || '';
                    const precio = parseFloat(tds[2].textContent.replace('$', '')) || 0;
                    const total = parseFloat(tds[3].textContent.replace('$', '')) || 0;
                    totalFactura += total;
                    productos.push([producto, cantidad, `$${precio.toFixed(2)}`, `$${total.toFixed(2)}`]);
                } else {
                    console.warn('Fila de productos con número incorrecto de columnas:', tds.length);
                }
            });
            // Agregar el total de la factura como una última fila
            productos.push(['', '', 'Total:', `$${totalFactura.toFixed(2)}`]);
            doc.autoTable({
                startY: doc.previousAutoTable.finalY + 5,
                head: [['Producto', 'Cantidad', 'Precio Unitario', 'Total']],
                body: productos,
                styles: {
                    halign: 'center'
                },
                headStyles: {
                    fillColor: [22, 160, 133]
                }
            });

            doc.save(`Factura_${numeroFactura}.pdf`);
        }

        document.addEventListener('DOMContentLoaded', function () {
            cargarProductos();
        });

        // Obtener la ruta actual de la URL...
        var path = window.location.pathname;

        // Limpiar el estado 'active' de todos los elementos....
        document.querySelectorAll('.nav-item').forEach(function (navItem) {
            navItem.classList.remove('active');
        });

        // Basado en la ruta, asignar la clase 'active' al elemento correspondiente...
        if (path.endsWith("/cliente/cliente.php")) {
            document.getElementById("clientes").classList.add("active");
        } else if (path.endsWith("/productos/producto.php")) {
            document.getElementById("product").classList.add("active");
        } else if (path.endsWith("/facturacion/facturas.php")) {
            document.getElementById("facturas").classList.add("active");
        }
    </script>
    <!-- Bootstrap JS y script personalizado -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>