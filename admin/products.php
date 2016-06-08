<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
if(!esta_conectado()){
    error_redireccion_login();
}
include 'includes/head.php';
include 'includes/navigation.php';
 
//Eliminar Producto
if(isset($_GET['delete'])){
  $id = desinfectar($_GET['delete']);
  $db->query("UPDATE productos SET eliminado = 1 WHERE id = '$id'");
  header('Location: products.php');
}
 
$dbcamino = '';
if (isset($_GET['add']) || isset($_GET['edit'])){
$Query = $db->query("SELECT * FROM publisher ORDER BY publisher");
$padreQuery = $db->query("SELECT * FROM categorias WHERE padre = 0 ORDER BY categoria");
$titulo = ((isset($_POST['titulo']) && $_POST['titulo'] != '')?desinfectar($_POST['titulo']):'');
$publisher = ((isset($_POST['publisher']) && !empty($_POST['publisher']))?desinfectar($_POST['publisher']):'');
$padre = ((isset($_POST['padre']) && !empty($_POST['padre']))?desinfectar($_POST['padre']):'');
$categoria = ((isset($_POST['hijo'])) && !empty($_POST['hijo'])?desinfectar($_POST['hijo']):'');
$precio = ((isset($_POST['precio']) && $_POST['precio'] != '')?desinfectar($_POST['precio']):'');
$lista_precios = ((isset($_POST['lista_precios']) && $_POST['lista_precios'] != '')?desinfectar($_POST['lista_precios']):'');
$descripcion = ((isset($_POST['descripcion']) && $_POST['descripcion'] != '')?desinfectar($_POST['descripcion']):'');
$tamaños = ((isset($_POST['tamaños']) && $_POST['tamaños'] != '')?desinfectar($_POST['tamaños']):'');
$tamaños = rtrim($tamaños,',');
$imagen_guardada = '';
 
if(isset($_GET['edit'])){
  $editar_id = (int)$_GET['edit'];
  $resultadosProducto = $db->query("SELECT * FROM productos WHERE id = '$editar_id'");
  $producto = mysqli_fetch_assoc($resultadosProducto);
  if(isset($_GET['borrar_imagen'])){
    $imgi = (int)$_GET['imgi'] - 1;
    $imagenes = explode(',',$product['imagen']);
    $url_imagen = $_SERVER['DOCUMENT_ROOT'].$imagenes[$imgi];
    unlink($url_imagen);
    unset($imagenes[$imgi]);
    $stringImagen = implode(',',$imagenes);
    $db->query("UPDATE productos SET imagen = '{$stringImagen}' WHERE id = '$editar_id'");
    header('Location: products.php?edit='.$editar_id);
  }
  $categoria = ((isset($_POST['hijo']) && $_POST['hijo'] != '')?desinfectar($_POST['hijo']):$producto['categorias']);
  $titulo = ((isset($_POST['titulo']) && $_POST['titulo'] != '')?desinfectar($_POST['titulo']):$producto['titulo']);
  $publisher = ((isset($_POST['publisher']) && $_POST['publisher'] != '')?desinfectar($_POST['publisher']):$producto['publisher']);
  $padreQ = $db->query("SELECT * FROM categorias WHERE id = '$categoria'");
  $resultadoPadre = mysqli_fetch_assoc($padreQ);
  $padre = ((isset($_POST['padre']) && $_POST['padre'] != '')?desinfectar($_POST['padre']):$resultadoPadre['padre']);
  $precio = ((isset($_POST['precio']) && $_POST['precio'] != '')?desinfectar($_POST['precio']):$producto['precio']);
  $lista_precios = ((isset($_POST['lista_precios']))?desinfectar($_POST['lista_precios']):$producto['lista_precios']);
  $descripcion = ((isset($_POST['descripcion']))?desinfectar($_POST['descripcion']):$producto['descripcion']);
  $tamaños = ((isset($_POST['tamaños']) && $_POST['tamaños'] != '')?desinfectar($_POST['tamaños']):$product['tamaños']);
  $tamaños = rtrim($tamaños,',');
  $imagen_guardada = (($producto['imagen'] != '')?$producto['imagen']:'');
  $dbcamino = $imagen_guardada;
}
if (!empty($tamaños)) {
  $tamañoString = desinfectar($tamaños);
  $tamañoString = rtrim($tamañoString,',');
  $tamañosArray = explode(',',$tamañoString);
  $tArray = array();
  $cArray = array();
  foreach($tamañosArray as $ts){
    $t = explode(':', $ts);
    $tArray[] = $t[0];
    $cArray[] = $c[1];
  }
}else{$tamañosArray = array();}
 
if ($_POST) {
  $errores= array();
  $necesario = array('titulo', 'publisher', 'precio', 'padre', 'hijo', 'cantidad');
  foreach($necesario as $campo){
    if($_POST[$campo] == ''){
      $errores[] = 'Todos los campos con asterisco son necesarios.';
      break;
    }
  }
  $cuentaFoto = count($_FILES['foto']['nombre']);
   if ($cuentaFoto > 0) {
     for($i=0;$i<$cuentaFoto;$i++){
      $nombre = $_ARCHIVOS['foto']['nombre'][$i];
      $nombreArray = explode('.',$nombre);
      $nombreArchivo = $nombreArray[0];
      $extArchivo = $nombreArray[1];
      $mimo = explode('/',$_FILES['foto']['tipo'][$i]);
      $tipoMimo = $mimo[0];
      $extMimo = $mimo[1];
      $ubiTmp[] = $_ARCHIVOS['foto']['nombre_tmp'][$i];
      $tamañoArchivo = $_ARCHIVOS['foto']['tamaño'][$i];
      $cargarNombre = md5(microtime()).'.'.$extArchivo;
      $cargarCamino[] = BASEURL.'images/products/'.$cargarNombre;
      if($i != 0 && $i < $cuentaFoto){
        $dbcamino .= ',';
      }
      $dbcamino .= '/tutorial/images/products/'.$cargarNombre;
      if ($tipoMimo != 'imagen') {
        $errores[] = 'El archivo necesita ser una imagen.';
      }
      if (!in_array($extArchivo, $permitido)) {
        $errores[] = 'La extension del archivo debe ser png, jpg, jpeg, or gif.';
      }
      if ($tamañoArchivo > 15000000) {
        $errores[] = 'Los archivos deben pesar menos de 15MB.';
      }
      if ($extArchivo != $extMimo && ($extMimo == 'jpeg' && $extArchivo != 'jpg')) {
        $errores[] = 'La extension no coincide con el archivo.';
      }
    }
   }
  if(!empty($errores)){
    echo desplegar_errores($errores);
  }else{
    //cargar archivo e insertar en la base de datos
      if(!empty($_ARCHIVOS)){
        move_uploaded_file($ubiTmp,$cargarCamino);
      }
    $insertarSql = "INSERT INTO productos (`titulo`,`precio`,`lista_precios`,`publisher`,`categorias`,`cantidad`,`imagen`,`descripcion`)
     VALUES ('$titulo','$precio', '$lista_precios', '$publisher', '$categoria','$cantidad','$dbcamino','$descripcion')";
     if(isset($_GET['edit'])){
       $insertarSql = "UPDATE productos SET `titulo` = '$titulo', `precio` = '$precio', `lista_precios` = '$lista_precios',
       `publisher` = '$publisher', `categorias` = '$categorias', `cantidad` = '$cantidad', `imagen` = '$dbcamino', `descripcion` = '$descripcion'
       WHERE id ='$editar_id'";
     }
 
     $db->query($insertarSql);
     header('Location: products.php');
  }
}
 
?>
  <h2 class="text-center"><?=((isset($_GET['edit']))?'Editar':'Agregar un nuevo');?> Producto</h2><hr>
  <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$editar_id:'add=1');?>" method="POST" enctype="multipart/form-data">
    <div class="form-group col-md-3">
      <label for="title">Titulo*:</label>
      <input type="text" name="title" class="form-control" id="title" value="<?=$titulo;?>">
    </div>
    <div class="form-group col-md-3">
      <label for="publisher">Publisher*:</label>
      <select class="form-control" id="publisher" name="brand">
        <option value=""<?=(($publisher == '')?' seleccionado':'');?>></option>
        <?php while($p = mysqli_fetch_assoc($publisherQuery)): ?>
          <option value="<?=$p['id'];?>"<?=(($publisher == $p['id'])?' seleccionado':'');?>><?=$p['publisher'];?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="form-group col-md-3">
      <label for="parent">Categoria Padre*:</label>
      <select class="form-control" id="parent" name="parent">
        <option value=""<?=(($padre == '')?' seleccionado':'');?>></option>
        <?php while($pa = mysqli_fetch_assoc($padreQuery)): ?>
          <option value="<?=$pa['id'];?>"<?=(($padre == $pa['id'])?' seleccionado':'');?>><?=$pa['categoria'];?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="form-group col-md-3">
      <label for="child">Categoria hijo*:</label>
      <select id="child" name="child" class="form-control">
      </select>
    </div>
    <div class="form-group col-md-3">
      <label for="price">Precio*:</label>
      <input type="text" id="price" name="price" class="form-control" value="<?=$precio;?>">
    </div>
    <div class="form-group col-md-3">
      <label for="list_price">Lista Precios:</label>
      <input type="text" id="list_price" name="list_price" class="form-control" value="<?=$lista_precios;?>">
    </div>
    <div class="form-group col-md-3">
      <label>Cantidad y Tamaños*:</label>
      <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Cantidad y Tamaños</button>
    </div>
    <div class="form-group col-md-3">
      <label for="sizes">Preview Cantidad y tamaños</label>
      <input type="text" class="form-control" name="sizes" id="sizes" value="<?=$tamaños;?>" readonly>
    </div>
    <div class="form-group col-md-6">
      <?php if($imagen_guardada != ''): ?>
        <?php
        $imgi = 1;
        $imagenes = explode(',',$imagen_guardada); ?>
          <?php foreach($imagenes as $imagen):?>
            <div class="saved-image col-md-4">
              <img src="<?=$imagen;?>" alt="saved image"/><br>
              <a href="products.php?delete_image=1&edit=<?=$editar_id;?>&imgi=<?=$imgi;?>" class="text-danger">Imagen eliminada</a>
            </div>
          <?php $imgi++; endforeach;?>
      <?php else: ?>
        <label for="photo">Foto del producto:</label>
        <input type="file" name="photo[]" id="photo" class="form-control" multiple>
      <?php endif; ?>
    </div>
    <div class="form-group col-md-6">
      <label for="description">Descripcion:</label>
      <textarea id="description" name="description" class="form-control" rows="6"><?=$descripcion;?></textarea>
    </div>
    <div class="form-group pull-right">
      <a href="products.php" class="btn btn-default">Cancelar</a>
      <input type="submit" value="<?=((isset($_GET['edit']))?'Editar':'Agregar');?> Producto" class="btn btn-success">
    </div><div class="clearfix"></div>
  </form>
  <!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Cantidad y tamaños</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
        <?php for($i=1;$i <= 1;$i++): ?>
           <div class="form-group col-md-2">
            <label for="size<?=$i;?>">Tamaño: </label>
            <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($tArray[$i-1]))?$tArray[$i-1]:'');?>" class="form-control">
          </div>
          <div class="form-group col-md-2">
            <label for="qty<?=$i;?>">Cantidad: </label>
            <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($cArray[$i-1]))?$cArray[$i-1]:'');?>" min="0" class="form-control">
          </div>
        <?php endfor; ?>
      </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="actualizarTamaños();jQuery('#sizesModal').modal('toggle');return false;">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>
<?php }else{
$sql = "SELECT * FROM productos WHERE eliminado = 0 ORDER BY categorias";
$presultados = $db->query($sql);
if (isset($_GET['featured'])) {
  $id = (int)$_GET['id'];
  $featured = (int)$_GET['featured'];
  $featuredSql = "UPDATE productos SET featured = '$featured' WHERE id = '$id'";
  $db->query($featuredSql);
  header('Location: products.php');
}
 ?>
<h2 class="text-center">Productos</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Agregar Producto</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped">
  <thead><th></th><th>Producto</th><th>Precio</th><th>Categoria</th><th>Featured</th><th>Vendido</th></thead>
  <tbody>
    <?php while($producto = mysqli_fetch_assoc($presultados)):
        $hijoID = $producto['categorias'];
        $catSql = "SELECT * FROM categorias WHERE id = '$hijoID'";
        $resultado = $db->query($catSql);
        $hijo = mysqli_fetch_assoc($resultado);
        $padreID = $hijo['padre'];
        $paSql =  "SELECT * FROM categorias WHERE id = '$padreID'";
        $presultado = $db->query($paSql);
        $padre = mysqli_fetch_assoc($presultado);
        $categoria = $padre['categoria'].'~'.$hijo['categoria'];
      ?>
      <tr>
        <td>
          <a href="products.php?edit=<?=$producto['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="products.php?delete=<?=$producto['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
 
        </td>
        <td><?=$producto['titulo'];?></td>
        <td><?=dinero($producto['precio']);?></td>
        <td><?=$categoria;?></td>
        <td><a href="products.php?featured=<?=(($producto['featured'] == 0)?'1':'0');?>&id=<?=$producto['id'];?>" class="btn btn-xs btn-default">
          <span class="glyphicon glyphicon-<?=(($producto['featured']==1)?'minus':'plus');?>"></span>
          </a>&nbsp <?=(($producto['featured'] == 1)?'Featured Product':'');?></td>
        <td>0</td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
 
<?php } include 'includes/footer.php'; ?>
<script>
  jQuery('document').ready(function(){
    obtener_opc_hijo('<?=$categoria;?>');
  });
</script>