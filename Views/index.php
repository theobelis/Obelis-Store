<?php 
include_once 'Views/template/header-principal.php';

// Los productos destacados vienen desde el controlador en $data['productos']
$productos = $data['productos'] ?? [];

// Obtener galería para todos los productos destacados
function getGaleriaProducto($id) {
    $galeria = [];
    $directorio = __DIR__ . '/../assets/images/productos/' . $id;
    if (file_exists($directorio)) {
        $imagenes = scandir($directorio);
        foreach ($imagenes as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'webp') {
                $galeria[] = BASE_URL . 'assets/images/productos/' . $id . '/' . $file;
            }
        }
        natsort($galeria);
    }
    return $galeria;
}
?>

<body class="bg-gradient-dark">
   <!-- banner bg main start -->
   <div class="banner-bg-main" style="position:relative; z-index:0; margin-top:-90px;">
      <div class="banner-card" style="position:relative; z-index:1; min-height:600px;">
         <div class="position-relative w-100 h-100" style="min-height: 600px;">
            <div class="banner-slide-bg position-absolute top-0 start-0 w-100 h-100" style="background: url('<?php echo BASE_URL; ?>assets/principal/images/banner-slide/1.png') no-repeat center center; background-size: cover; opacity: 0.5; z-index: 0; height: 100%; min-height: 600px; object-fit: contain;"></div>
            <div class="position-absolute bottom-0 start-0 w-100"></div>
            <div class="position-relative d-flex flex-column justify-content-center align-items-center h-100 w-100" style="z-index: 3; min-height:600px;">
               <!-- Slide de textos de presentación -->
               <div id="bannerTextCarousel" class="carousel slide w-100" data-bs-ride="carousel">
                  <div class="carousel-inner text-center py-5">
                     <div class="carousel-item active">
                        <h1 class="display-5 fw-bold">¡Bienvenido a Obelis Store!</h1>
                        <p class="lead">Simplifica tu vida, eleva tu estilo</p>
                        <a href="#" class="btn btn-info btn-lg mt-3 text-dark fw-bold boton-banner-slide">Vamos</a>
                     </div>
                     <div class="carousel-item">
                        <h1 class="display-5 fw-bold">Encuentra productos únicos</h1>
                        <p class="lead">Calidad, variedad y atención personalizada</p>
                        <a href="#" class="btn btn-info btn-lg mt-3 text-dark fw-bold boton-banner-slide">Vamos</a>
                     </div>
                     <div class="carousel-item">
                        <h1 class="display-5 fw-bold">Compra fácil y seguro</h1>
                        <p class="lead">Envíos a todo el país y soporte dedicado</p>
                        <a href="#" class="btn btn-info btn-lg mt-3 text-dark fw-bold boton-banner-slide">Vamos</a>
                     </div>
                  </div>
                  <div class="carousel-controls-container" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: 1200px; display: flex; justify-content: space-between; pointer-events: none;">
                     <button class="carousel-control-prev" type="button" data-bs-target="#bannerTextCarousel" data-bs-slide="prev" style="position: relative; pointer-events: auto;">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                     </button>
                     <button class="carousel-control-next" type="button" data-bs-target="#bannerTextCarousel" data-bs-slide="next" style="position: relative; pointer-events: auto;">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- banner bg main end -->

   <section class="productos-grid">
    <div class="container">
        <div class="section-title text-start">
            <h2 class="text-white">Productos Destacados</h2>
            <p class="text-white-50">Descubre nuestra selección especial</p>
        </div>

        <?php if (!empty($productos)) : ?>
        <div id="productosDestacadosCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-indicators">
                <?php
                $total_slides = ceil(count($productos) / 4); // 4 productos por slide
                for ($i = 0; $i < $total_slides; $i++) {
                    echo '<button type="button" data-bs-target="#productosDestacadosCarousel"
                            data-bs-slide-to="'.$i.'" '.($i === 0 ? 'class="active" aria-current="true"' : '').'
                            aria-label="Slide '.($i + 1).'"></button>';
                }
                ?>
            </div>

            <div class="carousel-inner">
                <?php
                $productos_chunks = array_chunk($productos, 4); // Dividir en grupos de 4
                foreach ($productos_chunks as $index => $grupo) :
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="row g-3 justify-content-center">
                        <?php foreach ($grupo as $producto) :
                            $galeria = isset($producto['id']) ? getGaleriaProducto($producto['id']) : [];
                            $imagen_destacada = !empty($galeria) ? $galeria[0] : (isset($producto['imagen']) ? getImageUrl($producto['imagen']) : BASE_URL . 'assets/img/placeholder.png');
                        ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                            <div class="card h-100">
                                <div class="img-zoom-container">
                                    <a href="<?php echo BASE_URL . 'productos/detalle/' . htmlspecialchars($producto['id']); ?>"
                                       aria-label="Ver detalles de <?php echo htmlspecialchars($producto['nombre']); ?>">
                                        <img src="<?php echo htmlspecialchars($imagen_destacada); ?>"
                                             class="card-img-top img-fluid"
                                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                             loading="lazy">
                                        <?php if (!empty($galeria)) : ?>
                                            <div class="gallery-icon position-absolute top-0 end-0 p-2">
                                                <i class="fas fa-images text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <a href="<?php echo BASE_URL . 'productos/detalle/' . htmlspecialchars($producto['id']); ?>"
                                           class="text-decoration-none"
                                           title="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                            <?php echo htmlspecialchars($producto['nombre']); ?>
                                        </a>
                                    </h5>
                                    <div class="precio mb-2">
                                        $<?php echo number_format(isset($producto['precio']) ? $producto['precio'] : 0, 2); ?>
                                    </div>
                                    <div class="rating mb-2">
                                        <?php
                                        $calificacion = isset($producto['calificacion']) ? $producto['calificacion'] : 0;
                                        for($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $calificacion ? 'text-warning' : 'text-muted'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="product-buttons mt-auto"> <button class="btn btn-carrito btnAddCarrito"
                                                data-id="<?php echo htmlspecialchars($producto['id']); ?>"
                                                aria-label="Agregar <?php echo htmlspecialchars($producto['nombre']); ?> al carrito">
                                            <i class="fas fa-shopping-cart me-1"></i>Añadir al Carrito
                                        </button>
                                        <div class="bottom-buttons">
                                            <a href="<?php echo BASE_URL . 'productos/detalle/' . htmlspecialchars($producto['id']); ?>"
                                               class="btn btn-detalles"
                                               target="_blank"
                                               rel="noopener"
                                               aria-label="Ver detalles de <?php echo htmlspecialchars($producto['nombre']); ?>">
                                                <i class="fas fa-eye me-1"></i>Detalles
                                            </a>
                                            <button class="btn btn-favoritos btnAddFavoritos"
                                                    data-id="<?php echo htmlspecialchars($producto['id']); ?>"
                                                    aria-label="Añadir <?php echo htmlspecialchars($producto['nombre']); ?> a favoritos">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#productosDestacadosCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productosDestacadosCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
        <?php else : ?>
            <div class="alert alert-info text-center mt-4" role="alert">
                No hay productos destacados disponibles en este momento.
            </div>
        <?php endif; ?>
    </div>
</section>

   <section class="seccion-otros-productos">
      <div class="container">
         <div class="section-title text-center">
            <h2 class="text-white">Otros Productos</h2>
            <p class="text-white-50">Explora nuestra amplia gama de productos</p>
         </div>
         <div class="row g-3 justify-content-center">
            <?php foreach ($data['otros_productos'] as $producto) : ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
               <div class="card h-100">
                  <div class="img-zoom-container">
                     <a href="<?php echo BASE_URL . 'productos/detalle/' . htmlspecialchars($producto['id']); ?>"
                        aria-label="Ver detalles de <?php echo htmlspecialchars($producto['nombre']); ?>">
                        <img src="<?php echo getImageUrl($producto['imagen']); ?>"
                             class="card-img-top img-fluid"
                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                             loading="lazy">
                     </a>
                  </div>
                  <div class="card-body d-flex flex-column">
                     <h5 class="card-title">
                        <a href="<?php echo BASE_URL . 'productos/detalle/' . htmlspecialchars($producto['id']); ?>"
                           class="text-decoration-none"
                           title="<?php echo htmlspecialchars($producto['nombre']); ?>">
                           <?php echo htmlspecialchars($producto['nombre']); ?>
                        </a>
                     </h5>
                     <div class="precio mb-2">
                        $<?php echo number_format($producto['precio'], 2); ?>
                     </div>
                     <div class="rating mb-2">
                        <?php
                        $calificacion = $producto['calificacion'] ?? 0;
                        for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= $calificacion ? 'text-warning' : 'text-muted'; ?>"></i>
                        <?php endfor; ?>
                     </div>
                     <div class="product-buttons mt-auto">
                        <button class="btn btn-carrito btnAddCarrito"
                                data-id="<?php echo htmlspecialchars($producto['id']); ?>"
                                aria-label="Agregar <?php echo htmlspecialchars($producto['nombre']); ?> al carrito">
                            <i class="fas fa-shopping-cart me-1"></i>Añadir al Carrito
                        </button>
                        <div class="bottom-buttons">
                           <a href="<?php echo BASE_URL . 'productos/detalle/' . htmlspecialchars($producto['id']); ?>"
                              class="btn btn-detalles"
                              target="_blank"
                              rel="noopener"
                              aria-label="Ver detalles de <?php echo htmlspecialchars($producto['nombre']); ?>">
                              <i class="fas fa-eye me-1"></i>Detalles
                           </a>
                           <button class="btn btn-favoritos btnAddFavoritos"
                                   data-id="<?php echo htmlspecialchars($producto['id']); ?>"
                                   aria-label="Añadir <?php echo htmlspecialchars($producto['nombre']); ?> a favoritos">
                              <i class="fas fa-heart"></i>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <?php endforeach; ?>
         </div>
      </div>
   </section>

<?php include_once 'Views/template/footer-principal.php'; ?>