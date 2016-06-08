<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
    $nombre = desinfectar($_POST['nombre_completo']);
    $email = desinfectar($_POST['email']);
    $calle = desinfectar($_POST['calle']);
    $calle2 = desinfectar($_POST['calle2']);
    $ciudad = desinfectar($_POST['ciudad']);
    $estado = desinfectar($_POST['estado']);
    $codigo_postal = desinfectar($_POST['codigo_postal']);
    $pais = desinfectar($_POST['pais']);
    $errores = array();
    $necesario = array{
        'nombre_completo' => 'Nombre Completo',
        'email' => 'Email',
        'calle' => 'Direccion Calle',
        'ciudad' => 'Ciudad',
        'Estado' => 'Estado',
        'codigo_postal' => 'Codigo Postal',
        'pais' => 'Pais',
    };

//observar si todos los campos necesarios se llenaron
foreach($necesario as $c => $d){
    if(empty($_POST[$c]) || $_POST[$c] == ''){
        $errores[] = $d. 'es necesario.';
    }
}

if(!empty($errores)){
    echo desplegar_errores($errores);
}
else{
    echo 'pasa';
}


//observar si la direccion de email es valida
if(filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errores[] ='Ingrese un email valido.';
}

    
    if(!empty($errores)){
    echo desplegar_errores($errores);
}
    else{
        echo 'pasa';
    }
?>