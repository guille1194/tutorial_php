<?php 
    require_once 'core/init.php';
    include 'includes/head.php'; 
    include 'includes/navigation.php';
    include 'includes/headerpartial.php';
    include 'includes/leftbar.php';

if(isset($_GET['cat'])){
    $cat_id = desinfectar($_GET['cat']);
}

else{
    $cat_id = '';
}

    $sql = "select * from productos where categorias = '$cat_id'";
    $productoQ = $db->query($sql);
    $categoria = obtener_categoria($cat_id);
?>
    
    <!-- contenido principal o main -->
    <div class="col-md-8">
    <div class="row">
        <h2 class="text-center"><?=$categoria['padre']. ' ' . $categoria['hijo'];?></h2>
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