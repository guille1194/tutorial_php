<?php 
require_once '../core/init.php';
if(!esta_conectado()){
    header("Location: login.php");
}
include 'includes/head.php';
include 'includes/navigation.php';

?>
<!-- Ordenes a llenar -->
<?php
$txnQuery = "select t.id, t.id_carrito, t.nombre_completo, t.descripcion, t.fecha_txn, t.gran_total, c.articulos, c.pagado, c.enviado 
from transacciones t
left join carrito c on t.id_carrito = c.id
where c.pagado = 1 and c.enviado = 0
order by t.fecha_txn";
$txnResultados = $db->query($txnQuery);
?>
<div class="col-md-12">
    <h3 class="text-center">Ordenes a enviar</h3>
    <table class="table table-condensed table-bordered table-striped">
    <thead>
        <th></th><th>Nombre</th><th>Descripcion</th><th>Total</th><th>Fecha</th>
    </thead>
    <tbody>
       <?php while($orden = mysqli_fetch_assoc($txnResultados)): ?>
        <tr>
            <td><a href="orders.php?txn_id=<?=$orden['id'];?>" class="btn btn-xs btn-info">Detalles</a></td>
            <td><?=$orden['nombre_completo'];?></td>
            <td><?=$orden['descripcion'];?></td>
            <td><?=dinero($orden['gran_total']);?></td>
            <td><?=bonita_fecha($orden['txn_fecha']);?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
    </table>
</div>

<div class="row">
    <!-- Ventas por mes-->
    <?php
        $esteAño = date("Y");
        $añoPasado = $esteAño - 1;
        $esteAñoQ = $db->query("select gran_total, txn_fecha from transacciones where year(txn_fecha) = '{$esteAño}'");
        $añoPasadoQ = $db->query("select gran_total, txn_fecha from transacciones where year(txn_fecha) = '{$añoPasado}'");
        $actual = array();
        $pasado = array();
        $totalActual = 0;
        $totalPasado = 0;
        while($x = mysqli_fetch_assoc($esteAñoQ)){
            $mes = date("m",strtotime($x['txn_fecha']));
            if(!array_key_exists($mes,$actual)) {
                $actual[(int)$mes] = $x['gran_total'];
            }
            else{
                $actual[(int)$mes] += $x['gran_total'];
            }
            $totalActual += $x['gran_total'];
        }  
        while($y = mysqli_fetch_assoc($añoPasadoQ)){
            $mes = date("m",strtotime($y['txn_fecha']));
            if(!array_key_exists($mes,$actual)) {
                $pasado[(int)$mes] = $y['gran_total'];
            }
            else{
                $pasado[(int)$mes] += $y['gran_total'];
            }
            $totalPasado += $y['gran_total'];
        }     
    ?>
    <div class="col-md-4">
        <h3 class="text-center">Ventas por mes</h3>
        <table class="table table-condensed table-striped table-bordered">
            <thead>
                <th></th>
                <th><?=$añoPasado;?></th>
                <th><?=$esteAño;?></th>
            </thead>
            <tbody>
                <?php for($i = 1;$i <= 12;$i++):
                $fh = DateTime::createFromFormat('!m',$i);
                ?>
                <tr<?=(date("m") == $i)?' class="info"':'';?>>
                    <td><?=$fh->format("F");?></td>
                    <td><?=(array_key_exists($i,$pasado))?dinero($pasado[$i]):dinero(0);?></td>
                    <td><?=(array_key_exists($i,$actual))?dinero($actual[$i]):dinero(0);?></td>
                </tr>
                <?php endfor; ?>
                <tr>
                    <td>Total</td>
                    <td><?=dinero($totalPasado);?></td>
                    <td><?=dinero($totalActual);?></td>
                </tr>
            </tbody>
        </table>
    </div>

<!--Inventario-->
<?php
    $iQuery = $db->query("select * from productos where  eliminado = 0");
    $articulosEscasos = array();
while($producto = mysqli_fetch_assoc($iQuery)){
    $articulo = array();
    $tamaños = tamañosaArray($producto['tamaños']);
    foreach($tamaños as $tamaño){
        if($tamaño['cantidad'] <= $tamaño['limite']){
            $cat = obtener_categoria($producto['categorias']);
            $articulo = array(
                'titulo' => $producto['titulo'],
                'tamaño' => $tamaño['tamaño'],
                'cantidad' => $tamaño['cantidad'],
                'limite' => $tamaño['limite'],
                'categoria' => $cat['padre'] . ' ~ '.$cat['hijo']
            );
            $articulosEscasos[] = $articulo;
        }
    }
}
    ?>
<div class="col-md-8">
    <h3 class="text-center">Bajo inventario</h3>
    <table class="table table-condensed table-striped table-bordered">
        <thead>
            <th>Producto</th>
            <th>Categoria</th>
            <th>Tamaño</th>
            <th>Cantidad</th>
            <th>Limite</th>
        </thead>
        <tbody>
           <?php foreach($articulosEscasos as $articulo): ?>
            <tr<?=($articulo['cantidad'] == 0)?' class="danger"':'';?>>
                <td><?=$articulo['titulo'];?></td>
                <td><?=$articulo['categoria'];?></td>
                <td><?=$articulo['tamaño'];?></td>
                <td><?=$articulo['cantidad'];?></td>
                <td><?=$articulo['limite'];?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

Casa administrador
<?php include 'includes/footer.php'; ?>