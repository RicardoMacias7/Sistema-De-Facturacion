function enviarSolicitudSOAP() {
    const soapMessage = `
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://example.com/webservice">
            <soapenv:Header/>
            <soapenv:Body>
                <web:CrearFactura>
                    <web:ClienteID>123</web:ClienteID>
                    <web:Productos>
                        <web:Producto>
                            <web:ProductoID>456</web:ProductoID>
                            <web:Cantidad>2</web:Cantidad>
                        </web:Producto>
                    </web:Productos>
                    <web:Total>100.00</web:Total>
                </web:CrearFactura>
            </soapenv:Body>
        </soapenv:Envelope>`;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "http://example.com/webservice", true); // Reemplaza con la URL de tu servicio SOAP
    xhr.setRequestHeader("Content-Type", "text/xml");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("Respuesta SOAP recibida:", xhr.responseText);
            // Aquí puedes manejar la respuesta recibida del servicio
        }
    };

    xhr.send(soapMessage);
}
