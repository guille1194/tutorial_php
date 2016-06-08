<?php
    $cat_id = ((isset($_PETICION['cat']))?desinfectar($_PETICION['cat']):'');
    $ordenar_precio = ((isset($_PETICION['ordenar_precio']))?desinfectar($_PETICION['ordenar_precio']):'');
    $precio_min = ((isset($_PETICION['precio_min']))?desinfectar($_PETICION['precio_min']):'');
    $precio_max = ((isset($_PETICION['precio_max']))?desinfectar($_PETICION['precio_max']):'');
    $p = ((isset($_PETICION['publisher']))?desinfectar($_PETICION['publisher']):'');
    $publisherQ = $db -> query("select * from publisher order by publisher");
?>
<h3 class="text-center">Buscar por:</h3>
<h4 class="text-center">Precio</h4>
<form action="search.php" method="post">
    <input type="hidden" name="cat" value="<?=$cat_id;?>">
    <input type="hidden" name="cat" value="0">
    <input type="radio" name="price_sort" value="low"<?=(($ordenar_precio == 'bajo')?' comprobado':'');?>>Bajo a alto<br>
    <input type="radio" name="price_sort" value="high"<?=(($ordenar_precio == 'alto')?' comprobado':'');?>>Alto a bajo<br>
    <input type="text" name="min_price" class="price-range" placeholder = "Min $" value="<?=$precio_min;?>">A
    <input type="text" name="min_price" class="price-range" placeholder = "Max $" value="<?=$precio_max;?>"><br><br>
    <h4 class="text-center">Publisher</h4>
    <input type="radio" name="publisher" value=""<?=(($p == '')?' comprobado':'');?>>Todo<br>
    <?php while($publisher = mysqli_fetch_assoc($publisherQ)): ?><br>
    <input type="radio" name="publisher" value=""<?=(($p == '')?' comprobado':'');?>><?=$publisher['publisher'];?>
    <?php endwhile; ?>
    <input type="submit" value="Search" class="btn btn-xs btn-primary">
</form>