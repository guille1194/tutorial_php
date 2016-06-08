<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
include 'includes/head.php';
/*$contraseña = 'contraseña';
$hashed = password_hash($contraseña,PASSWORD_DEFAULT);
echo $hashed;*/
$hashed = $datos_usuario['contraseña'];
$contraseña_anterior =((isset($_POST['contraseña_anterior']))?desinfectar($_POST['contraseña_anterior']):'');
$contraseña_anterior = trim($contraseña_anterior);
$contraseña =((isset($_POST['contraseña']))?desinfectar($_POST['contraseña']):'');
$contraseña = trim($contraseña);
$confirmar =((isset($_POST['confirmar']))?desinfectar($_POST['confirmar']):'');
$confirmar = trim($confirmar);
$nuevo_hashed = password_hash($contraseña, PASSWORD_DEFAULT);
$id_usuario = $datos_usuario['id'];
$errores = array();
?>

<div id="login-form">
<div>
    
<?php
    if($_POST){
        //forma de validacion
        if(empty($_POST['contraseña_anterior']) || empty($_POST['contraseña']) || empty($_POST['confirmar'])){
        $errores[] = 'Debes llenar todos los campos';
    }
        
        //la contraseña es de mas de 6 caracteres
        if(strlen($contraseña) < 6){
            $errores[] = 'La contraseña debe ser de al menos 6 caracteres';
        }
    }

//si la nueva contraseña coincide con la confirmacion
if($contraseña != $confirmar){
    $errores[] = 'La nueva contraseña y la confirmacion de la nueva contraseña no coinciden';
}

        if(!password_verify($contraseña_anterior, $hashed)){
            $errores[] = 'Tu contraseña anterior no coincide con nuestros registros.';
        }
        
        //Observar para errores
        if(!empty($errores)){
            echo desplegar_errores($errores);
        }
        else{
            //cambiar contraseña
            $db->query("update usuarios set contraseña = '$nuevo_hashed' where id = 'id_usuario'");
            $_SESION['exito_flash'] = 'Su contraseña a sido actualizada';
            header("Location: index.php");
        }
    ?>
    
</div>
<h2 class="text-center">Cambiar contraseña</h2><hr>
<form action="change_password.php" method="post">
    <div class="form-group">
        <label for="old_password">Contraseña anterior:</label>
        <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$contraseña_anterior;?>">
     </div>
    <div class="form-group">
        <label for="password">Nueva contraseña:</label>
        <input type="password" name="password" id="password" class="form-control" value="<?=$contraseña;?>">
     </div>
    <div class="form-group">
        <label for="confirm">Confirmar nueva contraseña:</label>
        <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirmar;?>">
     </div>
     <div class="form-group">
        <a href="index.php" class="btn btn-default">Cancelar</a>
         <input type="submit" value="Iniciar sesion" class="btn btn-primary">
     </div>
</form>
<p class="text-right"><a href="/tutorial/index,php" alt="home">Visitar sitio</a></p>
</div>
<?php include 'includes/footer.php'; ?>