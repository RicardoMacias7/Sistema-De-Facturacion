function crearCliente() {
    // Obtener los valores de los campos del formulario
    const nombre = document.getElementById('nombre').value.trim();
    const direccion = document.getElementById('direccion').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const email = document.getElementById('email').value.trim();

    // Validar que todos los campos estén completos
    if (nombre === '' || direccion === '' || telefono === '' || email === '') {
        alert('Por favor, complete todos los campos.');
        return;
    }

    // Aquí podrías enviar los datos del cliente a tu backend o API para guardarlos en la base de datos

    // Ejemplo de cómo podrías mostrar un mensaje de éxito o reiniciar el formulario
    alert('Cliente creado exitosamente.');
    document.getElementById('clienteForm').reset(); // Reiniciar el formulario después de crear el cliente
}
