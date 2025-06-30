<!-- copyright section start -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5>Sobre Nosotros</h5>
                <p>Obelis Store es tu destino para encontrar los mejores productos con la mejor calidad y precio.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>" class="text-white">Inicio</a></li>
                    <li><a href="<?php echo BASE_URL; ?>principal/tienda" class="text-white">Tienda</a></li>
                    <li><a href="<?php echo BASE_URL; ?>principal/contacto" class="text-white">Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Contacto</h5>
                <address>
                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>Dirección del local</p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>+51 123 456 789</p>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>info@obelis-store.com</p>
                </address>
            </div>
        </div>
        <hr class="bg-light">
        <div class="text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Obelis Store. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<!-- Carritos y Modales -->
<div class="modal fade" id="modalCarrito" tabindex="-1" aria-labelledby="modalCarritoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCarritoLabel">Mi Carrito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="contentCarrito" class="table-responsive">
                    <!-- El contenido del carrito se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div id="modalLogin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login y Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body m-3">
        <div class="text-center">
          <img class="img-thumbnail rounded-circle" src="<?php echo BASE_URL . 'assets/img/logo.png'; ?>" alt="" width="100">
        </div>
        <div class="row">
          <div class="col-md-12" id="frmLogin">
            <div class="form-group mb-3">
              <label for="correoLogin"><i class="fas fa-envelope"></i> Correo</label>
              <input id="correoLogin" class="form-control" type="text" name="correoLogin" placeholder="Correo Electrónico">
            </div>
            <div class="form-group mb-3">
              <label for="claveLogin"><i class="fas fa-key"></i> Contraseña</label>
              <input id="claveLogin" class="form-control" type="text" name="claveLogin" placeholder="Contraseña">
            </div>
            <a href="#" id="btnRegister">¿Todavia no tienes una cuenta?</a>
            <div class="float-right">
              <button class="btn btn-primary" type="button" id="login">Login</button>
            </div>
          </div>
          <!-- formulario de registro -->
          <div class="col-md-12 d-none" id="frmRegister">
            <div class="form-group mb-3">
              <label for="nombreRegistro"><i class="fas fa-list"></i> Nombre</label>
              <input id="nombreRegistro" class="form-control" type="text" name="nombreRegistro" placeholder="Nombre" required>
            </div>
            <div class="form-group mb-3">
              <label for="apellidoRegistro"><i class="fas fa-list"></i> Apellido</label>
              <input id="apellidoRegistro" class="form-control" type="text" name="apellidoRegistro" placeholder="Apellido (opcional)">
            </div>
            <div class="form-group mb-3">
              <label for="correoRegistro"><i class="fas fa-envelope"></i> Correo</label>
              <input id="correoRegistro" class="form-control" type="text" name="correoRegistro" placeholder="Correo Electrónico" required>
            </div>
            <div class="form-group mb-3">
              <label for="claveRegistro"><i class="fas fa-key"></i> Contraseña</label>
              <input id="claveRegistro" class="form-control" type="password" name="claveRegistro" placeholder="Contraseña" required>
            </div>
            <div class="form-group mb-3">
              <label for="perfilRegistro"><i class="fas fa-image"></i> Foto de perfil (opcional)</label>
              <input id="perfilRegistro" class="form-control" type="file" name="perfilRegistro" accept="image/*">
            </div>
            <a href="#" id="btnLogin">¿Ya tienes una cuenta?</a>
            <div class="float-right">
              <button class="btn btn-primary" type="button" id="registrarse">Registrarse</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- footer section end -->
</div>
<!-- Footer scripts -->

<!-- Javascript files-->
<!-- Core Javascript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/all.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/fontawesome.min.js"></script>

<!-- Plugin Javascript -->
<script src="<?php echo BASE_URL; ?>assets/principal/js/plugin.js"></script>
<script src="<?php echo BASE_URL; ?>assets/principal/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/principal/slick/slick.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!-- Variables globales y configuración -->
<script>
    const base_url = '<?php echo BASE_URL; ?>';
    const DEFAULT_PRODUCT_IMAGE = '<?php echo DEFAULT_PRODUCT_IMAGE; ?>';

    // Configuración de alertas
    function alertaPerzonalizada(mensaje, type, titulo = '') {
        toastr[type](mensaje, titulo)
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000"
        }
    }
</script>

<!-- Application Javascript -->
<script src="<?php echo BASE_URL; ?>assets/principal/js/custom.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/navbar.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/carrito.js"></script> <!-- ¿carrito? -->
<script src="<?php echo BASE_URL; ?>assets/principal/js/header-principal.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/modal-producto.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/login.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/productos-carousel.js"></script>