function enviarFactura(factura) {
    $("#enviando").show();
    const data = new FormData();
    data.append("factura", JSON.stringify(factura));

    fetch('../negocio/procesarPeticion.php', {
        method: 'POST',
        body: data
    })
        .then(response => response.json())
        .then(data => {
            
            if (data.success) {
                alert("Factura enviada correctamente");
                $("#enviando").hide();
            } else {
                alert("Hubo un problema al enviar la factura");
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
