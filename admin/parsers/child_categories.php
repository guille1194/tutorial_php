<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/tutorial/core/init.php';
$padreID = (int)$_POST['padreID'];
$seleccionado = desinfectar($_POST['seleccionado']);
$hijoQuery = $db->query("select * from categorias where padre = '$padreID' order by categoria");
ob_start(): ?>
<option value=""></option>
<?php while ($hijo= mysqli_fetch_assoc($hijoQuery)): ?>
<option value="<?=$hijo['id'];?>"<?=(($seleccionado == $hijo['id'])?' seleccionado':'');?>><?=$hijo['categoria'];?></option>
<?php endwhile; ?>
<?php echo ob_get_clean(); ?>