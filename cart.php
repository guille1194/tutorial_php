<?php
    require_once 'core/init.php';
    include 'includes/head.php';
    include 'includes/navigation.php';
    include 'includes/headerpartial.php';

    if($id_carrito != ''){
        $carritoQ = $db->query("select * from carrito where id = '{$id_carrito}'");
        $resultado = mysqli_fetch_assoc($carritoQ);
        $articulos = json_decode($resultado['articulos'],true);var_dump($articulos); 
        $i = 1;
        $subtotal = 0;
        $cuenta_articulo = 0;
    }
?>

<div class="col-md-12">
    <div class="row">
        <h2 class="text-center">Mi carrito de compras</h2><hr>
        <?php if($id_carrito == ''): ?>
        <div class="bg-danger">
            <p class="text-center text-danger">
                Tu carro de compras esta vacio!
            </p>
        </div>
            <?php else: ?>
            <table class="table table-bordered table-condensed table-stripped">
                <thead><th>#</th><th>Articulo</th><th>Precio</th><th>Cantidad</th><th>Tamaño</th><th>Subtotal</th></thead>
                <tbody>
                   <?php
                    foreach($articulos as $articulo){
                        $producto_id = $articulo['id'];
                        $productoQ = $db->query("select * from productos where id = '{$producto_id}'");
                        $producto = mysqli_fetch_assoc($productoQ);
                        $tArray - explode(',',$producto['tamaños']);
                        foreach($tArray as $tamañoString);
                        $t = explode(':',$tamañoString);
                        if($t[0] == $articulo['tamaño']){
                            $disponible = $t[1];
                        }
                    }
                    ?>
                    <tr>
                        <td><?=$i;?></td>
                        <td><?=$producto['titulo'];?></td>
                        <td><?=dinero($producto['precio']);?></td>
                        <td>
                        <button class="btn btn-xs btn-default" onclick="actualizar_carrito('quitaruno','<?=$producto['id'];?>','<?=$articulo['tamaño'];?>');"></button>
                        <?=$articulo['cantidad'];?>
                        <?php if($articulo['cantidad'] < $disponible): ?>
                        <button class="btn btn-xs btn-default" onclick="actualizar_carrito('agregaruno','<?=$producto['id'];?>','<?=$articulo['tamaño'];?>');"></button>
                        <?php else: ?>
                        <span class="text-danger">Maximo encontrado</span>
                        <?php endif; ?>
                        </td>
                        <td><?=$articulo['tamaño'];?></td>
                        <td><?=dinero($articulo['cantidad'] * $articulo['precio']);?></td>
                    </tr>
                    <?php
                    $i++
                    $cuenta_articulo += $articulo['cantidad'];
                    $subtotal += ($producto['precio'] * $articulo['cantidad']);
                }
                $impuesto = TAXRATE * $subtotal;
                $impuesto = number_format($impuesto,2);
                $gran_total = $impuesto + $subtotal;
?>
                </tbody>
            </table>
            <table class="table table-bordered table-condensed text-right">
               <legend>Total</legend>
                <thead class="totals-table-header"><th>Articulos totales</th><th>Subtotal</th><th>Impuesto</th><th>Total</th></thead>
                <tbody>
                    <tr>
                        <td><?=$cuenta_articulo;?></td>
                        <td><?=dinero($subtotal);?></td>
                        <td><?=dinero($impuesto);?></td>
                        <td class="bg-success"><?=dinero($gran_total);?></td>
                    </tr>
                </tbody>
            </table>
            <!-- Button trigger modal -->
<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
<span class="glyphicon glyphicon-shopping-cart"></span>  revisar
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="checkoutModalLabel">Direccion envio</h4>
      </div>
      <div class="modal-body">
       <div class="row">
        <form action="thankYou.php" method="post" id="payment-form">
           <span class="bg-danger" id="payment-errors"></span>
           <input type="hidden" name="tax" value="<?=$impuesto;?>">
           <input type="hidden" name="sub_total" value="<?=$subtotal;?>">
           <input type="hidden" name="grand_total" value="<?=$gran_total;?>">
           <input type="hidden" name="cart_id" value="<?=$id_carrito;?>">
           <input type="hidden" name="description" value="<?=$cuenta_articulo.' articulo'.(($cuenta_articulo>1)?'s':'').' desde Payne Store.';?>">
            <div id="step1" style="display:block;">
                <div class="from-group col-md-6">
                    <label for="full_name">Nombre completo:</label>
                    <input class="form-control" id="full_name" name="full_name" type="text">
                </div>
                <div class="from-group col-md-6">
                    <label for="email">Email:</label>
                    <input class="form-control" id="email" name="email" type="email">
                </div>
                <div class="from-group col-md-6">
                    <label for="street">Direccion calle:</label>
                    <input class="form-control" id="street" name="street" type="text" data-stripe="address_line1">
                </div>
                <div class="from-group col-md-6">
                    <label for="street2">Direccion calle 2:</label>
                    <input class="form-control" id="street2" name="street2" type="text" data-stripe="address_line2">
                </div>
                <div class="from-group col-md-6">
                    <label for="city">Ciudad:</label>
                    <input class="form-control" id="city" name="city" type="text" data-stripe="address_city">
                </div>
                <div class="from-group col-md-6">
                    <label for="state">Estado:</label>
                    <input class="form-control" id="state" name="state" type="text" data-stripe="address_state">
                </div>
                <div class="from-group col-md-6">
                    <label for="zip_code">Codigo postal:</label>
                    <input class="form-control" id="zip_code" name="zip_code" type="text" data-stripe="address_zip">
                </div>
                <div class="from-group col-md-6">
                    <label for="country">Pais:</label>
                    <input class="form-control" id="country" name="country" type="text" data-stripe="address_country">
                </div>
            </div>
            <div id="step2" style="display:none;">
                <div class="form-group col-md-3">
                    <label for="name">Nombre de la tarjeta</label>
                    <input type="text" id="name" class="form-control" data-stripe="name">
                </div>
                <div class="form-group col-md-3">
                    <label for="number">Numero de la tarjeta</label>
                    <input type="text" id="number" class="form-control" data-stripe="number">
                </div>
                <div class="form-group col-md-3">
                    <label for="cvc">CVC</label>
                    <input type="text" id="cvc" class="form-control" data-stripe="cvc">
                </div>
                <div class="form-group col-md-3">
                    <label for="name">Mes de caducidad</label>
                    <select id="exp-month" class="form-control" data-stripe="exp_month">
                        <option value=""></option>
                        <?php for($i=1;$i < 13; i++): ?>
                        <option value="<?=$i;?>"><?=$i;?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="name">Año de caducidad</label>
                    <select id="exp-year" class="form-control" data-stripe="exp_year">
                        <option value=""></option>
                        <?php $año = date("Y");?>
                        <?php for($i=0;$i<11; $i++): ?>
                        <option value="<?=$año + $i;?>"><?=$año + $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
      </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="observar_direccion();" id="next_button">Siguiente</button>
        <button type="button" class="btn btn-primary" onclick="regresar_direccion();" id="back_button" style="display:none;">Regresar</button>
        <button type="button" class="btn btn-primary" id="check_out_button" style="display:none;">Revisar</button>
         </form>
      </div>
    </div>
  </div>
</div>
            <?php endif; ?>
    </div>
</div>
<script>
    function regresar_direccion(){
        jQuery('#payment-errors').html("");
        jQuery('#step1').css("display","block");
        jQuery('#step2').css("display","none");
        jQuery('#next_button').css("display","inline-block");
        jQuery('#back_button').css("display","none");
        jQuery('#checkout_button').css("display","none");
        jQuery('#checkoutModalLabel').html("Direccion de compra");
    }
    
    function observar_direccion(){
        var datos = {
            'full_name' : jQuery('#full_name').val(),
            'email' : jQuery('#email').val(),
            'street' : jQuery('#street').val(),
            'street2' : jQuery('#street2').val(),
            'city' : jQuery('#city').val(),
            'state' : jQuery('#state').val(),
            'zip_code' : jQuery('zip_code').val(),
            'country' : jQuery('#country').val(),
        };
        jQuery.ajax({
            url : '/tutorial/admin/parsers/check_address.php',
            method : 'POST',
            data : data,
            success : function(data){
            if(data != 'pasa'){
                jQuery('#payment-errors').html(data);
            }
                if(data == 'pasa'){
                    jQuery('#payment-errors').html("");
                    jQuery('#step1').css("display","none");
                    jQuery('#step2').css("display","block");
                    jQuery('#next_button').css("display","none");
                    jQuery('#back_button').css("display","inline-block");
                    jQuery('#checkout_button').css("display","inline-block");
                    jQuery('#checkoutModalLabel').html("Ingresa los detalles de su tarjeta");
                }
            },
            error : function{alert("Algo esta mal");},
        });
    }
    Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');
    
  function stripeResponseHandler(status, response) {
  var $form = $('#payment-form');

  if (response.error) {
    // Show the errors on the form
    $form.find('#payment-errors').text(response.error.message);
    $form.find('button').prop('disabled', false);
  } else {
    // response contains id and card, which contains additional card details
    var token = response.id;
    // Insert the token into the form so it gets submitted to the server
    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
    // and submit
    $form.get(0).submit();
  }
};
    
  jQuery(function($) {
  $('#payment-form').submit(function(event) {
    var $form = $(this);

    // Disable the submit button to prevent repeated clicks
    $form.find('button').prop('disabled', true);

    Stripe.card.createToken($form, stripeResponseHandler);

    // Prevent the form from submitting with the default action
    return false;
  });
});
</script>

<?php include 'includes/footer.php'; ?>