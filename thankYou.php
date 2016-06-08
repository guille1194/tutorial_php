<?php
require_once 'core/init.php';

// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];
//Obtener el resto de los datos
$nombre_completo = desinfectar($_POST['nombre_completo']);
$email = desinfectar($_POST['email']);
$calle = desinfectar($_POST['calle']);
$calle2 = desinfectar($_POST['calle2']);
$ciudad = desinfectar($_POST['ciudad']);
$estado = desinfectar($_POST['estado']);
$codigo_postal = desinfectar($_POST['codigo_postal']);
$pais = desinfectar($_POST['pais']);
$impuesto = desinfectar($_POST['impuesto']);
$subtotal = desinfectar($_POST['subtotal']);
$gran_total = desinfectar($_POST['gran_total']);
$id_carrito = desinfectar($_POST['id_carrito']);
$descripcion = desinfectar($_POST['descripcion']);
$monto_cargo = number_format($gran_total,2) * 100;
$metadatos = array(
    "id_carrito" => $id_carrito,
    "impuesto" => $impuesto,
    "subtotal" => $subtotal,
);
// Create the charge on Stripe's servers - this will charge the user's card
try {
  $charge = \Stripe\Charge::create(array(
    "amount" => $monto_carga, // amount in cents, again
    "currency" => CURRRENCY,
    "source" => $token,
    "description" => $descripcion,
    "receipt_email" => $email,
    "metadata" => $metadatos)
    );
    //ajustar inventario
    $articuloQ = $db->query("select * from carrito where id = '{$id_carrito}'");
    $iresultados = mysqli_fetch_assoc($articuloQ);
    $articulos = json_decode($iresultados['articulos'],true);
    foreach($articulos as $articulo){
        $nuevosTamaños = array();
        $id_articulo = $articulo['id'];
        $productoQ = $db->query("select tamaños from productos where id = '{$id_articulo}'");
        $producto = mysqli_fetch_assoc($productoQ);
        $tamaños = tamañosaArray($producto['tamaños']);
        foreach($tamaños as $tamaño){
            if($tamaño['tamaño'] == $articulo['tamaño']){
                $c = $tamaño['cantidad'] - $articulo['cantidad'];
                $nuevosTamaños[] = array('tamaño' => $tamaño['tamaño'],'cantidad' => $c);
        }
            else{
                $nuevosTamaños[] = array('tamaño' => $tamaño['tamaño'],'cantidad => $tamaño['cantidad']');
            }
    }
    $tamañoString = $tamañosaString($nuevosTamaños);
    $db->query("update productos set tamaños = '{$tamañoString}' where id = '{$id_articulo}'");
    }
}

    //Actualizar carrito
    $db->query("update carrito set pagado = 1 where id = '{$id_carrito}'");
    $db->query("insert into transacciones
              (id_carga, id_carrito,nombre_completo,email,calle,calle2,ciudad,estado.codigo_postal,pais,subtotal,impuesto,gran_total,descripcio,tipo_txn) values
               ('$carga->id','$id_carrito','$nombre_completo','$email','$calle','$calle2','$ciudad','$estado','$codigo_postal','$pais','$subtotal','$impuesto','$gran_total','$descripcion','$carga->objeto')");
    
    $dominio = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
    setcookie(CART_COOKIE,'',1,"/",$dominio,false);
    include 'includes/head.php';
    include 'includes/navigation.php';
    include 'includes/headerpartial.php';
    ?>
    <h1 class="text-center text-success">Gracias!</h1>
    <p>Su tarjeta ha sido cargada exitosamente <?=dinero($gran_total);?>.Se le ha enviado un email de recibo. Por favor observe en su carpeta de spam si no esta en la bandeja de entrada. Adicionalmente puede imprimir esta pegina como un recibo.</p>
    <p>Su numero de recibo es: <strong><?=$id_carrito;?></strong></p>
    <p>Su orden sera enviada a la direccion debajo.</p>
    <address>
        <?=$nombre_completo;?><br>
        <?=calle;?><br>
        <?=(($calle2 != '')$calle2.'<br>':'');?>
        <?=$ciudad. ', '.$estado.' '.$codigo_postal;?><br>
        <?=$pais;?><br>
    </address>
    <?php
} catch(\Stripe\Error\Card $e) {
  // The card has been declined
    echo $e;
}
?>