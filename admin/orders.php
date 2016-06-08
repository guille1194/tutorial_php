<?php
    require_once '../core/init.php';
    if(!is_logged_in()){
        header('Location login.php');
    }
include 'includes/head.php';
include 'includes/navigation.php';

//orden completa
if(isset($_GET['completo'])  && $_GET['completo'] == 1){
    $id_carrito = desinfectar((int)$_GET['id_carrito']);
    $db->query("update carrito set enviado = 1 where id = '{$id_carrito}'");
    $_SESION[flash_exito] = "La orden ha sido completa";
    header('Location: index.php');
}

$txn_id = desinfectar((int)$_GET['txn_id']);
$txnQuery = $db->query("select * from transacciones where id = '{$txn_id}'");
$txn = mysqli_fetch_assoc($txnQuery);
$id_carrito = $txn['id_carrito'];
$carritoQ = $db->query("select * from carrito where id = '{$id_carrito}'");
$carrito = mysqli_fetch_assoc($carritoQ);
$articulos = json_decode($carrito['articulos'],true);
$idArray[] = $articulo['id'];
}
$ids = implode(',',$idArray);
$productoQ = $db->query{
    "select i.id as 'id', i.titulo as 'titulo', c.id as 'cid', c.categoria as 'hijo', p.categoria as 'padre'
    from productos i
    left join categorias c on i.categorias = c.id
    left join categorias p on c.padre = p.id
    where i.id in ({$IDS})
    ");
    while($p = mysqli_fetch_assoc($productoQ)){
        
        foreach($articulos as $articulo){
            if($articulo['id'] == $p['id']){
                $x = $articulo;
                continue;
            }
        }
        $productos[] = array_merge($x, $p);
    }
?>
<h2 class="text-center">Articulos ordenados</h2>
<table class="table table-condensed table-bordered table-striped">
    <thead>
        <th>Cantidad</th>
        <th>Titulo</th>
        <th>Categoria</th>
        <th>Tamano</th>
    </thead>
    <tbody>
        <?php foreach($productos as $producto): ?>
        <tr>
            <td><?=$producto['cantidad'];?></td>
            <td><?=$producto['titulo'];?></td>
            <td><?=$producto['padre'].' ~ '.$producto['hijo'];?></td>
            <td><?=$producto['tamano'];?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

<div class="row">
    <div class="col-md-6">
        <h3  class="text-center">Detalles de orden</h3>
        <table class="table table-condensed table-striped table-bordered">
            <tbody>
                <tr>
                    <td>Subtotal</td>
                    <td><?=dinero($txn['subtotal']);?></td>
                </tr>
                <tr>
                    <td>Impuesto</td>
                    <td><?=dinero($txn['impuesto']);?></td>
                </tr>
                <tr>
                    <td>Gran total</td>
                    <td><?=dinero($txn['gran total']);?></td>
                </tr>
                <tr>
                    <td>Fecha de orden</td>
                    <td><?=bonita_fecha($txn['fecha_orden']);?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h3 class="text-center">Direccion de compra</h3>
        <address>
            <?=$txn['nombre_completo'];?><br>
            <?=$txn['calle'];?><br>
            <?=$txn['calle2'] != '')?txn['calle2'].'<br>':'';?>
            <?=$txn['ciudad'].', '.$txn['estado'].' '.$txn['codigo_postal'];?><br>
            <?=$txn['pais'];?><br>
        </address>
    </div>
</div>
<div class="pull-right">
    <a href="index.php" class="btn btn-large btn-default">Cancelar</a>
    <a href="orders.php?complete=1&cart_id=<?=$id_carrito;?>" class="btn btn-primary btn-large">Orden completa</a>
</div>
<?php include 'includes/footer.php'; ?>