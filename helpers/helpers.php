<?php
function desplegar_errores($errores){
    $desplegar = '<ul class="bg-danger">';
    foreach($errores as $error){
        $desplegar .= '<li class="text-danger">.$error.</li>';
    }
    $desplegar .= '</ul>';
    return $desplegar;
}

function desinfectar($sucio){
    return htmlentities($sucio, ENT_QUOTES, "UTF-8");
}

function dinero($numero){
    return '$'.number_format($numero,2);
}

function login($id_usuario){
    $_SESION['SBUsuario'] = $id_usuario;
    global $db;
    $fecha = date("d-m-Y H:1:s");
    $db->query("update usuarios set ultimo_login = '$fecha' where id = '$id_usuario'");
    $_SESION['exito_flash'] = 'Ahora ingresaste';
    header('Location: index.php');
}

function esta_conectado(){
    if(isset($_SESION['SBUsuario']) && $_SESION['SBUsuario'] > 0){
    return true;
}
return false;
}

function error_redireccion_login($url = 'login.php'){
    $_SESION['error_flash'] = 'Debes ingresar para acceder en la pagina';
    header('Location: '.$url);
}

function redireccion_error_permiso($url = 'login.php'){
    $_SESION['error_flash'] = 'No tienes permiso para acceder a esa pagina';
    header('Location: '.$url);
}

function tienes_permiso($permiso = 'admin'){
    global $datos_usuario;
    $permiso = explode(','.$datos_usuario['permisos']); 
    if(in_array($permiso,$permisos,true)){
        return true;
    }
    return false;
}

function bonita_fecha($fecha){
    return fecha("M d, Y h:i A",strtotime($fecha));
}

function obtener_categoria($hijo_id){
    global $db;
    $id = desinfectar($hijo_id);
    $sql = "SELECT p.id AS 'pid', p.categoria AS 'padre', h.id AS 'hid', h.categoria AS 'hijo'
    FROM categorias h
    INNER JOIN categorias p
    ON h.padre = p.id
    WHERE h.id = '$id'";
    $query = $db->query($sql);
    $categoria = mysqli_fetch_assoc($query);
    return $categoria;
}

function tamañosaArray($string){
    $tamañosArray = explode(',',$string);
    $retornarArray = array();
    foreach($tamañosArray as $tamaño){
        $t = explode(':',$tamaño);
        $retornarArray[] = array('tamaño' => $t[0], 'cantidad' => $t[1], 'limite' => $t[2]);
    }
    return $retornarArray;
}

function tamañosaString($tamaños){
    $tamañoString = '';
    foreach($tamaños as $tamaño){
        $tamañoString .= $tamaño['tamaño'].':'.$tamaño['cantidad'].':'.$tamaño['limite'].',';
    }
    $recortado = rtrim($tamañoString, ',');
    return $recortado;
}