<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
/*if(!is_logged_in()){
  login_error_redirect();
}*/
include 'includes/head.php';
/*$contraseña = 'contraseña';
$hashed = password_hash($contraseña,PASSWORD_DEFAULT);
echo $hashed;*/
$email =((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$contraseña =((isset($_POST['contraseña']))?sanitize($_POST['contraseña']):'');
$contraseña = trim($contraseña);
$errores = array();
?>
<style>
    body{
        background-image:url("/tutorial/images/headerlogo/banckground.png");
        background-size: 100vw 100vh;
        background-attachment: fixed;
    }
</style>
<div id="login-form">
<div>
    
<?php
    if($_POST){
        //forma de validacion
        if(empty($_POST['email']) || empty($_POST['contraseña'])){
        $errores[] = 'Debes dar un email y contraseña';
    }
        //validar email
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errores[] = 'Debes ingresar un email valido';
        }
        
        //la contraseña es de mas de 6 caracteres
        if(strlen($contraseña) < 6){
            $errores[] = 'La contraseña debe ser de al menos 6 caracteres';
        }
    }
      
        // observar si el email existe en la base de datos
        $query = $db->query("select * from usuarios where email = '$email'");
        $usuario = mysqli_fetch_assoc($query);
        $cuentaUsuario = mysqli_num_rows($query);
        if($cuentaUsuario < 1){
            $errores[] = 'ese email no existe en la base de datos';
        }
        
        if(!password_verify($contraseña, $usuario['contraseña'])){
            $errores[] = 'La contraseña no coincide con nuestros registros. Por favor, intente de nuevo';
        }
        
        //Observar para errores
        if(!empty($errores)){
            echo desplegar_errores($errores);
        }
        else{
            //ingresar usuario
            $id_usuario = $usuario['id'];
            login($id_usuario);
        }
    ?>
    
</div>
<h2 class="text-center">Inicio sesion</h2><hr>
<form action="login.php" method="post">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
     </div>
    <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" class="form-control" value="<?=$contraseña;?>">
     </div>
     <div class="form-group">
         <input type="submit" value="Iniciar sesion" class="btn btn-primary">
     </div>
</form>
<p class="text-right"><a href="/tutorial/index.php" alt="home">Visitar sitio</a></p>
</div>
<?php include 'includes/footer.php'; ?>
