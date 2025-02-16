function enviarFactura(factura) {
    
    document.getElementById("loadingOverlay").style.display = "flex";
    const data = new FormData();
    data.append("factura", JSON.stringify(factura));

    fetch('../negocio/procesarPeticion.php', {
        method: 'POST',
        body: data
    })
        .then(response => response.json())
        .then(data => {
            
            if (data.success) {
                alert(data.message);
                window.location.href ="../vistas/monitor.php";
            } else {
                alert("Hubo un problema al enviar la factura");
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
