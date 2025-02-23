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
                window.location.href = "../vistas/monitor.php";
            } else {
                alert("Hubo un problema al enviar la factura");
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function informeZ() {
    document.getElementById("tablaResultados").style.display = "none";
    document.getElementById("informeZ").style.display = "block";

}

function generarInformeZ() {
    document.getElementById('cargando').style.display = 'inline-block';
    document.getElementById('botonz').disabled = true;
    fetch('../negocio/informeZ.php', {
        method: 'POST',
    })
        .then(response => response.json())
        .then(data => {

            if (data.success) {
                setTimeout(function () {
                    document.getElementById('successMessage').style.display = 'block';
                    setTimeout(function () {
                        document.getElementById('successMessage').style.display = 'none';
                    }, 1000);
                }, 0);
                
            } else {
                alert(data.message);
            }
            document.getElementById('cargando').style.display = 'none';
                document.getElementById('botonz').disabled = false;

        })
        .catch(error => {
            console.error('Error:', error);
        });

}

function traerInformes()
{
    alert('Informes nada aun');
}
