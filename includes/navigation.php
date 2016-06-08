<?php
$sql = "Select * from categorias where padre = 0";
$pquery = $db->query($sql);
?>      
        
         <!--Top barra navegacion o navbar-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <a href="index.php" class="navbar-brand">Payne's Store</a>
                <ul class="nav navbar-nav">
                  <?php while($padre = mysqli_fetch_assoc($pquery)) : ?>
                  <?php 
                    $padre_id = $padre['id']; 
                    $sql2 = "Select * from categorias where padre = '$padre_id'";
                    $hquery = $db->query($sql2);
                    ?>
                    
                   <!--Items menu-->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $padre['categoria']; ?><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                        <?php while($hijo = mysqli_fetch_assoc($hquery)) : ?>
                            <li><a href="#"><?php echo $hijo['categoria']; ?></a></li>
                            <?php endwhile; ?>
                        </ul>
                    </li>
                    <?php endwhile; ?>
            </div>
        </nav>