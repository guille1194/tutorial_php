         <!--Top barra navegacion o navbar-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <a href="index.php" class="navbar-brand">Payne's Store</a>
                <ul class="nav navbar-nav">
                <!--Articulos menu-->
                <li><a href="index.php">Mi Dashboard</a></li>
                <li><a href="publisher.php">Publishers</a></li>
                <li><a href="index.php">Categorias</a></li>
                <li><a href="categories.php">Productos</a></li>
                <li><a href="archived.php">Archivado</a></li>
                <?php if(has_permission('admin')):?>
                 <li><a href="users.php">Usuarios</a></li>
                  <?php endif;?>                                                           
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hola <?=$datos_usuario['primer'];?>!
                        <span class="carret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="change_password.php">Cambiar contraseña</a></li>
                            <li><a href="logout.php">Cerrar sesion</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
</nav>