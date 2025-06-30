<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalles del Producto | Obelis Store</title>
  <!-- Bootstrap y Material Dashboard -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@material-dashboard/bootstrap@4.0.0/assets/css/material-dashboard.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/producto-detalles.css">
</head>
<body class="bg-gray-900">
  <!-- Barra de navegación (copiada, no include) -->
  <nav class="navbar navbar-expand-lg bg-gradient-dark rounded-4 mt-2 mx-auto px-3" style="max-width: 1200px;">
    <div class="container-fluid">
      <a href="<?php echo BASE_URL; ?>" class="navbar-brand d-flex align-items-center gap-2">
        <img src="<?php echo BASE_URL; ?>assets/images/logo.jpg" width="48" alt="Logo" class="rounded-circle border border-2 border-info">
        <span class="fw-bold fs-4 text-white">Obelis Store</span>
      </a>
      <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarMain">
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
        <form class="search-animated d-flex ms-auto me-3 position-relative align-items-center" role="search" autocomplete="off" style="max-width:300px;">
          <input class="form-control search-input bg-transparent text-white border-0 rounded-pill ps-4" type="search" placeholder="¿Qué estás buscando?" aria-label="Buscar" id="search" style="box-shadow:none; display:none; min-width:300px; opacity:0; position:absolute; right:40px; transition:all 0.7s cubic-bezier(.68,-0.55,.27,1.55); color: white; background-color: rgba(255,255,255,0.1) !important;">
          <button type="button" class="btn search-toggle p-0 text-info bg-transparent border-0" style="z-index:2; font-size:1.4rem;"><i class="fa fa-search"></i></button>
        </form>
        <ul class="navbar-nav align-items-center gap-2">
          <li class="nav-item">
            <a href="#" id="verCarrito" class="nav-link position-relative text-white">
              <i class="fa fa-shopping-cart fs-5"></i>
              <span class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1" id="btnCantidadCarrito">Cart</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-white d-flex align-items-center gap-1">
              <i class="fa fa-user"></i> <span>Login</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Detalles del producto -->
  <main class="container my-5">
    <div class="row g-4 align-items-start bg-white rounded-4 shadow-lg p-4" style="width:100vw;max-width:100vw;margin-left:calc(-1 * ((100vw - 100%) / 2));margin-right:calc(-1 * ((100vw - 100%) / 2));overflow-y:auto;max-height:80vh;">
      <!-- Galería -->
      <div class="col-lg-5">
        <div id="productGalleryCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
          <div class="carousel-inner rounded-3 bg-light">
            <!-- Aquí van las imágenes del producto -->
            <?php $images = isset($data['galeria']) ? $data['galeria'] : [$data['imagen']];
            foreach($images as $index => $image): ?>
              <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $image; ?>" class="d-block w-100 object-fit-contain" style="max-height:340px; background:#f5f6fa;" alt="Imagen producto">
              </div>
            <?php endforeach; ?>
          </div>
          <?php if(count($images) > 1): ?>
          <button class="carousel-control-prev" type="button" data-bs-target="#productGalleryCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#productGalleryCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
          <?php endif; ?>
        </div>
        <!-- Miniaturas -->
        <?php if(count($images) > 1): ?>
        <div class="d-flex gap-2 justify-content-center">
          <?php foreach($images as $index => $image): ?>
            <img src="<?php echo $image; ?>" class="rounded border border-info bg-white" style="width:56px; height:56px; object-fit:cover; cursor:pointer; opacity:<?php echo $index === 0 ? '1' : '0.7'; ?>;" onclick="document.querySelector('#productGalleryCarousel').carousel(<?php echo $index; ?>)">
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <!-- Detalles y comentarios -->
      <div class="col-lg-7 d-flex flex-column" style="height:100%;">
        <div class="flex-grow-1 d-flex flex-column justify-content-between" style="min-height:500px;">
          <div>
            <div>
              <span class="badge bg-info text-dark mb-2" style="font-size:1rem;">Categoría: <?= isset($data['categoria']) ? $data['categoria'] : 'Sin categoría' ?></span>
            </div>
            <h1 class="fw-bold text-dark mb-2" style="font-family: 'Poppins', sans-serif; letter-spacing:0.5px;"> <?= $data['nombre'] ?> </h1>
            <div class="mb-3 d-flex align-items-center gap-3">
              <?php if(isset($data['calificacion'])): ?>
                <span class="d-inline-flex align-items-center">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= $data['calificacion'] ? 'text-warning' : 'text-muted'; ?>"></i>
                  <?php endfor; ?>
                  <span class="ms-2 text-info fw-semibold"> <?= number_format($data['calificacion'], 1) ?> / 5</span>
                </span>
              <?php endif; ?>
            </div>
            <div class="fs-3 fw-bold text-info mb-3">$<?= number_format($data['precio'], 2) ?></div>
            <div class="mb-3 text-secondary" style="min-height:80px;"> <?= $data['descripcion'] ?> </div>
            <div class="mb-3">
              <?php if(isset($data['colores']) && !empty($data['colores'])): ?>
                <div class="mb-2"><strong class="text-dark">Colores disponibles:</strong></div>
                <div class="d-flex gap-2 mb-2">
                  <?php foreach($data['colores'] as $color): ?>
                    <div class="color-circle" title="<?= $color ?>" style="background:<?= $color ?>; border:2px solid #00e1ff; width:28px; height:28px; cursor:pointer;"></div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <?php if(isset($data['tallas']) && !empty($data['tallas'])): ?>
                <div class="mb-2"><strong class="text-dark">Tallas disponibles:</strong></div>
                <div class="d-flex gap-2 mb-2">
                  <?php foreach($data['tallas'] as $talla): ?>
                    <span class="badge bg-info text-dark fs-6 px-3 py-2 talla-badge" style="cursor:pointer;"> <?= $talla ?> </span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="mb-3">
              <strong class="text-dark">Cantidad:</strong>
              <div class="input-group" style="max-width:160px;">
                <button class="btn btn-outline-info" type="button" onclick="cambiarCantidad(-1)">-</button>
                <input type="number" id="cantidad" class="form-control text-center" value="1" min="1" max="<?= $data['cantidad'] ?>">
                <button class="btn btn-outline-info" type="button" onclick="cambiarCantidad(1)">+</button>
              </div>
            </div>
            <div class="mb-4">
              <button class="btn btn-info btn-lg w-100 text-dark fw-bold shadow" onclick="agregarCarrito(<?= $data['id'] ?>, document.getElementById('cantidad').value)">
                <i class="fas fa-shopping-cart me-2"></i>Agregar al Carrito
              </button>
            </div>
          </div>
          <!-- Sección de reviews -->
          <?php if(isset($data['reviews'])): ?>
          <div class="bg-transparent p-0 mt-2" style="max-height:220px;overflow-y:auto;">
            <h3 class="text-white mb-3">Valoraciones del Producto</h3>
            <div class="reviews-list">
              <?php foreach($data['reviews'] as $review): ?>
              <div class="review-item border-bottom pb-3 mb-3">
                <div class="d-flex align-items-center mb-2">
                  <i class="fas fa-user-circle me-2 text-info fs-4"></i>
                  <strong class="me-2 text-white"> <?= $review['cliente'] ?> </strong>
                  <div class="ms-auto">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                      <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                    <?php endfor; ?>
                  </div>
                </div>
                <div class="text-secondary mb-1"> <?= $review['comentario'] ?> </div>
                <small class="text-muted"> <?= date('d/m/Y', strtotime($review['fecha'])) ?> </small>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
          <!-- Caja de comentario -->
          <div class="bg-transparent p-0 mt-2">
            <h3 class="text-white mb-3">Deja tu comentario</h3>
            <form class="comment-form" id="commentForm">
              <input type="hidden" name="product_id" value="<?= $data['id'] ?>">
              <div class="mb-3">
                <div class="rating-select mb-2">
                  <div class="stars">
                    <?php for($i = 5; $i >= 1; $i--): ?>
                      <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" />
                      <label for="star<?= $i ?>"><i class="fas fa-star"></i></label>
                    <?php endfor; ?>
                  </div>
                </div>
                <textarea name="comment" class="form-control" rows="4" placeholder="Comparte tu opinión sobre este producto..."></textarea>
              </div>
              <button type="submit" class="btn btn-info text-dark fw-bold">
                <i class="fas fa-paper-plane me-2"></i>Enviar comentario
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function cambiarCantidad(delta) {
      const input = document.getElementById('cantidad');
      const newValue = parseInt(input.value) + delta;
      if (newValue >= 1 && newValue <= <?= $data['cantidad'] ?>) {
        input.value = newValue;
      }
    }
    // Aquí puedes agregar más JS para interacción de tallas, colores, reviews, etc.
  </script>
</body>
</html>
