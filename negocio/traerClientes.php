<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['data'])) {

    } else {

        echo json_encode([
            "estado" => 'error',
            "mensaje" => 'no se recibio los datos.',
            "cufe" => ''
        ]);
    }
}