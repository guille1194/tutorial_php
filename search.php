<?php 
    require_once 'core/init.php';
    include 'includes/head.php'; 
    include 'includes/navigation.php';
    include 'includes/headerpartial.php';
    include 'includes/leftbar.php';

    $sql = "select * from productos";
    $cat_id = (($_POST['cat'] != '')?desinfectar($_POST['cat']):'');
    if ($cat_id == ''){
        $sql .= 'where eliminado = 0';   
    }
    else{
        $sql .= "where categorias = '{$cat_id}' and eliminado = 0"; 
    }
    $ordenar_precio = (($_POST['ordenar_precio'] != '')?desinfectar($_POST['ordenar_precio']):'');
    $precio_min = (($_POST['precio_min'] != '')?desinfectar($_POST['precio_min']):'');
    $precio_min = (($_POST['precio_max'] != '')?desinfectar($_POST['precio_max']):'');
    if($precio_min != ''){
        $sql .=  "and precio >= '{$precio_min}'";
    }
    if($precio_max != ''){
        $sql .=  "and precio <= '{$precio_max}'";
    }
    if($publisher != ''){
        $sql .=  "and publisher >= '{$publisher}'";
    }
    if($precio_min != ''){
        $sql .=  "and precio >= '{$precio_min}'";
    }
    if($ordenar_precio == 'bajo'){
        $sql .=  "order by precio";
    }
    if($ordenar_precio == 'alto'){
        $sql .=  "order by precio desc";
    }
    $productoQ = $db->query($sql);
    $categoria = obtener_categoria($cat_id);
?>
    
    <!-- contenido principal o main -->
    <div class="col-md-8">
    <div class="row">
       <?php if($cat_id != ''): ?>
       <h2 class="text-center"<?=$categoria['padre'].''. $categoria['hijo'];?>></h2>
       <?php else: ?>
      <h2 class="text-center">Payne's store</h2>
       <?php endif; ?>
        <?php while($producto = mysqli_fetch_assoc($productoQ)) : ?>
        <div class="col-md-3 text-center">
            <h4><?= $producto['titulo']; ?></h4>
            <?php $fotos = explode(',',$producto['imagen']); ?>
            <img src="<?= $fotos[0]; ?>" alt= "<?= $producto['titulo']; ?>" class="img-thumb" />
            <p class="list-price text-danger">Lista de precios<s>$<?= $producto['lista_precios']; ?></s></p>
            <p class="price">Nuestro precio: $<?= $producto['precio']; ?></p>
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" onclick="detailsmodal(<?= $producto['id']; ?>)">Detalles</button>
        </div>
        <?php endwhile; ?>
        </div>
        </div>
   
<?php
    include 'includes/rightbar.php';
    include 'includes/footer.php';
?>