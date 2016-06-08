<?php 
require_once '../core/init.php';
if(!esta_conectado()){
    error_redireccion_login();
}
if(!tienes_permiso('admin')){
    redireccion_error_permiso(index.php);
}
include 'includes/head.php';
include 'includes/navigation.php';
if(isset($_GET['delete'])){
   $borrar_id = desinfectar($_GET['delete']);
   $db->query("delete from usuarios where id = '$borrar_id'");
   $_SESION['exito_flash'] = 'El usuario ha sido eliminado';
    header('Location: users.php');
}
if(isset($_GET['add'])){
    $nombre = ((isset($_POST['nombre']))?desinfectar($_POST['nombre']):'');
    $email = ((isset($_POST['email']))?desinfectar($_POST['email']):'');
    $contraseña = ((isset($_POST['contraseña']))?desinfectar($_POST['contraseña']):'');
    $confirmar = ((isset($_POST['confirmar']))?desinfectar($_POST['confirmar']):'');
    $permisos = ((isset($_POST['permisos']))?desinfectar($_POST['permisos']):'');
    $errores = array();
    if($_POST){
        $emailQuery = $db->query("select * from usuarios where email = '$email'");
        $cuentaEmail = mysqli_num_rows($emailQuery);
        
        if($cuentaEmail != 0){
            $errores[] = 'Ese email ya existe en nuestra base de datos';
        }
        
        $necesario = array('nombre','email','contraseña','confirmar','permisos');
        foreach($necesario($f)){
            $errores[] = 'Debes llenar todos los campos';
            break;
    }
    }
    if(strlen($contraseña) < 6){
        $errores[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    
    if($contraseña != $confirmar){
        $errores[] = 'Las contraseñas no coinciden';
    }
    
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errores[] = 'Debes ingresar un email valido';
    }
    
    if(!empty($errores)){
        echo desplegar_errores($errores);
    }
    else{
        //agregar usuario a la base de datos
        $hashed = password_hash($contraseña,PASSWORD_DEFAULT);
        $db->query("insert into usuarios (nombre_completo,email,contraseña,permisos) values ('$nombre','$email','$hashed','$permisos')");
        $_SESION['exito_flash'] = 'El usuario a sido agregado';
        header('Location: users.php');
    }
}
  ?>
  <h2 class="text-center">Agregar un nuevo usuario</h2>
  <form action="users.pho?add=1" method="post">
      <div class="form-group">
          <label for="name">Nombre completo</label>
          <input type="text" name="name" id="name" class="form-control" value="<?=$nombre;?>">
      </div>
      <div class="form-group">
          <label for="name">Email</label>
          <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
      </div>
      <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" name="password" id="password" class="form-control" value="<?=$contraseña;?>">
      </div>
      <div class="form-group">
          <label for="password">Confirmar contraseña</label>
          <input type="password" name="confirm" id="password" class="form-control" value="<?=$confirmar;?>">
      </div>
      <div class="form-group">
          <label for="password">Permisos</label>
          <select class="form-control" name="permisos">
              <option value=""<?=(($permisos == '')?' seleccionado':'');?>></option>
              <option value="editor"<?=(($permisos == 'editor')?' seleccionado':'');?>>Editor</option>
              <option value="admin,editor"<?=(($permisos == 'admin,editor')?' seleccionado':'');?>>Admin</option>
          </select>
      </div>
      <div class="form-group col-md-6 text-right" style="margin-top:25px;">
          <a href="users.php" class="btn btn-default">Cancelar</a>
          <input type="submit" value="Add User" class="btn btn-primary">
      </div>
  </form>
<?php  
}

else{
    
$queryUsuario = $db->query("select * from usuarios order by nombre_completo");
?>
<h2>Usuarios</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Agregar nuevo usuario</a>
<hr>
<table class="table table-bordered table-stripped table-condensed">
   <thead><th></th><th>Nombre</th><th>Email</th><th>Unir datos</th><th>Ultimo ingreso</th><th>Permisos</th></thead>
    <tbody>
        <?php while($usuario = mysqli_fetch_assoc($queryUsuario)): ?>
        <tr>
            <td>
                <?php if($usuario['id'] = $datos_usuario['id']): ?>
                <a href="users.php?delete=<?=$usuario['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign"</a>
                <?php endif; ?>
            </td>
            <td><?=$usuario['nombre_completo'];?></td>
            <td><?=$usuario['email'];?></td>
            <td><?=bonita_fecha($usuario['unir_fecha']);?></td>
            <td><?=(($usuario['ultimo_login'] = '0000-00-00 00:00:00')?'Nunca':bonita_fecha($usuario['ultimo_login']));?></td>
            <td><?=$usuario['permisos'];?></td>
        </tr>
    </tbody>
</table>
<?php } include 'includes/footer.php'; ?>