<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Obelis Store - Tu tienda online de confianza">
   <meta name="author" content="Samuel Rondon">
   <title>Obelis Store - Simplifica tu vida, eleva tu estilo</title>
   
   <!-- CSS Framework -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/@material-dashboard/bootstrap@4.0.0/assets/css/material-dashboard.min.css" rel="stylesheet">
   
   <!-- Fuentes -->
   <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap&subset=latin-ext" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>icomoon/style.css">
   
   <!-- Estilos de la aplicación -->
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/navbar.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/productos.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/productos-grid.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/banner-carousel.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/cliente-area.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/producto-detalles.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/principal/css/style.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/principal/css/header-principal.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/principal/css/responsive.css">
   
   <!-- Componentes CSS -->
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/principal/css/owl.carousel.min.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/principal/css/owl.theme.default.min.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/principal/slick/slick.css">
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/principal/slick/slick-theme.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
   
   <!-- Favicon -->
   <link rel="icon" href="<?php echo BASE_URL; ?>assets/principal/images/favicon.png" type="image/png">
   
   <!-- Scripts críticos que deben cargarse en el header -->
   <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo MONEDA; ?>" defer></script>
   
</head>

<body>
   <!-- header section start -->
   <nav class="navbar navbar-expand-lg bg-transparent w-100 fixed-navbar rounded-4 mt-2 mx-auto px-3" style="max-width: 1200px; margin-top: 0 !important; z-index: 1050; position: relative; box-shadow: none;">
      <div class="container-fluid">
         <!-- Logo -->
         <a href="<?php echo BASE_URL; ?>" class="navbar-brand d-flex align-items-center gap-2">
            <img src="<?php echo BASE_URL; ?>assets/images/logo.jpg" width="48" alt="Logo" class="rounded-circle border border-2 border-info">
            <span class="fw-bold fs-4 text-white">Obelis Store</span>
         </a>
         <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Menú de Secciones -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
               <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="fa fa-bars me-1"></i>Secciones
                  </a>
                  <ul class="dropdown-menu shadow rounded-3 bg-gradient-dark border-0">
                     <li><a class="dropdown-item text-info" href="<?php echo BASE_URL; ?>productos/destacados">Destacados</a></li>
                     <li><a class="dropdown-item text-info" href="<?php echo BASE_URL; ?>productos/nuevos">Lo Nuevo</a></li>
                     <li><a class="dropdown-item text-info" href="<?php echo BASE_URL; ?>productos/ofertas">Ofertas</a></li>
                     <li><hr class="dropdown-divider bg-info"></li>
                     <li><a class="dropdown-item text-info" href="<?php echo BASE_URL; ?>productos/categorias">Categorías</a></li>
                  </ul>
               </li>
            </ul>
            <!-- Barra de búsqueda -->
            <form class="search-animated d-flex ms-auto me-3 position-relative align-items-center" role="search" autocomplete="off" style="max-width:300px;">
               <input class="form-control search-input bg-transparent text-white border-0 rounded-pill ps-4" type="search" placeholder="¿Qué estás buscando?" aria-label="Buscar" id="search" style="box-shadow:none; display:none; min-width:300px; opacity:0; position:absolute; right:40px; transition:all 0.7s cubic-bezier(.68,-0.55,.27,1.55); color: white; background-color: rgba(255,255,255,0.1) !important;">
               <button type="button" class="btn search-toggle p-0 text-info bg-transparent border-0" style="z-index:2; font-size:1.4rem;"><i class="fa fa-search"></i></button>
            </form>
            <!-- Menú de usuario y carrito -->
            <ul class="navbar-nav align-items-center gap-2">
               <li class="nav-item">
                  <a href="#" id="verCarrito" class="nav-link position-relative text-white" data-bs-toggle="modal" data-bs-target="#modalCarrito">
                     <i class="fa fa-shopping-cart fs-5"></i>
                     <span class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1" id="btnCantidadCarrito">Cart</span>
                  </a>
               </li>
               <?php if (empty($_SESSION['nombreCliente'])) { ?>
                  <li class="nav-item">
                     <a href="#" class="nav-link text-white d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalLogin">
                        <i class="fa fa-user"></i> <span>Login</span>
                     </a>
                  </li>
               <?php } else { ?>
                  <li class="nav-item dropdown">
                     <a href="#" class="nav-link dropdown-toggle d-flex align-items-center text-white" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user"></i>
                        <span class="ms-1 text-capitalize"><?php echo $_SESSION['nombreCliente']; ?></span>
                     </a>
                     <ul class="dropdown-menu dropdown-menu-end shadow rounded-3 bg-gradient-dark border-0" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item text-info" href="<?php echo BASE_URL; ?>clientes">Mi Cuenta</a></li>
                        <li><hr class="dropdown-divider bg-info"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>clientes/salir">Cerrar Sesión</a></li>
                     </ul>
                  </li>
               <?php } ?>
            </ul>
         </div>
      </div>
   </nav>
   <!-- header section end -->

   <!-- ...existing code...