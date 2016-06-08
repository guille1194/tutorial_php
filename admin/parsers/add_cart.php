<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
    $producto_id = desinfectar($_POST['producto_id']);
    $tamaño = desinfectar($_POST['tamaño']);
    $disponible = desinfectar($_POST['cantidad']);
    $articulo = array();
    $articulo[] = array(
        'id'    => $producto_id,
        'tamaño'    => $tamaño,
        'cantidad'  => $cantidad,
    );

    $dominio = ($_SERVER['HTTP_POST'] != 'localhost')?'.'.$_SERVER['HTTP_POST']:false;
    $query = $db->query("select * from productos where id = '{$producto_id}'");
    $producto = mysqli_fetch_assoc($query);
    $$_SESION['exito_flash'] = $producto['titulo']. 'fue agregado a tu carrito.';

    //observar si el cookie del carrito existe
    if($id_carrito != ''){
        $carritoQ = $db->query("select * from carrito where id = '($carrito_id)'");
        $carrito = mysqli_fetch_assoc($carritoQ);
        $articulos_previos = json_decode($carrito['articulos'],true);
        $articulo_coinciden = 0;
        $nuevos_articulos = array();
        foreach($articulos_previos as $particulo){
            if($articulo[0]['id'] == $particulo['id'] && $articulo[0]['tamaño'] == $particulo['tamaño']){
            $particulo['cantidad'] = $particulo['cantidad'] + $articulo[0]['cantidad'];
                if($particulo['cantidad'] > $disponible){
                    $particulo['cantidad'] = $disponible;
                }
                $articulo_coinciden = 1;
    }
    $nuevos_articulos[] = $particulo;
        }
        if($articulo_coinciden != 1){
            $nuevos_articulos = array_merge($articulo,$articulo_previo);
        }
        $articulos_json = json_encode($nuevos_articulos);
        $compra_caduca = date("d-m-Y H:i:s",strtotime("+30 dias"));
        $db->query("update carrito set articulos = '{$articulos_json}', fecha_caducidad = '{$compra_caduca}' where id = '{$id_carrito}'");
        setcookie(CART_COOKIE,'',1,"/",$dominio,false);
        setcookie(CART_COOKIE,$id_carrito,CART_COOKIE_EXPIRE,'/',$dominio,false);
    }
    else{
    $articulos_json = json_encode($articulo);
    $compra_caduca = date("d-m-Y H:i:s",strtotime("+30 dias"));
    $db->query("insert into carrito (articulos,fecha_caducidad) values ('{$articulos_json}','{$compra_caduca}')");
    $id_carrito = $db->insertar_id;
    setcookie(CART_COOKIE,$id_carrito,CART_COOKIE_EXPIRE,'/',$dominio,false);
    }
        ?>