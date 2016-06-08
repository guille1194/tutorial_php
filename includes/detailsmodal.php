 <?php
require_once '../tutorial/core/init.php';
$id = isset($_POST['id']) ? $_POST['id'] : '';
$id = (int)$id;
$sql = "select * from productos where id = '$id'";
$resultado = $db->query($sql);
$producto = mysqli_fetch_assoc($resultado);
$publisher_id = $producto['publisher'];
$sql = "select publisher from publisher where id = 'publisher_id'";
$publisher_query = $db->query($sql);
$publisher = mysqli_fetch_assoc($publisher_query);
$tamañostring = $producto['tamaños'];
$tamañostring = rtrim($tamañostring,',');
$tamaño_array = explode(',', $tamañostring);
?>
   
       <!--ventana detalles-->
   <?php ob_start(); ?>
   
    <div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labeledby="details-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" onclick="closeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title text-center"><?=$producto['titulo']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                       <span id="modal_errors" class="bg-danger"></span>
                        <div class="col-sm-6">
                            <?php $fotos = explode(',',$producto['imagen']);
                            foreach($fotos as $foto): ?>
                           <img src="<?=$foto; ?>"alt="<?=$producto['titulo']; ?>" class="details img-responsive">
                            <?php endforeach; ?>
                        <div class="col-sm-6">
                        <h4>Detalles</h4> 
                        <p><?= nl2br($producto['descripcion']); ?></p>
                        <hr>
                        <p>Precio: $<?=$producto['precio']; ?> </p>
                        <p>Publisher: <?=$publisher['publisher']; ?></p>
                        <form action="add_cart.php" method="post" id="add_product_form">
                          <input type="hidden" name="product_id" value="<?=$id;?>">
                           <input type="hidden" name="available" id="available" value="">
                            <div class="form-group">
                                <div class="col-xs-3">
                                    <label for="quantity">Cantidad</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="0">
                                </div><br><div class="col-xs-9">&nbsp;</div>
                            </div><br><br>
                            <div class="form-group">
                                <label for="size">Tamaño:</label>
                                <select name="size" id="size" class="form-control">
                                    <option value=""></option>
                                    <?php foreach($tamaño_array as $string) {
                                    $tamaño_array = explode(':', $string);
                                    $tamaño = $string_array[0];
                                    $disponible = $string_array[1];
                                    if($disponible > 0){
                                    echo '<option value="'.$tamaño'" data-available="'.$disponible.'">'.$tamaño.' ('.$disponible.' Disponible)</option>';
                                    }
                                    } ?>
                                </select>
                            </div>
                        </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" onclick="closeModal()">Cerrar</button>
                    <button class="btn btn-warning" onclick="agregar_al_carrito(); return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Agregar al carro</button>
                </div>
            </div>
            </div>
        </div>
    </div>
    <script>
        
    jQuery('#tamaño').change(function(){
        var disponible = jQuery('#tamaño option:selected').data("disponible");
        });
        jQuery('#disponible').val(disponible);
        
        $(function () {
  $('.fotorama').fotorama({'loop':true,'autoplay':true});
});
        
     function closeModal(){
         jQuery('#details-modal').modal('hide');
         setTimeout(function(){
            jQuery('#details-modal').remove();
            jQuery('.modal-backdrop').remove();
         },500);
     }
</script>
    <?php echo ob_get_clean(); ?>