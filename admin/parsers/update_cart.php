<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
    $modo = desinfectar($_POST['modo']);
    $editar_tamaño = desinfectar($_POST['editar_tamaño']);
    $editar_id = desinfectar($_POST['editar_id']);
    $carritoQ = $db->query("select * from carrito where id = '{$carrito_id}'");
    $resultado = mysqli_fetch_assoc($carritoQ);
    $articulos = json_decode($resultado['articulos'],true);
    $articulos_actualizados = array();
    $dominio = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

    if($modo == 'quitaruno'){
        foreach($articulos as $articulo){
            if($articulo['id'] == $editar_id && $articulo['tamaño'] == $editar_tamaño){
                $articulo['cantidad'] = $articulo['cantidad'] - 1;
            }
            if($articulo['cantidad'] > 0){
                $articulos_actualizados[] = $articulo;
            }
        }
    }

    if($modo == 'agregaruno'){
        foreach($articulos as $articulo){
            if($articulo['id'] == $editar_id && $articulo['tamaño'] == $editar_tamaño){
                $articulo['cantidad'] = $articulo['cantidad'] + 1;
            }
                $articulos_actualizados[] = $articulo;
            }
    }

    if(!empty($articulos_actualizados)){
        $actualizar_json = json_encode($articulos_actualizados);
        $db->query("update carrito set articulos = '{$json_actualizado}' where id = '{$id_carrito}'");
        $_SESION['exito_flash'] = 'Tu carrito ha sido actualizado!';
    }

    if(empty($articulos_actualizados)){
        $db->query("delete from carrito where id = '{$id_carrito}'");
        setcookie(CART_COOKIE,'',1,"/",$dominio,false);
        
    }
?>


