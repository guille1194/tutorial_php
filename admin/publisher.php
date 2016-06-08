<?php 
require_once '../core/init.php';
if(!esta_conectado()){
    error_redireccion_login();
}
include 'includes/head.php';
include 'includes/navigation.php';
//obtener publishers desde la base de datos
$sql = "select * from publisher order by publisher";
$resultados = $db->query($sql);
$errores = array();

//eidtar publisher
if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $editar_id = (int)$_GET['edit'];
    $editar_id = desinfectar($editar_id);
    $sql2 = "select * from publisher where id = '$editar_id'";
    $editar_resultado = $db->query($sql2);
    $ePublisher = mysqli_fetch_assoc($editar_resultado);
}

//borrar publisher
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $borrar_id = (int)$_GET['delete'];
    $borrar_id = desinfectar($borrar_id);
    $sql = "delete from publisher where id = '$borrar_id'";
    $db->query($sql);
    header('Location: publisher.php');
}

//si se agrega una forma
if(isset($_POST['add_submit'])){
    $publisher = desinfectar($_POST['publisher']);
    //observar si publisher esta en blanco
    if($_POST['publisher'] == ''){
        $errores[] .= 'Debes ingresar un publisher';
}
  //observar si publisher existe en la base de datos
    $sql = "select * from publisher where publisher = '$publisher'";
    if(isset($_GET['edit'])){
        $sql = "select * from publisher where publisher = '$publisher' and id != '$editar_id'";
    }
    $resultado = $db->query($sql);
    $cuenta = mysqli_num_rows($resultado);
    if ($cuenta > 0){
        $errores[] .= $publisher.' ya existe. Por favor elije otro nombre de publisher';
    }
    
    //desplegar errores
    if(!empty($errores)){
        echo desplegar_errores($errores);
    }
    else{
        //agregar publisher a la base de datos
        $sql = "insert into publisher (publisher) values ('$publisher')";
        if(isset($_GET['edit'])){
            $sql = "update publisher set publisher = '$publisher' where id = 'editar_id'";
        }
        $db->query($sql);
        header('Location: publisher.php');
    }
}
?>

<h2 class="text-center">Publishers</h2><hr>
<!--Forma publishers-->
<div class="text-center">
    <form class="form-inline" action="publisher.php<?=((isset($_GET['edit']))?'?edit='.$editar_id:'');?>" method="post">
        <div class="form-group">
           <?php 
    $publisher_value = '';                                        
    if(isset($_GET['edit'])){
    $publisher_valor = $ePublisher['publisher'];
}
        else{
            if(isset($_POST['publisher'])){
                $publisher_valor = desinfectar($_POST['publisher']);
            }
        } ?>
            <label for="publisher"><?=((isset($_GET['edit']))?'Editar':'Agregar a'); ?> publisher:</label>
            <input type="text" name="publisher" id="publisher" class="form-control" value="<?=$publisher_valor; ?>">
            <?php if(isset($_GET['edit'])): ?>
            <a href="publisher.php" class="btn btn-default">Cancelar</a>
            <?php endif; ?>
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Editar':'Agregar');?> publisher" class="btn btn-success">
        </div>
    </form>
</div><hr>

<table class="table table-bordered table-striped table-auto table-condensed">
    <thead>
        <th></th><th>Publisher</th><th></th>
    </thead>
    <tbody>
       <?php while($publisher = mysqli_fetch_assoc($resultados)): ?>
        <tr>
            <td><a href="publisher.php?edit=<?=$publisher['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><?=$publisher['publisher'];?></td>
            <td><a href="publisher.php?delete=<?=$publisher['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include 'includes/footer.php'; ?>