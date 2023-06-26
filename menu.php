<?php 
/*
 * El menú mostrará todas las opciones de acceso a las diferentes partes (inicio de sesión, registro, 
 * inicio, catálogo en todas sus formas). Aunque es importante mencionar que varias de las opciones dependerán
 * de si el usuario ha iniciado sesión o no pues en caso de hacerlo las opciones de usuario serán un saludo y cerrar sesión
 * así como la opción de acceder a pago.php una vez se quiera relizar el pago en checkout.php. En el caso
 * contrario, al no iniciar sesión se mostrarán las posibilidades de iniciar, registrar y el acceso a
 * pago.php se verá imposibilitado.
 */
?>

<!-- Navigation-->
<header>
    <div class="p-3 text-center bg-white border-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-4 d-flex justify-content-center justify-content-md-start mb-3 mb-md-0">
                    <a href="#!" class="ms-md-2">
                        <img src="img/logotipo.png" width="60" height="40">
                    </a>
                </div>
                <div class="col-md-4">
                    <!--<h5 style="font-family: 'Lato'; color: rgba(0, 0, 0, 0.55); ">Nelumbo Nucifera</h5>-->
                    <form action="#" method="POST" class="d-flex input-group w-auto my-auto mb-3 mb-md-0">
                        <input name="busqueda" autocomplete="off" type="search" class="form-control rounded" placeholder="">
                       <!-- <span class="input-group-text border-0 d-none d-lg-flex"><i
                                class="fas fa-search">
                            </i></span>-->
                    </form>
                </div>

                <div class="col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
                    <div class="d-flex">

                        <div class="dropdown">
                            <a class="text-reset me-3 dropdown-toggle hidden-arrow" href="#"
                               id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                                <?php if (isset($_SESSION['user_id'])) {
                                    ?>
                                    <li><a class="dropdown-item" href="historial_compras.php">Tus Pedidos</a></li>
                                <?php } ?>
                                <li>
                                    <a class="dropdown-item" href="checkout.php">Comprar</a>
                                </li>
                            </ul>
                        </div>


                        <div class="dropdown">
                            <a class="text-reset dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                               id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
                               aria-expanded="false">
                                <img src="img/assets/avatar.png"
                                     class="rounded-circle" height="22" alt="" loading="lazy">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                                <?php if (!isset($_SESSION['user_id'])) {
                                    ?>
                                    <li><a class="dropdown-item" href="inicio_sesion.php">Inicia sesión</a></li> 
                                    <li><a class="dropdown-item" href="Registro.php">Regístrate</a></li>
                                <?php } if (isset($_SESSION['user_id'])) {
                                    ?>
                                    <li><a class="dropdown-item" href="#">Bienvenidx <?php echo $_SESSION['user_name']; ?></a></li>
                                    <li><a class="dropdown-item" href="cerrar_sesion.php">Cerrar sesión</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container justify-content-center justify-content-md-between">
            <ul class="navbar-nav flex-row">
                <li class="nav-item me-2 me-lg-0">
                    <a class="nav-link" href="#" role="button" data-mdb-toggle="sidenav"
                       data-mdb-target="#sidenav-1" aria-controls="#sidenav-1" aria-haspopup="true">
                        <i class="fas fa-bars me-1"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
                    <a class="nav-link" href="index.php"><span>Home</span></a>
                    
                </li>
                <li class="nav-item me-2 me-lg-0 d-none d-md-inline-block">
                    <a class="nav-link" href="#contact"><span>Contacto</span></a>
                    
                </li>

            </ul>
        </div>
    </nav>

    <div id="sidenav-1" class="sidenav sidenav-primary ps" role="navigation" data-mdb-hidden="true"
         data-mdb-accordion="true"
         style="width: 240px; height: 100vh; position: fixed; transition: all 0.3s linear 0s;">
        <ul class="sidenav-menu">
            <li class="sidenav-item">
                <a class="sidenav-link ripple-surface" href="catalogo.php"><i class="fab fa-hotjar pe-3"></i><span>Todo</span></a>
            </li>
            <li class="sidenav-item">
                <?php $categoria = 1; ?>
                <a class="sidenav-link ripple-surface" data-mdb-toggle="collapse" href="#sidenav-collapse-1-0-1" role="button" aria-expanded="false"> 
                    <i class="fas fa-layer-group pe-3"></i><span>Pantalones</span><i
                        class="fas fa-angle-down rotate-icon" style="transform: rotate(180deg);"></i></a>
                <ul class="sidenav-collapse show collapse" id="sidenav-collapse-1-0-0">
                    <li class="sidenav-item">
                        <a class="sidenav-link ripple-surface" href = "catalogo.php?categoria=<?php echo $categoria; ?>&token=
                           <?php echo hash_hmac('sha1', $categoria, KEY_TOKEN); ?>">Largos</a>
                    </li>
                    <li class="sidenav-item">
                        <?php $categoria = 3; ?>
                        <a class="sidenav-link ripple-surface" href = "catalogo.php?categoria=<?php echo $categoria; ?>&token=
                           <?php echo hash_hmac('sha1', $categoria, KEY_TOKEN); ?>">Leggins</a>
                    </li>
                </ul>
            </li>
            <li class="sidenav-item">
                <a class="sidenav-link collapsed ripple-surface" data-mdb-toggle="collapse"
                   href="#sidenav-collapse-1-0-1" role="button" aria-expanded="false"><i
                        class="fas fa-gem pe-3"></i><span>Tops y camisas</span><i
                        class="fas fa-angle-down rotate-icon"></i></a>
                <ul class="sidenav-collapse collapse" id="sidenav-collapse-1-0-1">
                    <li class="sidenav-item">
                        <?php $categoria = 5; ?>
                        <a class="sidenav-link ripple-surface" href = "catalogo.php?categoria=<?php echo $categoria; ?>&token=
                           <?php echo hash_hmac('sha1', $categoria, KEY_TOKEN); ?>">Tops</a>
                    </li>
                    <li class="sidenav-item">
                        <a class="sidenav-link ripple-surface">Camisas</a>
                    </li>
                </ul>
            </li>
            <li class="sidenav-item">
                <a class="sidenav-link collapsed ripple-surface" data-mdb-toggle="collapse"
                   href="#sidenav-collapse-1-0-2" role="button" aria-expanded="false"><i
                        class="fas fa-gift pe-3"></i><span>Accesorios</span><i
                        class="fas fa-angle-down rotate-icon"></i></a>
                <ul class="sidenav-collapse collapse" id="sidenav-collapse-1-0-2">
                    <li class="sidenav-item">
                        <a class="sidenav-link ripple-surface">Sombreros</a>
                    </li>
                    <li class="sidenav-item">
                        <?php $categoria = 4; ?>
                        <a class="sidenav-link ripple-surface" href = "catalogo.php?categoria=<?php echo $categoria; ?>&token=
                           <?php echo hash_hmac('sha1', $categoria, KEY_TOKEN); ?>">Pendientes</a>
                    </li>
                </ul>
            </li>
            <li class="sidenav-item">
                <a class="sidenav-link collapsed ripple-surface" data-mdb-toggle="collapse"
                   href="#sidenav-collapse-1-0-3" role="button" aria-expanded="false"><i
                        class="fas fa-fire-alt pe-3"></i><span>Zapatos</span><i
                        class="fas fa-angle-down rotate-icon"></i></a>
                <ul class="sidenav-collapse collapse" id="sidenav-collapse-1-0-3">
                    <li class="sidenav-item">
                        <a class="sidenav-link ripple-surface">Plataformas</a>
                    </li>
                    <li class="sidenav-item">
                        <a class="sidenav-link ripple-surface">Stilettos</a>
                    </li>
                </ul>
            </li>
            <li class="sidenav-item">
                <?php $categoria = 2; ?>
                <a class="sidenav-link ripple-surface" href = "catalogo.php?categoria=<?php echo $categoria; ?>&token=
                   <?php echo hash_hmac('sha1', $categoria, KEY_TOKEN); ?>"><i class="fab fa-hotjar pe-3"></i><span>Abrigos y chaquetas</span></a>
            </li>
            <li class="sidenav-item">
                <a class="sidenav-link ripple-surface"><i class="fab fa-hotjar pe-3"></i><span>Vestidos</span></a>
            </li>
        </ul>

        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
        </div>
        <div class="ps__rail-y" style="top: 0px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
        </div>
    </div>
    <!-- Sidenav -->    
</header>