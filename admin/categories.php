<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
if(!esta_conectado()){
    error_redireccion_login();
}
include 'includes/head.php';
include 'includes/navigation.php';

$sql="select * from categorias where padre = 0";
$resultado = $db->query($sql);
$errores = array();
$categoria = '';
$post_padre = '';
//editar categoria
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $editar_id = (int)$_GET['edit'];
    $editar_id = desinfectar($editar_id);
    $editar_sql = "select * from categorias where id = '$editar_id'";
    $editar_resultado = $db->query($editar_sql);
    $editar_categoria = mysqli_fetch_assoc($editar_resultado);
}


//borrar categoria
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $borrar_id = (int)$_GET['delete'];
    $borrar_id = desinfectar($borrar_id);
    $sql = "select * from categorias where id = '$borrar_id'";
    $resultado = $db->query($sql);
    $categoria = mysqli_fetch_assoc($resultado);
    if($categoria['padre'] == 0){
        $sql = "delete from categorias where padre = $borrar_id";
        $db->query($sql);
    }
    $bsql = "delete from categorias where id = '$borrar_id'";
    $db->query($bsql);
    header('Location: categories.php');
}


//forma proceso
if(isset($_POST) && !empty($_POST)){
    $post_padre = desinfectar(isset($_POST['padre']) ? $_POST['padre']: null);
    $categoria = desinfectar(isset($_POST['categoria']) ? $_POST['categoria']: null);
    $sqlforma = "select * from categorias where categoria = '$categoria' and padre = '$post_padre'";
    if(isset($_GET['edit'])){
        $id = $editar_categoria['id'];
        $sqlforma = "select * from categorias where categoria = '$categoria' and padre = '$post_padre' and id != '$id'";
    }
    $fresultado = $db->query($sqlforma);
    $cuenta = mysqli_num_rows($resultado);
       //si la categoria esta en blanco
        if($categoria == ''){
            $errores[] .= 'La categoria no puede estar en blanco';
        }   
    
    //Si existe en la base de datos
    if($cuenta > 0){
        $errores[] .= $categoria. 'ya existe. Por favor elije una nueva categoria.';
    }
    
    //Despliega errores o actualiza la base de datos
    if(!empty($errores)){
    //despliega errores
        $desplegar = $desplegar_errores(errores); ?>
        <script>
            jQuery('document').ready(function(){
                jQuery('#errores').html('<?=desplegar; ?>');
            });
</script>
  <?php } else{
        //actualizar base de datos
        $actualizarsql = "insert into categorias (categoria, padre) values ('$categoria', '$post_padre')";
        if(isset($_GET['edit'])){
            $actualizarsql = "update categorias set categoria = '$categoria', padre = '$post_padre' where id = '$editar_id'";
        }
        $db->query($actualizarsql);
        header('Location: categories.php');
    }
    
}
$valor_categoria = '';
$valor_padre = 0;
if(isset($_GET['edit'])){
    $valor_categoria = $editar_categoria['categoria'];
    $valor_padre = $editar_categoria['padre'];
}
else{
    if(isset($_POST)){
        $valor_categoria = $categoria;
        $valor_padre = $post_padre;
    }
}
?>
<h2 class="text-center">Categorias</h2><hr>
<div class="row">
     
      <!--Forma-->
      <div class="col-md-6">
          <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$editar_id:'');?>" method="post">
             <legend><?=((isset($_GET['edit']))?'Editar':'Agregar una');?> categoria</legend>
             <div id="errors"></div>
              <div class="form-group">
                  <label for="parent">Padre</label>
                  <select class="form-control" name="parent" id="parent">
                      <option value="0"<?=(($valor_padre == 0)?'selected="selected"':'');?>>Padre</option>
                      <?php while($padre = mysqli_fetch_assoc($resultado)) : ?>
                      <option value="<?=$padre['id'];?>"<?=(($valor_padre == $padre['id'])?'selected="selected"':'');?>><?=$padre['categoria'];?></option>
                      <?php endwhile; ?>
                  </select>
              </div>
              <div class="form-group">
                  <label for="category">Categoria</label>
                  <input type="text" class="form-control" id="category" name="category" value="<?=$valor_categoria;?>">
              </div>
              <div class="form-group">
                  <input type="submit" value="<?= ((isset($_GET['edit']))?'Editar':'Agregar'); ?> Categoria" class="btn btn-success">
              </div>
          </form>
      </div>
      
       <!--Tabla categoria-->
        <div class="col-md-6">
            <table class="table table-bordered">
               <thead>
                <th>Categoria</th><th>Padre</th><th></th>
                </thead>
                <tbody>
                   <?php 
                    $sql="select * from categorias where padre = 0";
                    $resultado = $db->query($sql);
                    while($padre = mysqli_fetch_assoc($resultado)): 
                    $padre_id = (int)$padre['id'];
                    $sql2 = "select * from categorias where padre = $padre_id";
                    $hresultado = $db->query($sql2);
                    ?>
                    <tr class="bg-primary">
                        <td><?=$padre['categoria']; ?></td>
                        <td>Padre</td>
                        <td>
                            <a href="categories.php?edit=<?=$padre['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                              <a href="categories.php?delete=<?=$padre['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                        </td>
                    </tr>
                    <?php while($hijo = mysqli_fetch_assoc($hresultado)): ?>
                    <tr class="bg-info">
                        <td><?=$hijo['categoria']; ?></td>
                        <td><?=$padre['categoria']; ?></td>
                        <td>
                            <a href="categories.php?edit=<?=$hijo['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                              <a href="categories.php?delete=<?=$hijo['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
</div>
<?php include 'includes/footer.php'; ?>