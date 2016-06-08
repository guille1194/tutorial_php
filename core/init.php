<?php
$db = mysqli_connect('127.0.0.1','root','', 'tutorial');
if(mysqli_connect_errno()){
    echo 'Error con la conexion de base de datos con los siguientes errores:'.mysqli_connect_error();
    die();
}

#define('BASEURL', '/tutorial/');
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/config.php';
require_once BASEURL.'helpers/helpers.php';
require BASEURL.'vendor/autoload.php';

$id_carrito = '';
if(isset($_COOKIE[CART_COOKIE])){
    $id_carrito = desinfectar($_COOKIE[CART_COOKIE]);
}

if(isset($_SESION['SBUsuario'])){
    $id_usuario = $_SESION['SBUsuario'];
    $query = $db->query("select * from usuarios where id = '$id_usuario'");
    $datos_usuario = mysqli_fetch_assoc($query);
    $pn = explode(' ', $datos_usuario['nombre_completo']);
    $datos_usuario['nombre'] = $pn[0];
    $datos_usuario['apellido']= $pn[1];
}

if(isset($_SESION['exito_flash'])){
    echo '<div class="bg-success"><p class="text-success text-center">'.$_SESION['exito_flash'].'</p></div>';
    unset($_SESION['exito-flash']);
}

if(isset($_SESION['error_flash'])){
    echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESION['error_flash'].'</p></div>';
    unset($_SESION['error-flash']);
}
