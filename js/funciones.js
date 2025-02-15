function cargarMunicipios() {
    let departamento = document.getElementById('departamento').value;
    document.getElementById("municipio").innerHTML = "";
    $.ajax({
        url: '../clientes/getMunicipios.php',
        type: 'GET',
        data: {
            departamento: departamento,
            tipo: 0
        },
        success: function (respuesta) {

            let select = document.getElementById("municipio");
            let option = document.createElement("option");
            select.appendChild(option);
            respuesta.forEach(function (opcion) {
                var option = document.createElement("option");
                option.value = opcion.id_municipio;
                option.text = opcion.nom_municipio;
                select.appendChild(option);
            });
        },
        error: function (xhr, status, error) {
            // Manejamos errores si los hay
            console.log('Error: ' + error);
        }
    });
}

function cargarCodigo() {
    let departamento = document.getElementById('departamento').value;
    let ciudad = document.getElementById('municipio').value;
    $.ajax({
        url: '../clientes/getMunicipios.php',
        type: 'GET',
        data: {
            departamento: departamento,
            ciudad: ciudad,
            tipo: 1
        },
        success: function (respuesta) {
            document.getElementById("codigo_postal").value = respuesta[0].codigo;

        },
        error: function (xhr, status, error) {
            console.log('Error: ' + error);
        }
    });

}

function guardarCliente() {
    let tipoIdentificacion = $("#tipoIdentificacion").val();
    let identificacion = $("#identificacion").val();
    let departamento= $("#departamento").val();
    let municipio = $("#municipio").val();
    let email = $("#email").val();
    let pais = $("#pais").val();
    let telefono = $("#telefono").val();
    let codigo_postal = $("#codigo_postal").val();
    let tipo_p = $("#tipo_p").val();
    let res_fiscal = $("#res_fiscal").val();
    let res_regimen = $("#res_regimen").val();
    let tributo = $("#tributo").val();
    let nombre = $("#nombre").val();
    let direccion = $("#direccion").val();
    let data = {
        tipoIdentificacion: tipoIdentificacion,
        identificacion: identificacion,
        departamento:  departamento,
        municipio: municipio,
        email: email,
        pais: pais,
        telefono: telefono,
        codigo_postal: codigo_postal,
        tipo_p: tipo_p,
        res_fiscal: res_fiscal,
        res_regimen: res_regimen,
        tributo : tributo,
        nombre : nombre,
        direccion : direccion,
        accion: 'nuevo',
    };

    let valida = true;
    if (valida) {
        if (nombre == '') {
            valida = false;
            $('.alert-danger').text('Digite los nombres').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);
        }
    }
    if (valida) {
        if (tipoIdentificacion == '') {
            valida = false;
            $('.alert-danger').text('Seleccione el tipo de Identificación').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);
        }
    }
    if (valida) {
        if (identificacion == '') {
            valida = false;
            $('.alert-danger').text('Digite la Identificación').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);
        }
    }
    if (valida) {
        if (departamento == '') {
            valida = false;
            $('.alert-danger').text('Seleccione un departamento').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);
        }
    }

    if (valida) {
        if (municipio == '') {
            valida = false;
            $('.alert-danger').text('Seleccione un municipio').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);
        }
    }
    if (valida) {
        if (email == '') {
            valida = false;
            $('.alert-danger').text('Digite un correo electronico').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);
        }
    }
 

 if (valida)guardarclientes(data);

}

function guardarclientes(data)
{
    let url = "../clientes/add_cliente.php";
    $.ajax({
        url: '../clientes/gestionClientes.php',
        type: 'POST',
        data: data,
        success: function (datos) {
            let data = JSON.parse(datos);
            if (data.success) {
                $('#cargando').hide();
                $('#btnGuardar').attr('disabled', false);
                $('.alert-success').text(data.mensaje).fadeIn().delay(3000).fadeOut();

                setTimeout(function () {
                    $('.alert-success').fadeOut();
                }, 3000);
            }
            else {
                $('#cargando').hide();
                $('#btnGuardar').attr('disabled', false);
                $('.alert-danger').text(data.mensaje).fadeIn().delay(3000).fadeOut();

                setTimeout(function () {
                    $('.alert-danger').fadeOut();
                }, 9000);
            }
            setTimeout(function () {
                window.location.href = url;
            }, 3000);
            

        },
        error: function (xhr, status, error) {
            
        }
    });
    
}

function eliminarCliente(id) {
    let url = "../clientes/add_cliente.php";
    let result = confirm("¿Desea eliminar el cliente?");
    if (result) {
        let data =
        {
            id: id,
            accion: 'eliminar'
        }
        $.ajax({
            type: "POST",
            url: "../clientes/gestionClientes.php",
            data: data,
            cache: false,
            dataType: 'json',
            success: function (data) {

                if (data.success) {

                    $('.alert-success').text(data.mensaje).fadeIn().delay(3000).fadeOut();

                    setTimeout(function () {
                        $('.alert-success').fadeOut();
                    }, 3000);
                }
                else {

                    $('.alert-danger').text(data.mensaje).fadeIn().delay(3000).fadeOut();

                    setTimeout(function () {
                        $('.alert-danger').fadeOut();
                    }, 3000);
                }
                setTimeout(function () {
                    window.location.href = url;
                }, 3000);


            },
            error: function (xhr, status, error) {
                console.error(xhr);

            }
        });


    }
}

