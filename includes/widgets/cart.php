<h2 class="text-center">Carrito de compras</h2>
<div>
    <?php if(empty($id_carrito)): ?>
    <p>Tu carrito de compras esta vacio</p>
    <?php else: 
        $carritoQ = $db->query("select * from carrito where id = '{$id_carrito}'");
        $resultados = mysqli_fetch_assoc($carritoQ);
        $articulos = json_decode($resultados['articulos'], true);
        $subtotal = 0;
    ?>
    <table class="table table-condensed" id="cart_widget">
        <tbody>
            <?php foreach($articulos as $articulo):
                $productoQ = $db->query("select * from productos where id = '{$articulos['id']}'");
                $producto = mysqli_fetch_assoc($productoQ);
            ?>
            <tr>
                <td><?=$articulo['cantidad'];?></td>
                <td><?=substr($producto['titulo'],0,13);?></td>
                <td><?=dinero($articulo['cantidad'] * $producto['precio']);?></td>
            </tr>
            <?php 
                $subtotal += ($articulo['cantidad'] * $producto['precio']);    
            endforeach; ?>
            <tr>
                <td></td>
                <td>Subtotal</td>
                <td><?=dinero($subtotal); ?></td>
            </tr>
        </tbody>
    </table>
    <a href="cart.php" class="btn btn-xs btn-primary pull-right">Ver carrito</a>
    <div class="clearfix"></div>
    <?php endif; ?>
</div>