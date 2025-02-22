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

function informeZ()
{
    document.getElementById("tablaResultados").style.display = "none";
    document.getElementById("informeZ").style.display = "block";

}

function generarInformeZ()
{
    fetch('../negocio/informeZ.php', {
        method: 'POST',
    })
        .then(response => response.json())
        .then(data => {
            
            if (data.success) {
                
              
            } else {
               
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    
}
