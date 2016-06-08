<h3 class="text-center">Articulos populares</h3>
<?php 
$transQ = $db->query("select * from carrito where pagado = 1 order by id desc limit 5");
$resultados = array();
while($fila = mysqli_fetch_assoc($transQ)){
    $resultados[] = $fila;
}
$cuenta_fila = $transQ->num_filas;
$ids_usados = array();
for($i=0;$i<$cuenta_fila;$i++){
    $json_articulos = $resultados[$i]['articulos'];
    $articulos = json_decode($json_articulos,true);
    foreach($articulos as $articulo){
        if(!in_array($articulo['id'], $ids_usados)){
            $ids_usados[] = $articulo['id'];
        }
    }
}
?>


<div id="recent_widget">
    <table class="table table-condensed">
        <?php foreach($ids_usados as $id):
            $productoQ = $db->query("select id,titulo from productos where id = '{$id}'");
            $producto = mysqli_fetch_assoc($productoQ);
        ?>
        <tr>
            <td>
                <?= substr($producto['titulo'],0,15);?>
            </td>
            <td>
                <a class="text-primary" onclick="detailsmodal('<?=$id;?>')">Vista</a>
            </td>
        </tr>
    </table>
</div>