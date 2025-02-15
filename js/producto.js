function guardarProducto(accion) {

    let id = 0;
    if (accion == 'editar') {
        id = $('#Id').val();
    }

    let nombre = $('#nombre').val();
    let precio = $('#precio').val();
    let ipc = $('#ipc').val();
    let iva = $('#iva').val();
    let url = "../templates/add_producto.php";
    let valida = true;
    if (valida) {
        if (nombre == '') {
            valida = false;
            $('.alert-danger').text('Ingrese el  nombre').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);

        }
    }
    if (valida) {
        if (precio == '') {
            valida = false;
            $('.alert-danger').text('Ingrese el precio').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);

        }
    }
    if (valida) {
        if (ipc == '') {
            valida = false;
            $('.alert-danger').text('Ingrese el IPC').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);

        }
    }
    precio = parseFloat(precio).toFixed(2);

    if (valida) {
        $('#cargando').show();
        $('#btnGuardar').attr('disabled', true);
        let data = {
            nombre: nombre,
            precio: precio,
            ipc: ipc,
            iva: iva,
            accion: accion,
            id: id,
        };
        $.ajax({
            type: "POST",
            url: "../controller/productoController.php",
            data: data,
            cache: false,
            dataType: 'json',
            success: function (data) {
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
                    }, 3000);
                }
                setTimeout(function () {
                    window.location.href = url;
                }, 3000);

            },
            error: function (xhr, status, error) {
                console.error(xhr);
                $('#cargando').hide();
                $('#btnGuardar').attr('disabled', false);
            }
        });


    }
}

function eliminarProducto(id) {
    let url = "../templates/add_producto.php";
    let result = confirm("¿Desea eliminar el producto?");
    if (result) {
        let data =
        {
            id: id,
            accion: 'eliminar'
        }
        $.ajax({
            type: "POST",
            url: "../controller/productoController.php",
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

function agregar() {
    if ($("#producto").val() == "sel") {
    }
    else {
        let datos = ($("#producto").val()).split("-");

        let cantidad = 1;
        let total = datos[2] * cantidad;

        let nuevaFila = '<tr><td>' + datos[0] + '</td><td>' + datos[1] + '</td><td>' + cantidad + '</td><td>' + datos[3] + '</td><td>' + datos[4] + '</td><td>' + total + '</td><td><a  class="eliminarBtn" onclick="eliminarFila(this)"><img src=../img/eliminar.png  height="15" width="15"></a></td></tr>';

        $('#facturasTable').append(nuevaFila);
    }

}

function eliminarFila(btn) {
    // Obtener la fila actual y eliminarla
    let fila = btn.parentNode.parentNode;
    fila.parentNode.removeChild(fila);
}

function facturar() {

    let valida = true;
    let url = "";
    if (valida) {
        if ($('#cliente').val() == 'sel') {
            valida = false;
            $('.alert-danger').text('Selecione un cliente').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);

        }
    }
    if (valida) {
        if ($('#modoPago').val() == 'sel') {
            valida = false;
            $('.alert-danger').text('Selecione un modo de pago').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);

        }
    }

    if(valida)
    {
    let tabla = document.getElementById('facturasTable');

    let datosTabla = [];

    for (let i = 1; i < tabla.rows.length; i++) {

        let fila = tabla.rows[i];
        let datosFila = [];
        datosFila = {
            id: fila.cells[0].innerText,
            nombre: fila.cells[1].innerText,
            cantidad: fila.cells[2].innerText,
            ipo: fila.cells[3].innerText,
            iva: fila.cells[4].innerText,
            precio: fila.cells[5].innerText,

        };
        datosTabla.push(datosFila);
    }
    data = {
    datosTabla: datosTabla,
    cliente: $('#cliente').val(),
    modoPago : $('#modoPago').val(),

    }
    if (datosTabla.length === 0) {
        $('.alert-danger').text('Ingrese items a la factura').fadeIn().delay(3000).fadeOut();

            setTimeout(function () {
                $('.alert-success').fadeOut();
            }, 3000);
    }
    else {
        $('#factura').show();
        $('#facturaBTN').attr('disabled', true);
        $.ajax({
            type: "POST",
            url: "../controller/facturarController.php",
            data:  JSON.stringify(data),
            cache: false,
            dataType: 'json',
            success: function (data) {
                $('#factura').hide();
                $('#facturaBTN').attr('disabled', false);
                if (data.success) {

                    $('.alert-success').text(data.mensaje).fadeIn().delay(3000).fadeOut();

                    setTimeout(function () {
                        $('.alert-success').fadeOut();
                    }, 9000);
                }
                else {

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
                console.error(xhr);

            }
        });

}
}





}