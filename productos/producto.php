<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado y Gestión de Productos</title>
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
        <h2>Listado de Productos</h2>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalCrearProducto">Agregar
            Producto</button>

    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // $servername = "localhost";
                // $username = "root";
                // $password = "";
                // $dbname = "factura";
                $servername = "sql208.infinityfree.com";
                $username = "if0_37068684";
                $password = "QDDMXbjIIptT3u";
                $dbname = "if0_37068684_facturacion";

                
                $conn = new mysqli($servername, $username, $password, $dbname);

                
                if ($conn->connect_error) {
                    echo "falla";
                    die("Conexión fallida: " . $conn->connect_error);
                }
                $conn->set_charset("utf8");
                
                $sql = "SELECT ID_Producto, Nombre, Precio FROM Productos";
                $result = $conn->query($sql);

                // Mostrar datos de clientes en la tabla -->
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Nombre"] . "</td>";
                        echo "<td>" . $row["Precio"] . "</td>";

                        echo '<td>
                                <button class="btn btn-info" data-toggle="modal" data-target="#modalCliente" onclick="cargarDatosEditarProducto(' . $row["ID_Producto"] . ')">Editar</button>
                                    <button class="btn btn-danger ml-2" onclick="eliminarProducto(' . $row["ID_Producto"] . ')">Eliminar</button>
                                </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron productos</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <!-- Modal para crear producto -->
    <div class="modal fade" id="modalCrearProducto" tabindex="-1" role="dialog"
        aria-labelledby="modalCrearProductoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearProductoLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCrearProducto">
                        <div class="form-group">
                            <label for="nombreCrear">Nombre</label>
                            <input type="text" class="form-control" id="nombreCrear" required>
                        </div>
                        <div class="form-group">
                            <label for="precioCrear">Precio</label>
                            <input type="text" class="form-control" id="precioCrear" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarProducto()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar producto -->
    <div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog"
        aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditarProducto">
                        <input type="hidden" id="idProductoEditar" value="">
                        <div class="form-group">
                            <label for="nombreEditar">Nombre</label>
                            <input type="text" class="form-control" id="nombreEditar" required>
                        </div>
                        <div class="form-group">
                            <label for="precioEditar">Precio</label>
                            <input type="text" class="form-control" id="precioEditar" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <!--  <button type="button" class="btn btn-primary" onclick="actualizarProducto()">Actualizar</button>-->
                    <button type="button" class="btn btn-primary"
                        onclick="actualizarProductoSimulacion()">Actualizar</button>

                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS y script personalizado -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Función para cargar datos en el modal de editar producto..
        function cargarDatosEditarProducto(idProducto) {
            
            const url = `obtener_producto.php?id=${idProducto}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalEditarProductoLabel').textContent = 'Editar Producto';
                    document.getElementById('idProductoEditar').value = idProducto;
                    document.getElementById('nombreEditar').value = data.Nombre;
                    document.getElementById('precioEditar').value = data.Precio;
                })
                .catch(error => console.error('Error:', error));

            // Mostrar el modal de editar producto..
            $('#modalEditarProducto').modal('show');
        }

        // Función para guardar un nuevo producto..
        function guardarProducto() {
            const nombre = document.getElementById('nombreCrear').value.trim();
            const precio = document.getElementById('precioCrear').value.trim();

            // Validar campos
            if (nombre === '' || precio === '') {
                alert('Por favor, complete todos los campos.');
                return;
            }

            const formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('precio', precio);

            
            fetch('guardar_producto.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    alert('Producto creado exitosamente.');
                    $('#modalCrearProducto').modal('hide');
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
        }


        // Función similar actualizacion...
        function actualizarProductoSimulacion(idProducto) {

            alert('Simulacion .. Producto Actulizado');


        }

        // Función para actualizar un producto ...
        function actualizarProducto() {
            const idProducto = document.getElementById('idProductoEditar').value;
            const nombre = document.getElementById('nombreEditar').value.trim();
            const precio = document.getElementById('precioEditar').value.trim();

            // Validar campos
            if (idProducto === '' || nombre === '' || precio === '') {
                alert('Por favor, complete todos los campos.');
                return;
            }

            const formData = new FormData();
            formData.append('idProducto', idProducto);
            formData.append('nombre', nombre);
            formData.append('precio', precio);


            fetch('actualizar_producto.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    alert('Producto actualizado exitosamente.');
                    $('#modalEditarProducto').modal('hide');
                    location.reload();
                    // Puedes recargar la tabla de productos o realizar alguna acción adicional aquí..
                })
                .catch(error => console.error('Error:', error));
        }


        function eliminarProducto(idProducto) {

            // alert('Implemendtar función para eliminar producto');

            if (confirm('Simlacion.... ¿Estás seguro de que deseas eliminar este Producto?')) {
                // Realizar petición AJAX para eliminar el Producto...
                $.ajax({
                    url: 'eliminar_producto.php',
                    method: 'POST',
                    data: { idProducto: idProducto },
                    success: function (response) {
                        // Si el Producto se elimina correctamente, actualizar tabla
                        $('#producto_' + idProducto).remove();
                        // Puedes añadir mensajes de confirmación u otras acciones necesarias...
                    },
                    error: function () {
                        alert('Error al eliminar el Producto');
                    }
                });
            }

        }

        // Evento cuando el modal de editar producto se muestra
        $('#modalEditarProducto').on('show.bs.modal', function (event) {
            // Obtener el botón que abre el modal
            const button = $(event.relatedTarget);
            // Extraer el ID del producto de los atributos de datos del botón
            const idProducto = button.data('idproducto');
            // Cargar los datos del producto en el modal de editar
            cargarDatosEditarProducto(idProducto);
        });
        // Obtener la ruta actual de la URL
        var path = window.location.pathname;

        // Limpiar el estado 'active' de todos los elementos
        document.querySelectorAll('.nav-item').forEach(function (navItem) {
            navItem.classList.remove('active');
        });

        // Basado en la ruta, asignar la clase 'active' al elemento correspondiente
        if (path.endsWith("/cliente/cliente.php")) {
            document.getElementById("clientes").classList.add("active");
        } else if (path.endsWith("/productos/producto.php")) {
            document.getElementById("product").classList.add("active");
        } else if (path.endsWith("/facturacion/facturas.php")) {
            document.getElementById("facturas").classList.add("active");
        }
    </script>

    <!-- //hora -->
</body>

</html>