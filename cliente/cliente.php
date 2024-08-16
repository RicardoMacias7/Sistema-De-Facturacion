<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado y Gestión de Clientes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h2>Listado de Clientes</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearCliente">Agregar
            Cliente</button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
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

                $sql = "SELECT ID_Cliente, Cedula, Nombre, Direccion, Telefono, Email FROM Clientes WHERE Activo = TRUE";
                $result = $conn->query($sql);


                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Cedula"] . "</td>";
                        echo "<td>" . $row["Nombre"] . "</td>";
                        echo "<td>" . $row["Direccion"] . "</td>";
                        echo "<td>" . $row["Telefono"] . "</td>";
                        echo "<td>" . $row["Email"] . "</td>";
                        echo '<td>
                                <button class="btn btn-info" data-toggle="modal" data-target="#modalEditarCliente" onclick="cargarDatosEditar(' . $row["ID_Cliente"] . ')">Editar</button>
                                <button class="btn btn-danger ml-2" onclick="eliminarCliente(' . $row["ID_Cliente"] . ')">Eliminar</button>
                              </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron clientes</td></tr>";
                }

                $conn->close();
                ?>

            </tbody>
        </table>
    </div>
    <!-- Modal para crear -->
    <div class="modal fade" id="modalCrearCliente" tabindex="-1" role="dialog" aria-labelledby="modalCrearClienteLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearClienteLabel">Agregar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCrearCliente">
                        <div class="form-group">
                            <label for="cedulaCrear">Cédula</label>
                            <input type="text" class="form-control" id="cedulaCrear" required>
                        </div>
                        <div class="form-group">
                            <label for="nombreCrear">Nombre</label>
                            <input type="text" class="form-control" id="nombreCrear" required>
                        </div>
                        <div class="form-group">
                            <label for="direccionCrear">Dirección</label>
                            <input type="text" class="form-control" id="direccionCrear" required>
                        </div>
                        <div class="form-group">
                            <label for="telefonoCrear">Teléfono</label>
                            <input type="text" class="form-control" id="telefonoCrear" required>
                        </div>
                        <div class="form-group">
                            <label for="emailCrear">Email</label>
                            <input type="email" class="form-control" id="emailCrear" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCliente()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar -->
    <div class="modal fade" id="modalEditarCliente" tabindex="-1" role="dialog"
        aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarClienteLabel">Editar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditarCliente">
                        <input type="hidden" id="idClienteEditar" value="">
                        <div class="form-group">
                            <label for="cedulaEditar">Cédula</label>
                            <input type="text" class="form-control" id="cedulaEditar" required>
                        </div>
                        <div class="form-group">
                            <label for="nombreEditar">Nombre</label>
                            <input type="text" class="form-control" id="nombreEditar" required>
                        </div>
                        <div class="form-group">
                            <label for="direccionEditar">Dirección</label>
                            <input type="text" class="form-control" id="direccionEditar" required>
                        </div>
                        <div class="form-group">
                            <label for="telefonoEditar">Teléfono</label>
                            <input type="text" class="form-control" id="telefonoEditar" required>
                        </div>
                        <div class="form-group">
                            <label for="emailEditar">Email</label>
                            <input type="email" class="form-control" id="emailEditar" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <!-- <button type="button" class="btn btn-primary" onclick="actualizarCliente()">Actualizar</button>-->
                    <button type="button" class="btn btn-primary"
                        onclick="actualizarClienteSimilacion()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>



    <script>

        function cargarDatosEditar(idCliente) {
            const url = `obtener_cliente.php?id=${idCliente}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalEditarClienteLabel').textContent = 'Editar Cliente';
                    document.getElementById('idClienteEditar').value = idCliente;
                    document.getElementById('cedulaEditar').value = data.Cedula;
                    document.getElementById('nombreEditar').value = data.Nombre;
                    document.getElementById('direccionEditar').value = data.Direccion;
                    document.getElementById('telefonoEditar').value = data.Telefono;
                    document.getElementById('emailEditar').value = data.Email;
                })
                .catch(error => console.error('Error:', error));

            // Mostrar el modal de editar cliente
            $('#modalEditarCliente').modal('show');
        }


        //         // Evento cuando el modal se muestra
        // $('#modalCliente').on('shown.bs.modal', function () {
        //     // Aquí puedes realizar acciones adicionales cuando el modal se muestra
        //     // Por ejemplo, cargar datos si es para editar un cliente existente

        // });

        // // Evento cuando el modal se cierra
        // $('#modalCliente').on('hidden.bs.modal', function () {
        //     // Aquí puedes realizar acciones adicionales cuando el modal se cierra
        //     // Por ejemplo, limpiar el formulario
        //     document.getElementById('formClienteModal').reset();

        // });



        // Función para guardar un nuevo cliente...
        function guardarCliente() {
            const cedula = document.getElementById('cedulaCrear').value.trim();
            const nombre = document.getElementById('nombreCrear').value.trim();
            const direccion = document.getElementById('direccionCrear').value.trim();
            const telefono = document.getElementById('telefonoCrear').value.trim();
            const email = document.getElementById('emailCrear').value.trim();

            // Validar campos
            if (cedula === '' || nombre === '' || direccion === '' || telefono === '' || email === '') {
                alert('Por favor, complete todos los campos.');
                return;
            }

            const formData = new FormData();
            formData.append('cedula', cedula);
            formData.append('nombre', nombre);
            formData.append('direccion', direccion);
            formData.append('telefono', telefono);
            formData.append('email', email);


            fetch('guardar_cliente.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    alert('Cliente creado exitosamente.');
                    $('#modalCrearCliente').modal('hide');
                    location.reload();
                    // Puedes recargar la tabla de clientes o realizar alguna acción adicional aquí...
                })
                .catch(error => console.error('Error:', error));
        }

        // Función para actualizar un cliente existente..
        function actualizarCliente() {
            const idCliente = document.getElementById('idClienteEditar').value;
            const cedula = document.getElementById('cedulaEditar').value.trim();
            const nombre = document.getElementById('nombreEditar').value.trim();
            const direccion = document.getElementById('direccionEditar').value.trim();
            const telefono = document.getElementById('telefonoEditar').value.trim();
            const email = document.getElementById('emailEditar').value.trim();


            if (idCliente === '' || cedula === '' || nombre === '' || direccion === '' || telefono === '' || email === '') {
                alert('Por favor, complete todos los campos.');
                return;
            }

            const formData = new FormData();
            formData.append('idCliente', idCliente);
            formData.append('cedula', cedula);
            formData.append('nombre', nombre);
            formData.append('direccion', direccion);
            formData.append('telefono', telefono);
            formData.append('email', email);

            fetch('actualizar_cliente.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    alert('Cliente actualizado exitosamente.');
                    $('#modalEditarCliente').modal('hide');
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
        }
        function actualizarClienteSimilacion() {
            alert('Similacion...  Datos Actualizados')
        }


        function eliminarCliente(idCliente) {
            if (confirm('Simulacion.... ¿Estás seguro de que deseas eliminar este cliente?')) {
                $.ajax({
                    url: 'eliminar_cliente.php',
                    method: 'POST',
                    data: { idCliente: idCliente },
                    success: function (response) {
                        $('#cliente_' + idCliente).remove();
                    },
                    error: function () {
                        alert('Error al eliminar el cliente');
                    }
                });
            }
        }



        var path = window.location.pathname;
.
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